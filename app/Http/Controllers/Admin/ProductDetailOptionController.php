<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductDetailOption;
use Illuminate\Http\Request;

class ProductDetailOptionController extends Controller
{
    private function getDropdownData(): array
    {
        $mainMenus = [];
        $subMenus  = [];
        $units     = [];

        try {
            $mainMenus = \App\Models\MainMenu::orderBy('category_name')->get();
        } catch (\Exception $e) {}

        try {
            $subMenus = \App\Models\SubMenu::orderBy('sub_category_name')->get();
        } catch (\Exception $e) {}

        try {
            $units = \App\Models\Unit::orderBy('unit_name')->get();
        } catch (\Exception $e) {}

        return compact('mainMenus', 'subMenus', 'units');
    }

    public function index(Request $request)
    {
        $search  = $request->input('search');
        $entries = (int) $request->input('entries', 10);

        $query = ProductDetailOption::query();

        if ($search) {
            $query->where('option_name', 'like', "%{$search}%");
        }

        $options = $query->orderBy('_id', 'desc')->paginate($entries)->withQueryString();

        return view('admin.products.product-detail-options', [
            'mode'    => 'index',
            'options' => $options,
        ]);
    }

    public function create()
    {
        return view('admin.products.product-detail-options', array_merge(
            ['mode' => 'create', 'record' => null],
            $this->getDropdownData()
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'option_name'     => 'required|string|max:255',
            'data_type'       => 'required|in:integer,float,small_text,long_text',
            'category_id'     => 'nullable',
            'sub_category_id' => 'nullable',
            'unit_ids'        => 'nullable|array',
        ]);

        $category    = $request->category_id     ? \App\Models\MainMenu::find($request->category_id)     : null;
        $subCategory = $request->sub_category_id ? \App\Models\SubMenu::find($request->sub_category_id)  : null;

        // Resolve unit names — works whether unit_ids is empty or not
        $unitNames = [];
        if (!empty($request->unit_ids)) {
            $unitNames = \App\Models\Unit::whereIn('_id', $request->unit_ids)
                                          ->pluck('unit_name')
                                          ->toArray();
        } // ✅ if block closed here

        ProductDetailOption::create([
            'option_name'       => $request->option_name,
            'data_type'         => $request->data_type,
            'category_id'       => $request->category_id,
            'category_name'     => $category?->category_name,
            'sub_category_id'   => $request->sub_category_id,
            'sub_category_name' => $subCategory?->sub_category_name,
            'unit_ids'          => $request->unit_ids ?? [],
            'unit_names'        => $unitNames,
        ]);

        return redirect()->route('admin.products.detail-options.index')
                         ->with('success', 'Product specification created successfully.');
    } // ✅ store() closed here

    public function edit(ProductDetailOption $detailOption)
    {
        return view('admin.products.product-detail-options', array_merge(
            ['mode' => 'edit', 'record' => $detailOption],
            $this->getDropdownData()
        ));
    }

    public function update(Request $request, ProductDetailOption $detailOption)
    {
        $request->validate([
            'option_name'     => 'required|string|max:255',
            'data_type'       => 'required|in:integer,float,small_text,long_text',
            'category_id'     => 'nullable',
            'sub_category_id' => 'nullable',
            'unit_ids'        => 'nullable|array',
        ]);

        $category    = $request->category_id     ? \App\Models\MainMenu::find($request->category_id)     : null;
        $subCategory = $request->sub_category_id ? \App\Models\SubMenu::find($request->sub_category_id)  : null;

        $unitNames = [];
        if (!empty($request->unit_ids)) {
            $unitNames = \App\Models\Unit::whereIn('_id', $request->unit_ids)
                                          ->pluck('unit_name')
                                          ->toArray();
        } // ✅ if block closed here

        $detailOption->update([
            'option_name'       => $request->option_name,
            'data_type'         => $request->data_type,
            'category_id'       => $request->category_id,
            'category_name'     => $category?->category_name,
            'sub_category_id'   => $request->sub_category_id,
            'sub_category_name' => $subCategory?->sub_category_name,
            'unit_ids'          => $request->unit_ids ?? [],
            'unit_names'        => $unitNames,
        ]);

        return redirect()->route('admin.products.detail-options.index')
                         ->with('success', 'Product specification updated successfully.');
    } // ✅ update() closed here

    public function destroy(ProductDetailOption $detailOption)
    {
        $detailOption->delete();

        return redirect()->route('admin.products.detail-options.index')
                         ->with('success', 'Product specification deleted successfully.');
    }
}