<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * Base class for all simple setup resource controllers.
 * Extend this and define $model, $view, $rules, $fields.
 *
 * Child controllers only need:
 *  - protected string $model   — Model class
 *  - protected string $view    — e.g. 'admin.setup.incoterms.incoterms'
 *  - protected string $route   — e.g. 'admin.setup.incoterms'
 *  - protected array  $rules   — validation rules
 *  - protected array  $fields  — fields to save from request
 *  - protected string $orderBy — field to sort by (default 'created_at')
 */
abstract class ResourceController extends Controller
{
    protected string $model;
    protected string $view;
    protected string $route;
    protected array  $rules  = [];
    protected array  $fields = [];
    protected string $orderBy = 'created_at';
    protected string $orderDir = 'desc';

    // ── Index ──────────────────────────────────────────────
    public function index(\Illuminate\Http\Request $request)
    {
        $query = $this->model::query();

        if ($request->filled('search') && !empty($this->fields)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                foreach ($this->fields as $field) {
                    $q->orWhere($field, 'like', '%' . $search . '%');
                }
            });
        }

        $records = $query
            ->orderBy($this->orderBy, $this->orderDir)
            ->paginate($request->get('entries', 10));

        return view($this->view, [
            'mode'    => 'index',
            'records' => $records,
        ]);
    }

    // ── Create ─────────────────────────────────────────────
    public function create()
    {
        return view($this->view, [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    // ── Store ──────────────────────────────────────────────
    public function store(\Illuminate\Http\Request $request)
    {
        // Support both single record and multi-row (items[])
        if ($request->has('items')) {
            $request->validate([
                'items'        => 'required|array|min:1',
                'items.*.name' => 'required|string|max:255',
            ]);
            foreach ($request->items as $item) {
                $this->model::create($this->extractFields($item, $request));
            }
            $count = count($request->items);
        } else {
            $request->validate($this->rules);
            $this->model::create($this->extractFields($request->all(), $request));
            $count = 1;
        }

        return redirect()->route($this->route . '.index')
            ->with('success', $count . ' record(s) saved successfully.');
    }

    // ── Edit ───────────────────────────────────────────────
    public function edit($id)
    {
        $record = $this->model::findOrFail($id);

        return view($this->view, [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    // ── Update ─────────────────────────────────────────────
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $record = $this->model::findOrFail($id);
        $request->validate($this->rules);
        $record->update($this->extractFields($request->all(), $request));

        return redirect()->route($this->route . '.index')
            ->with('success', 'Updated successfully.');
    }

    // ── Destroy ────────────────────────────────────────────
    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();

        return redirect()->route($this->route . '.index')
            ->with('success', 'Deleted successfully.');
    }

    // ── Helper: extract only allowed fields ────────────────
    protected function extractFields(array $data, $request = null): array
    {
        $result = [];
        foreach ($this->fields as $field) {
            if (array_key_exists($field, $data)) {
                $result[$field] = $data[$field];
            }
        }
        return $result;
    }
}