<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MainMenu;
use App\Models\SubMenu;
use App\Models\Product;
use App\Models\Warehouse;

class ProductListingController extends Controller
{
    // ── Index (My Listings page) ────────────────────────────────────

    public function index(Request $request)
{
    $query = ProductListing::where('user_id', Auth::id());

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('sku_code', 'like', "%{$search}%");
        });
    }

    $filter = $request->get('filter', 'all');

    // Apply filter tab
    match ($filter) {
        'active'   => $query->where('is_active', true),
        'inactive' => $query->where('is_active', false),
        'paid'     => $query->where('is_paid', true),
        'unpaid'   => $query->where('is_paid', false),
        default    => null,
    };

    $warehouseFilter = $request->get('warehouse_id');
    if ($warehouseFilter) {
        $query->where('warehouse_id', $warehouseFilter);
    }

    $listings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    // Count unpaid listings for the current user
    $unpaidCount = ProductListing::where('user_id', Auth::id())
                                 ->where('is_paid', false)
                                 ->count();

    // Load warehouses for the dropdown
    $warehouses = \App\Models\Warehouse::all();

    return view('admin.product_listing.index', compact(
        'listings',
        'unpaidCount',
        'warehouses',
        'warehouseFilter',
        'filter',
    ));
}

    // ── Create ──────────────────────────────────────────────────────

    public function create()
{
    $mainCategories = MainMenu::all();
    $subCategories  = SubMenu::all();
    $products       = Product::all();
    $warehouses     = Warehouse::all();

    $sellTypes     = ['sell by pieces', 'sell by containers', 'sell by weight'];
    $currencies    = ['AED', 'USD', 'GBP', 'EUR'];
    $discountTypes = ['No Promotion', 'fixed', 'percentage'];
    $incoterms     = [
        'EXW' => 'EXW - Ex Works',
        'FCA' => 'FCA - Free Carrier',
        'FOB' => 'FOB - Free On Board',
        'CFR' => 'CFR - Cost and Freight',
        'CIF' => 'CIF - Cost Insurance Freight',
        'DAP' => 'DAP - Delivered At Place',
        'DDP' => 'DDP - Delivered Duty Paid',
    ];

    return view('admin.product_listing.create', compact(
        'mainCategories', 'subCategories', 'products', 'warehouses',
        'sellTypes', 'currencies', 'discountTypes', 'incoterms',
    ));
}

    // ── Store ───────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'           => 'required|string',
            'main_category_id'     => 'required|string',
            'sub_category_id'      => 'required|string',
            'warehouse_id'         => 'required|string',
            'sell_type'            => 'required|string',
            'currency_id'          => 'required|string',
            'discount_type'        => 'nullable|string',
            'incoterm'             => 'nullable|string',
            'total_quantity'       => 'required|integer|min:1',
            'lead_time'            => 'required|integer|min:0',
            'is_active'            => 'boolean',
            'slots'                => 'required|array|min:1',
            'slots.*.min_quantity' => 'required|integer|min:0',
            'slots.*.max_quantity' => 'required|integer|min:1',
            'slots.*.price'        => 'required|numeric|min:0',
        ]);

        $validated['user_id']             = Auth::id();
        $validated['created_by']          = Auth::id();
        $validated['verification_status'] = 'pending';
        $validated['is_active']           = $request->boolean('is_active', true);
        $validated['is_paid']             = false;
        $validated['sku_code']            = 'PV-' . rand(1000000000, 9999999999) . '-' . rand(1000, 9999);

        ProductListing::create($validated);

        return redirect()->route('product_listing.index')
                         ->with('success', 'Your listing has been created and is pending approval.');
    }

    // ── Show ────────────────────────────────────────────────────────

    public function show(string $id)
    {
        $listing = ProductListing::findOrFail($id);
        return view('admin.product_listing.show', compact('listing'));
    }

    // ── Edit ────────────────────────────────────────────────────────

    public function edit(string $id)
    {
        $listing = ProductListing::findOrFail($id);
        return view('admin.product_listing.edit', compact('listing'));
    }

    // ── Update ──────────────────────────────────────────────────────

    public function update(Request $request, string $id)
    {
        $listing = ProductListing::findOrFail($id);

        $validated = $request->validate([
            'sell_type'            => 'required|string',
            'currency_id'          => 'required|string',
            'discount_type'        => 'nullable|string',
            'incoterm'             => 'nullable|string',
            'total_quantity'       => 'required|integer|min:1',
            'lead_time'            => 'required|integer|min:0',
            'is_active'            => 'boolean',
            'slots'                => 'required|array|min:1',
            'slots.*.min_quantity' => 'required|integer|min:0',
            'slots.*.max_quantity' => 'required|integer|min:1',
            'slots.*.price'        => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $listing->update($validated);

        return redirect()->route('product_listing.index')
                         ->with('success', 'Listing updated successfully. Changes are reflected immediately.');
    }

    // ── Destroy ─────────────────────────────────────────────────────

    public function destroy(string $id)
    {
        $listing = ProductListing::findOrFail($id);
        $listing->delete();

        return redirect()->route('product_listing.index')
                         ->with('success', 'Listing deleted successfully.');
    }

    // ── Toggle Hold / Active ────────────────────────────────────────

    public function toggleActive(string $id)
    {
        $listing = ProductListing::findOrFail($id);
        $listing->update(['is_active' => !$listing->is_active]);

        $msg = $listing->is_active ? 'Listing is now active.' : 'Listing is now on hold.';
        return back()->with('success', $msg);
    }
}