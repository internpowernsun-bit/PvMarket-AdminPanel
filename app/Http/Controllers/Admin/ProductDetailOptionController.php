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

        // Safely load each — won't crash if table/collection doesn't exist
        try {
            $mainMenus = \App\Models\MainMenu::orderBy('name')->get();
        } catch (\Exception $e) {}

        try {
            $subMenus = \App\Models\SubMenu::orderBy('name')->get();
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

        // MongoDB pagination
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
        'option_name'  => 'required|string|max:255',
        'data_type'    => 'required|in:integer,float,small_text,long_text',
        'main_menu_id' => 'nullable',
        'sub_menu_id'  => 'nullable',
        'unit_ids'     => 'nullable|array',  // ← changed
    ]);

    ProductDetailOption::create($request->only([
        'option_name', 'data_type', 'main_menu_id', 'sub_menu_id', 'unit_ids'  // ← changed
    ]));

    return redirect()->route('admin.products.detail-options.index')
                     ->with('success', 'Product detail option created successfully.');
}

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
        'option_name'  => 'required|string|max:255',
        'data_type'    => 'required|in:integer,float,small_text,long_text',
        'main_menu_id' => 'nullable',
        'sub_menu_id'  => 'nullable',
        'unit_ids'     => 'nullable|array',  // ← changed
    ]);

    $detailOption->update($request->only([
        'option_name', 'data_type', 'main_menu_id', 'sub_menu_id', 'unit_ids'  // ← changed
    ]));

    return redirect()->route('admin.products.detail-options.index')
                     ->with('success', 'Product detail option updated successfully.');
}

    public function destroy(ProductDetailOption $detailOption)
    {
        $detailOption->delete();

        return redirect()->route('admin.products.detail-options.index')
                         ->with('success', 'Product detail option deleted successfully.');
    }
}