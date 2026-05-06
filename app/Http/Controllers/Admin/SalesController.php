<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('is_active', 1);

        // Filter by seller user if provided (for user sales tab)
        if ($request->filled('user_id')) {
            $query->where('seller_id', $request->user_id);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Search by order id
        if ($request->filled('search')) {
            $query->where('unique_id', 'like', '%' . $request->search . '%');
        }

        // Filter by order status
        if ($request->filled('order_status') && $request->order_status !== '') {
            $query->where('order_status', (int)$request->order_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', (int)$request->payment_method);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Load related products for dropdown filter
        $products = Product::orderBy('product_name')
                   ->get(['_id', 'product_name']);

        // Load user info if filtering by user
        $user = null;
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
        }

        // Attach product & company info to each order
        $productIds = $orders->pluck('product_id')->filter()->unique()->values();
        $products_map = Product::whereIn('_id', $productIds->toArray())
                               ->get()
                               ->keyBy(fn($p) => (string)$p->_id);

        $orders = $orders->map(function ($order) use ($products_map) {
            $order->product_info = $products_map[(string)$order->product_id] ?? null;
            return $order;
        });

        return view('admin.sales.index', compact('orders', 'products', 'user'));
    }

    // ── Mark partial payment as verified ─────────────────────────
    public function markPaymentVerified(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_verified' => 1]);

        return response()->json(['success' => true, 'message' => 'Payment marked as verified.']);
    }

    // ── Update order status ───────────────────────────────────────
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['order_status' => 'required|integer|min:0|max:4']);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => $request->order_status,
            'updated_by'   => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function viewProof($id)
{
    $order = Order::findOrFail($id);

    if (empty($order->transaction_upload)) {
        abort(404, 'No proof uploaded for this order.');
    }

    // Try storage path first
    $relativePath = $order->transaction_upload;
    $fullPath     = storage_path('app/public/' . $relativePath);

    if (file_exists($fullPath)) {
        $mimeType = mime_content_type($fullPath);
        return response()->file($fullPath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
        ]);
    }

    // Try public path
    $publicPath = public_path($relativePath);
    if (file_exists($publicPath)) {
        return response()->file($publicPath);
    }

    abort(404, 'Proof file not found on server.');
}
}