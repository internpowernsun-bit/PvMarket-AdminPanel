<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Offer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'offers';

    protected $fillable = [
        'unique_id',        // e.g. "00T4Cx"  – auto-generated on create
        'product_id',
        'product_name',     // denormalised for display
        'warehouse_id',
        'warehouse_name',   // denormalised for display
        'payment_status',   // 'paid' | 'pending'
        'is_active',        // boolean – status toggle
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}