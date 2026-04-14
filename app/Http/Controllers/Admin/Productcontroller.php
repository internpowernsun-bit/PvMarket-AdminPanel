<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductDetailOption;
use App\Models\MainMenu;
use App\Models\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ── Shared dropdown data ──────────────────────────
    private function getDropdowns(?string $subMenuId = null): array
    {
        $brands    = Brand::where('is_active', true)->orderBy('name')->get();
        $mainMenus = MainMenu::orderBy('name')->get();
        $subMenus  = SubMenu::orderBy('name')->get();

        // Filter options by selected sub_menu_id if provided
        if ($subMenuId) {
            $options = ProductDetailOption::where('sub_menu_id', $subMenuId)
                                          ->orderBy('option_name')
                                          ->get();
        } else {
            $options = collect(); // empty until sub menu selected
        }

        return compact('brands', 'mainMenus', 'subMenus', 'options');
    }

    // ── Index ─────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('product_name', 'like', "%{$s}%")
                  ->orWhere('brand_name',  'like', "%{$s}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')
                          ->paginate($request->get('entries', 10));

        return view('admin.products.products', [
            'mode'     => 'index',
            'products' => $products,
        ]);
    }

    // ── Create ────────────────────────────────────────
    public function create()
    {
        return view('admin.products.products', array_merge(
            ['mode' => 'create', 'record' => null],
            $this->getDropdowns()
        ));
    }

    // ── Store ─────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'product_name'  => 'required|string|max:500',
            'main_menu_id'  => 'required|string',
            'sub_menu_id'   => 'required|string',
            'brand_id'      => 'nullable|string',           
            'one_pallet'    => 'nullable|string|max:100',
            'one_container' => 'nullable|string|max:100',
            'description'   => 'nullable|string',
        ]);

        $data = [
            'product_name'        => $request->product_name,
            'main_menu_id'        => $request->main_menu_id,
            'sub_menu_id'         => $request->sub_menu_id,
            'brand_id'            => $request->brand_id,
            'one_pallet'          => $request->one_pallet,
            'one_container'       => $request->one_container,
            'description'         => $request->description,
            'is_popular'          => $request->boolean('is_popular'),
            'real_time_price'     => $request->boolean('real_time_price'),
            'verification_status' => 'pending',
            'updated_by'          => Auth::user()->name,
        ];

        // Resolve display names
        $mainMenu = MainMenu::find($request->main_menu_id);
        $subMenu  = SubMenu::find($request->sub_menu_id);
        $data['main_menu_name'] = $mainMenu?->name;
        $data['sub_menu_name']  = $subMenu?->name;

        if ($request->brand_id) {
            $brand = Brand::find($request->brand_id);
            $data['brand_name'] = $brand?->name;
        }

        

        // ── Datasheets ───────────────────────────────
        if ($request->hasFile('datasheets')) {
            $paths = [];
            foreach ($request->file('datasheets') as $file) {
                $paths[] = $file->store('products/datasheets', 'public');
            }
            $data['datasheets'] = $paths;
        }

        // ── product_details: [{label, value, unit}] ──
        if ($request->has('product_details')) {
            $details = [];
            foreach ($request->product_details as $row) {
                if (!empty($row['label'])) {
                    $details[] = [
                        'label' => $row['label'],
                        'value' => $row['value'] ?? '',
                        'unit'  => $row['unit']  ?? null,
                    ];
                }
            }
            $data['product_details'] = $details;
        }

        // ── measurement_details: {height, height_unit, …} ──
        $data['measurement_details'] = [
            'height'      => $request->height      ? (float) $request->height      : null,
            'height_unit' => $request->height_unit ?? null,
            'width'       => $request->width       ? (float) $request->width       : null,
            'width_unit'  => $request->width_unit  ?? null,
            'depth'       => $request->depth       ? (float) $request->depth       : null,
            'depth_unit'  => $request->depth_unit  ?? null,
            'weight'      => $request->weight      ? (float) $request->weight      : null,
            'weight_unit' => $request->weight_unit ?? null,
            'length'      => $request->length      ? (float) $request->length      : null,
            'length_unit' => $request->length_unit ?? null,
        ];

        Product::create($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    // ── Edit ──────────────────────────────────────────
    public function edit($id)
    {
        $record = Product::findOrFail($id);

        return view('admin.products.products', array_merge(
            ['mode' => 'edit', 'record' => $record],
            $this->getDropdowns($record->sub_menu_id)
        ));
    }

    // ── Update ────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:500',
            'main_menu_id' => 'required|string',
            'sub_menu_id'  => 'required|string',
        ]);

        $data = [
            'product_name'    => $request->product_name,
            'main_menu_id'    => $request->main_menu_id,
            'sub_menu_id'     => $request->sub_menu_id,
            'brand_id'        => $request->brand_id,
            'one_pallet'      => $request->one_pallet,
            'one_container'   => $request->one_container,
            'description'     => $request->description,
            'is_popular'      => $request->boolean('is_popular'),
            'real_time_price' => $request->boolean('real_time_price'),
            'updated_by'      => Auth::user()->name,
        ];

        // Resolve display names
        $mainMenu = MainMenu::find($request->main_menu_id);
        $subMenu  = SubMenu::find($request->sub_menu_id);
        $data['main_menu_name'] = $mainMenu?->name;
        $data['sub_menu_name']  = $subMenu?->name;

        if ($request->brand_id) {
            $brand = Brand::find($request->brand_id);
            $data['brand_name'] = $brand?->name;
        }

        // ── Image ────────────────────────────────────
        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // ── product_details: [{label, value, unit}] ──
        if ($request->has('product_details')) {
            $details = [];
            foreach ($request->product_details as $row) {
                if (!empty($row['label'])) {
                    $details[] = [
                        'label' => $row['label'],
                        'value' => $row['value'] ?? '',
                        'unit'  => $row['unit']  ?? null,
                    ];
                }
            }
            $data['product_details'] = $details;
        }

        // ── measurement_details: {height, height_unit, …} ──
        $data['measurement_details'] = [
            'height'      => $request->height      ? (float) $request->height      : null,
            'height_unit' => $request->height_unit ?? null,
            'width'       => $request->width       ? (float) $request->width       : null,
            'width_unit'  => $request->width_unit  ?? null,
            'depth'       => $request->depth       ? (float) $request->depth       : null,
            'depth_unit'  => $request->depth_unit  ?? null,
            'weight'      => $request->weight      ? (float) $request->weight      : null,
            'weight_unit' => $request->weight_unit ?? null,
            'length'      => $request->length      ? (float) $request->length      : null,
            'length_unit' => $request->length_unit ?? null,
        ];

        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated.');
    }

    // ── AJAX: Get options by sub_menu_id ──────────────
    // Returns option names as label suggestions for the product_details rows.
    // unit_ids are no longer needed since unit is stored as a plain string.
    public function getOptionsBySubMenu(Request $request)
{
    $subMenuId = $request->input('sub_menu_id');

    $options = ProductDetailOption::where('sub_menu_id', $subMenuId)
                                  ->orderBy('option_name')
                                  ->get(['_id', 'option_name', 'unit_ids']);

    $options = $options->map(function ($option) {
        $unitIds = $option->unit_ids ?? [];

        $units = collect();
        if (!empty($unitIds)) {
            $units = Unit::whereIn('_id', $unitIds)
                         ->orderBy('unit_name')
                         ->get(['_id', 'unit_name'])
                         ->map(fn($u) => ['unit_name' => $u->unit_name]);
        }

        return [
            'option_name' => $option->option_name,
            'units'       => $units,
        ];
    });

    return response()->json(['options' => $options]);
}

    // ── AJAX: Get sub menus by main_menu_id ───────────
    public function getSubMenusByMainMenu(Request $request)
    {
        $mainMenuId = $request->input('main_menu_id');
        $subMenus   = SubMenu::where('main_menu_id', $mainMenuId)->orderBy('name')->get(['_id', 'name']);
        return response()->json(['subMenus' => $subMenus]);
    }

    // ── Verify ────────────────────────────────────────
    public function verify($id)
    {
        Product::findOrFail($id)->update([
            'verification_status' => 'verified',
            'updated_by'          => Auth::user()->name,
        ]);
        return redirect()->route('admin.products.index')->with('success', 'Product verified.');
    }

    // ── Reject ────────────────────────────────────────
    public function reject($id)
    {
        Product::findOrFail($id)->update([
            'verification_status' => 'rejected',
            'updated_by'          => Auth::user()->name,
        ]);
        return redirect()->route('admin.products.index')->with('success', 'Product rejected.');
    }

    // ── Destroy ───────────────────────────────────────
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}