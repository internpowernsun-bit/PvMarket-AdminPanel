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
        $units     = Unit::orderBy('unit_name')->get();
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

        return compact('brands', 'units', 'mainMenus', 'subMenus', 'options');
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
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'alt_tag'       => 'nullable|string|max:255',
            'one_pallet'    => 'nullable|string|max:100',
            'one_container' => 'nullable|string|max:100',
            'description'   => 'nullable|string',
        ]);

        $data = [
            'product_name'        => $request->product_name,
            'main_menu_id'        => $request->main_menu_id,
            'sub_menu_id'         => $request->sub_menu_id,
            'brand_id'            => $request->brand_id,
            'alt_tag'             => $request->alt_tag,
            'one_pallet'          => $request->one_pallet,
            'one_container'       => $request->one_container,
            'description'         => $request->description,
            'is_popular'          => $request->boolean('is_popular'),
            'real_time_price'     => $request->boolean('real_time_price'),
            'height'              => $request->height,
            'width'               => $request->width,
            'depth'               => $request->depth,
            'weight'              => $request->weight,
            'length'              => $request->length,
            'verification_status' => 'pending',
            'updated_by'          => Auth::user()->name,
        ];

        // Resolve names for display
        $mainMenu = MainMenu::find($request->main_menu_id);
        $subMenu  = SubMenu::find($request->sub_menu_id);
        $data['main_menu_name'] = $mainMenu?->name;
        $data['sub_menu_name']  = $subMenu?->name;

        if ($request->brand_id) {
            $brand = Brand::find($request->brand_id);
            $data['brand_name'] = $brand?->name;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('datasheets')) {
            $paths = [];
            foreach ($request->file('datasheets') as $file) {
                $paths[] = $file->store('products/datasheets', 'public');
            }
            $data['datasheets'] = $paths;
        }

        if ($request->has('product_details')) {
            $details = [];
            foreach ($request->product_details as $row) {
                if (!empty($row['option_id'])) {
                    $unit = isset($row['unit_id']) ? Unit::find($row['unit_id']) : null;
                    $details[] = [
                        'option_id'      => $row['option_id'],
                        'option_name'    => $row['option_name'] ?? '',
                        'unit_id'        => $row['unit_id'] ?? null,
                        'unit_name'      => $unit?->unit_name,
                        'value'          => $row['value'] ?? '',
                        'show_in_badge'  => !empty($row['show_in_badge']),
                    ];
                }
            }
            $data['product_details'] = $details;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    // ── Edit ──────────────────────────────────────────
    public function edit($id)
    {
        $record = Product::findOrFail($id);

        // Load options filtered by the product's existing sub_menu_id
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
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [
            'product_name'    => $request->product_name,
            'main_menu_id'    => $request->main_menu_id,
            'sub_menu_id'     => $request->sub_menu_id,
            'brand_id'        => $request->brand_id,
            'alt_tag'         => $request->alt_tag,
            'one_pallet'      => $request->one_pallet,
            'one_container'   => $request->one_container,
            'description'     => $request->description,
            'is_popular'      => $request->boolean('is_popular'),
            'real_time_price' => $request->boolean('real_time_price'),
            'height'          => $request->height,
            'width'           => $request->width,
            'depth'           => $request->depth,
            'weight'          => $request->weight,
            'length'          => $request->length,
            'updated_by'      => Auth::user()->name,
        ];

        $mainMenu = MainMenu::find($request->main_menu_id);
        $subMenu  = SubMenu::find($request->sub_menu_id);
        $data['main_menu_name'] = $mainMenu?->name;
        $data['sub_menu_name']  = $subMenu?->name;

        if ($request->brand_id) {
            $brand = Brand::find($request->brand_id);
            $data['brand_name'] = $brand?->name;
        }

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->has('product_details')) {
            $details = [];
            foreach ($request->product_details as $row) {
                if (!empty($row['option_id'])) {
                    $unit = isset($row['unit_id']) ? Unit::find($row['unit_id']) : null;
                    $details[] = [
                        'option_id'     => $row['option_id'],
                        'option_name'   => $row['option_name'] ?? '',
                        'unit_id'       => $row['unit_id'] ?? null,
                        'unit_name'     => $unit?->unit_name,
                        'value'         => $row['value'] ?? '',
                        'show_in_badge' => !empty($row['show_in_badge']),
                    ];
                }
            }
            $data['product_details'] = $details;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated.');
    }

    // ── AJAX: Get options by sub_menu_id ──────────────
    public function getOptionsBySubMenu(Request $request)
    {
        $subMenuId = $request->input('sub_menu_id');

        $options = ProductDetailOption::where('sub_menu_id', $subMenuId)
                                      ->orderBy('option_name')
                                      ->get(['_id', 'option_name', 'unit_ids', 'unit_id']);

        $units = Unit::orderBy('unit_name')->get(['_id', 'unit_name']);

        return response()->json([
            'options' => $options,
            'units'   => $units,
        ]);
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