<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductVisit extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_visits';

    protected $fillable = [
        'user_id',
        'product_id',
        'offer_id',
        'no_of_times',
        'visit_date',
        'is_active',
    ];

    protected $casts = [
        'no_of_times' => 'integer',
        'is_active'   => 'integer',
    ];
}