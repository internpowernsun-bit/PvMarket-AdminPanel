<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    // ── Index ─────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Warehouse::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $warehouses = $query->orderBy('created_at', 'desc')
                            ->paginate($request->get('entries', 10));

        return view('admin.warehouses.warehouses', [
            'mode'       => 'index',
            'warehouses' => $warehouses,
        ]);
    }

    // ── Create ────────────────────────────────────────
    public function create()
    {
        return view('admin.warehouses.warehouses', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    // ── Store ─────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:300',
        ]);

        Warehouse::create([
            'name'           => $request->name,
            'payment_status' => 'pending',
            'is_active'      => true,
            'updated_by'     => Auth::user()->name,
        ]);

        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Warehouse created successfully.');
    }

    // ── Edit ──────────────────────────────────────────
    public function edit($id)
    {
        $record = Warehouse::findOrFail($id);
        return view('admin.warehouses.warehouses', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    // ── Update ────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:300',
        ]);

        Warehouse::findOrFail($id)->update([
            'name'       => $request->name,
            'updated_by' => Auth::user()->name,
        ]);

        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Warehouse updated.');
    }

    // ── Mark As Paid ──────────────────────────────────
    public function markAsPaid($id)
    {
        Warehouse::findOrFail($id)->update([
            'payment_status' => 'paid',
            'updated_by'     => Auth::user()->name,
        ]);

        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Warehouse marked as paid.');
    }

    // ── Toggle active status ──────────────────────────
    public function toggleStatus($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update([
            'is_active'  => !$warehouse->is_active,
            'updated_by' => Auth::user()->name,
        ]);

        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Status updated.');
    }

    // ── Destroy ───────────────────────────────────────
    public function destroy($id)
    {
        Warehouse::findOrFail($id)->delete();
        return redirect()->route('admin.warehouses.index')
                         ->with('success', 'Warehouse deleted.');
    }
}