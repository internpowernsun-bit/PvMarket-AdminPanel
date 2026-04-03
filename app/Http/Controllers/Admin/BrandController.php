<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $brands = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('entries', 10));

        return view('admin.setup.brands.brands', [
            'mode'   => 'index',
            'brands' => $brands,
        ]);
    }

    public function create()
    {
        return view('admin.setup.brands.brands', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brands'           => 'required|array|min:1',
            'brands.*.name'    => 'required|string|max:255',
            'brands.*.alt_tag' => 'nullable|string|max:255',
            'brands.*.image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        foreach ($request->brands as $i => $data) {
            $row = [
                'name'      => $data['name'],
                'alt_tag'   => $data['alt_tag'] ?? null,
                'is_active' => true,
            ];
            if ($request->hasFile("brands.{$i}.image")) {
                $row['image'] = $request->file("brands.{$i}.image")->store('brands', 'public');
            }
            Brand::create($row);
        }

        return redirect()->route('admin.setup.brands.index')
                         ->with('success', count($request->brands) . ' brand(s) added.');
    }

    public function edit($id)
    {
        $record = Brand::findOrFail($id);
        return view('admin.setup.brands.brands', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'alt_tag' => 'nullable|string|max:255',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name'    => $request->name,
            'alt_tag' => $request->alt_tag,
        ];

        if ($request->hasFile('image')) {
            if ($brand->image) Storage::disk('public')->delete($brand->image);
            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('admin.setup.brands.index')
                         ->with('success', 'Brand updated.');
    }

    public function toggle($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update(['is_active' => !$brand->is_active]);
        return redirect()->route('admin.setup.brands.index');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->image) Storage::disk('public')->delete($brand->image);
        $brand->delete();
        return redirect()->route('admin.setup.brands.index')
                         ->with('success', 'Brand deleted.');
    }
}