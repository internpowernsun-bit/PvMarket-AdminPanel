<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BidRequest extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bid_requests';

    protected $fillable = [
        'unique_id',             // e.g. "Req00035"
        'selected_pcs_qty',      // integer — quantity selected
        'quantity_unit',         // e.g. "Pallet", "Container", "Piece"
        'sell_type',             // 1=normal, 2=offer
        'purchased_currency',    // e.g. "USD"
        'bid_price_per_piece',   // string e.g. "2063.45"
        'offer_id',
        'order_id',
        'final_price_per_pcs',   // string — after negotiation
        'country_id',
        'request_type',          // 'bid request' | 'fair request'
        'lead_time',             // integer — weeks
        'product_id',            // MongoDB ObjectId string
        'product_name',          // denormalized
        'company_id',            // MongoDB ObjectId string
        'company_name',          // denormalized
        'created_by',
        'updated_by',
        'status',                // 0=Pending, 1=Accepted, 2=Rejected, 3=Completed
        'is_active',             // 1=active, 0=deleted
        'completed_at',          // timestamp when completed
    ];

    protected $casts = [
        'selected_pcs_qty' => 'integer',
        'sell_type'        => 'integer',
        'lead_time'        => 'integer',
        'status'           => 'integer',
        'is_active'        => 'integer',
    ];

    // ── Accessors ─────────────────────────────────────────────────
    public function getRequestTypeLabelAttribute(): string
    {
        return match($this->request_type) {
            'fair request' => 'Fair Request',
            'bid request'  => 'Bid Request',
            default        => 'Bid Request',
        };
    }

    public function getRequestTypeBadgeClassAttribute(): string
    {
        return match($this->request_type) {
            'fair request' => 'badge-fair',
            'bid request'  => 'badge-bid',
            default        => 'badge-bid',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match((int)$this->status) {
            0 => 'Pending',
            1 => 'Accepted',
            2 => 'Rejected',
            3 => 'Completed',
            default => 'Pending',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match((int)$this->status) {
            0 => 'status-pending',
            1 => 'status-accepted',
            2 => 'status-rejected',
            3 => 'status-completed',
            default => 'status-pending',
        };
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}