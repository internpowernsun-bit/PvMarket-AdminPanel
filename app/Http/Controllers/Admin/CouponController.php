<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }
        $coupons = $query->orderBy('created_at', 'desc')
                 ->paginate(request('per_page', 12)) 
                 ->appends(request()->query());
        return view('admin.setup.coupons.coupons', [
            'mode'    => 'index',
            'coupons' => $coupons,
        ]);
    }

    public function create()
    {
        return view('admin.setup.coupons.coupons', ['mode' => 'create']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code',
            'discount_type'    => 'required|in:percentage,fixed,months',
            'discount_value'   => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'status'           => 'required|in:active,inactive',
            'description'      => 'nullable|string|max:255',
        ]);

        Coupon::create([
            'code'             => strtoupper($request->code),
            'discount_type'    => $request->discount_type,
            'discount_value'   => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'usage_limit'      => $request->usage_limit,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'status'           => $request->status,
            'description'      => $request->description,
        ]);

        return redirect()->route('admin.setup.coupons.index')
                         ->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $record = Coupon::findOrFail($id);
        return view('admin.setup.coupons.coupons', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code,' . $id,
            'discount_type'    => 'required|in:percentage,fixed,months',
            'discount_value'   => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'status'           => 'required|in:active,inactive',
            'description'      => 'nullable|string|max:255',
        ]);

        $coupon->update([
            'code'             => strtoupper($request->code),
            'discount_type'    => $request->discount_type,
            'discount_value'   => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'usage_limit'      => $request->usage_limit,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'status'           => $request->status,
            'description'      => $request->description,
        ]);

        return redirect()->route('admin.setup.coupons.index')
                         ->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();
        return redirect()->route('admin.setup.coupons.index')
                         ->with('success', 'Coupon deleted.');
    }
}