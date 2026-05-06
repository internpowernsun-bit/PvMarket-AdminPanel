<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductListing extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_listing';

    protected $fillable = [
        'product_id',
        'total_quantity',
        'is_active',
        'user_id',
        'sell_type',
        'currency_id',
        'slots',
        'verification_status',
        'main_category_id',
        'sku_code',
        'discount_type',
        'sub_category_id',
        'warehouse_id',
        'lead_time',
        'is_paid',
        'created_by',
        'incoterm',
    ];

    protected $casts = [
        'slots'          => 'array',
        'is_active'      => 'boolean',
        'is_paid'        => 'boolean',
        'lead_time'      => 'integer',
        'total_quantity' => 'integer',
    ];

    // ── Relationships ───────────────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'warehouse_id');
    }

    // ── Scopes ──────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByVerification($query, string $status)
    {
        return $query->where('verification_status', $status);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    // ── Helpers ─────────────────────────────────────────────────────

    /** Number of price tiers (slots) */
    public function getTierCountAttribute(): int
    {
        return count($this->slots ?? []);
    }

    /** Human-readable status label */
    public function getVerificationLabelAttribute(): string
    {
        return match ($this->verification_status) {
            'approved' => 'Approved',
            'pending'  => 'Pending approval',
            'rejected' => 'Rejected',
            default    => ucfirst($this->verification_status),
        };
    }
}