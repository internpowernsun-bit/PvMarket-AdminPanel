<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Warehouse extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'warehouses';

    protected $fillable = [
        'name',
        'payment_status',  // 'paid' | 'pending'
        'is_active',       // boolean – the status toggle
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}