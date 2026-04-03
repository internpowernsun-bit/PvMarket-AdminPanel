<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductDetailOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ── Shared dropdown data ──────────────────────────
    private function getDropdowns(): array
    {
        $brands  = Brand::where('is_active', true)->orderBy('name')->get();
        $units   = Unit::orderBy('unit_name')->get();
        $options = ProductDetailOption::orderBy('option_name')->get();

        return compact('brands', 'units', 'options');
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
            'brand_id'      => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'alt_tag'       => 'nullable|string|max:255',
            'one_pallet'    => 'nullable|string|max:100',
            'one_container' => 'nullable|string|max:100',
            'description'   => 'nullable|string',
        ]);

        $data = [
            'product_name'       => $request->product_name,
            'brand_id'           => $request->brand_id,
            'alt_tag'            => $request->alt_tag,
            'one_pallet'         => $request->one_pallet,
            'one_container'      => $request->one_container,
            'description'        => $request->description,
            'is_popular'         => $request->boolean('is_popular'),
            'specification_value'=> $request->specification_value,
            'real_time_price'    => $request->boolean('real_time_price'),
            'height'             => $request->height,
            'width'              => $request->width,
            'depth'              => $request->depth,
            'weight'             => $request->weight,
            'length'             => $request->length,
            'verification_status'=> 'pending',
            'updated_by'         => Auth::user()->name,
            'show_in_badge' => !empty($row['show_in_badge']),
        ];

        // Brand name for display
        if ($request->brand_id) {
            $brand = Brand::find($request->brand_id);
            $data['brand_name'] = $brand?->name;
        }

        // Product image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Datasheets (multiple files)
        if ($request->hasFile('datasheets')) {
            $paths = [];
            foreach ($request->file('datasheets') as $file) {
                $paths[] = $file->store('products/datasheets', 'public');
            }
            $data['datasheets'] = $paths;
        }

        // Product details rows
        if ($request->has('product_details')) {
            $details = [];
            foreach ($request->product_details as $row) {
                if (!empty($row['option_id'])) {
                    $unit = isset($row['unit_id']) ? Unit::find($row['unit_id']) : null;
                    $details[] = [
                        'option_id'   => $row['option_id'],
                        'option_name' => $row['option_name'] ?? '',
                        'unit_id'     => $row['unit_id'] ?? null,
                        'unit_name'   => $unit?->unit_name,
                        'value'       => $row['value'] ?? '',
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

        return view('admin.products.products', array_merge(
            ['mode' => 'edit', 'record' => $record],
            $this->getDropdowns()
        ));
    }

    // ── Update ────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:500',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [
            'product_name'        => $request->product_name,
            'brand_id'            => $request->brand_id,
            'alt_tag'             => $request->alt_tag,
            'one_pallet'          => $request->one_pallet,
            'one_container'       => $request->one_container,
            'description'         => $request->description,
            'is_popular'          => $request->boolean('is_popular'),
            'specification_value' => $request->specification_value,
            'real_time_price'     => $request->boolean('real_time_price'),
            'height'              => $request->height,
            'width'               => $request->width,
            'depth'               => $request->depth,
            'weight'              => $request->weight,
            'length'              => $request->length,
            'updated_by'          => Auth::user()->name,
            'show_in_badge' => !empty($row['show_in_badge']),
        ];

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
                        'option_id'   => $row['option_id'],
                        'option_name' => $row['option_name'] ?? '',
                        'unit_id'     => $row['unit_id'] ?? null,
                        'unit_name'   => $unit?->unit_name,
                        'value'       => $row['value'] ?? '',
                    ];
                }
            }
            $data['product_details'] = $details;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated.');
    }

    // ── Verify ────────────────────────────────────────
    public function verify($id)
    {
        Product::findOrFail($id)->update([
            'verification_status' => 'verified',
            'updated_by'          => Auth::user()->name,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product verified.');
    }

    // ── Reject ────────────────────────────────────────
    public function reject($id)
    {
        Product::findOrFail($id)->update([
            'verification_status' => 'rejected',
            'updated_by'          => Auth::user()->name,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product rejected.');
    }

    // ── Destroy ───────────────────────────────────────
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted.');
    }
}