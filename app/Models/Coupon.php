<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Coupon extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'coupons';

    protected $fillable = [
    'code',
    'discount_type',
    'discount_value',
    'min_order_amount',
    'usage_limit',
    'start_date',
    'end_date',
    'status',
    'description',
];

    protected $casts = [
        'is_active'       => 'boolean',
        'discount_months' => 'integer',
    ];
}