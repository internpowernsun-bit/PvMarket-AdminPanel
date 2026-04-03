<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChargeController extends Controller
{
    public function index(Request $request)
{
    $query = Charge::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $charges = $query->orderBy('created_at', 'desc')
                     ->paginate($request->get('entries', 10));

    return view('admin.setup.charges.charges', [
        'mode'    => 'index',
        'charges' => $charges,
    ]);
}

    public function create()
    {
        return view('admin.setup.charges.charges', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.name'    => 'required|string|max:255',
            'items.*.charge'  => 'required|numeric|min:0',
        ]);

        foreach ($request->items as $item) {
            Charge::create([
                'name'   => $item['name'],
                'charge' => $item['charge'],
                'slug'   => Str::slug($item['name']),
            ]);
        }

        return redirect()->route('admin.setup.charges.index')
                         ->with('success', count($request->items) . ' charge(s) added.');
    }

    public function edit($id)
    {
        $record = Charge::findOrFail($id);
        return view('admin.setup.charges.charges', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $charge = Charge::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'charge' => 'required|numeric|min:0',
        ]);

        $charge->update([
            'name'   => $request->name,
            'charge' => $request->charge,
            'slug'   => Str::slug($request->name),
        ]);

        return redirect()->route('admin.setup.charges.index')
                         ->with('success', 'Charge updated.');
    }

    public function destroy($id)
    {
        Charge::findOrFail($id)->delete();
        return redirect()->route('admin.setup.charges.index')
                         ->with('success', 'Charge deleted.');
    }
}