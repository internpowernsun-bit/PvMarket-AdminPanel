<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'unique_id',               // e.g. "Order00010"
        'offer_id',                // reference to offers
        'product_id',              // reference to products (MongoDB ObjectId as string)
        'total_qty',               // integer
        'each_qty_price',          // string e.g. "3873"
        'purchased_currency',      // e.g. "USD"
        'selled_currency',         // e.g. "USD"
        'payment_currency',        // e.g. "USD"
        'payment_currency_total',  // total in payment currency
        'payment_currency_advance',
        'transaction_json',        // JSON blob of transaction details
        'sell_each_qty_price',
        'rate',
        'address_id',
        'company_id',              // seller company id
        'buyer_company_id',        // buyer company id
        'buyer_id',                // buyer user id
        'seller_id',               // seller user id
        'payment_platform',        // 1=default
        'partial_payment_amount',  // string e.g. "774.6"
        'payment_status',          // 0=unpaid, 1=paid
        'payment_verified',        // 0=not verified, 1=verified
        'payment_method',          // 1=offline, 2=online, 3=stripe etc.
        'transaction_upload',      // proof file path
        'payment_id',              // stripe/gateway payment id
        'price_type',              // 1=fixed, 2=bid
        'bid_id',
        'delivery_charge',
        'order_status',            // 0=pending, 1=confirmed, 2=shipped, 3=delivered, 4=cancelled
        'delivery_type',           // 1=standard, 2=express
        'sell_type',               // 1=normal, 2=offer
        'is_active',               // 1=active, 0=deleted
        'created_by',              // admin/user id who created
        'updated_by',
    ];

    protected $casts = [
        'total_qty'        => 'integer',
        'payment_platform' => 'integer',
        'payment_status'   => 'integer',
        'payment_verified' => 'integer',
        'payment_method'   => 'integer',
        'price_type'       => 'integer',
        'order_status'     => 'integer',
        'delivery_type'    => 'integer',
        'sell_type'        => 'integer',
        'is_active'        => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // ── Accessors ─────────────────────────────────────────────────
    public function getPaymentMethodLabelAttribute(): string
    {
        return match((int)$this->payment_method) {
            1 => 'Offline Transaction',
            2 => 'Online',
            3 => 'Stripe',
            4 => 'Bank Transfer',
            default => 'Unknown',
        };
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return match((int)$this->order_status) {
            0 => 'Pending under payment verification',
            1 => 'Confirmed',
            2 => 'Shipped',
            3 => 'Delivered',
            4 => 'Cancelled',
            default => 'Unknown',
        };
    }

    public function getOrderStatusColorAttribute(): string
    {
        return match((int)$this->order_status) {
            0 => 'orange',
            1 => 'blue',
            2 => 'purple',
            3 => 'green',
            4 => 'red',
            default => 'gray',
        };
    }

    public function getPaymentVerifiedLabelAttribute(): string
    {
        return $this->payment_verified ? 'Verified' : 'Not Verified';
    }

    public function getTotalCostAttribute(): string
    {
        $total    = $this->payment_currency_total ?? ($this->each_qty_price * $this->total_qty);
        $currency = $this->payment_currency ?? $this->purchased_currency ?? 'USD';
        return $total . '(' . $currency . ')';
    }
}