<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('search')) {
            $query->where('unit_name', 'like', '%' . $request->search . '%');
        }

        $units = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('entries', 10));

        return view('admin.setup.units.units', [
            'mode'  => 'index',
            'units' => $units,
        ]);
    }

    public function create()
    {
        return view('admin.setup.units.units', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'units'               => 'required|array|min:1',
            'units.*.unit_name'   => 'required|string|max:255',
            'units.*.unit_code'   => 'required|string|max:50',
            'units.*.description' => 'nullable|string|max:500',
        ]);

        foreach ($request->units as $item) {
            Unit::create([
                'unit_name'   => $item['unit_name'],
                'unit_code'   => strtoupper($item['unit_code']),
                'description' => $item['description'] ?? null,
                'is_active'   => true,
            ]);
        }

        return redirect()->route('admin.setup.units.index')
                         ->with('success', count($request->units) . ' unit(s) created successfully.');
    }

    public function edit($id)
    {
        $record = Unit::findOrFail($id);

        return view('admin.setup.units.units', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'unit_name'   => 'required|string|max:255',
            'unit_code'   => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $unit->update([
            'unit_name'   => $request->unit_name,
            'unit_code'   => strtoupper($request->unit_code),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.setup.units.index')
                         ->with('success', 'Unit updated successfully.');
    }

    public function toggleStatus($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->update(['is_active' => !$unit->is_active]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        Unit::findOrFail($id)->delete();

        return redirect()->route('admin.setup.units.index')
                         ->with('success', 'Unit deleted.');
    }
}