<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PvSpotPrice extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pv_spot_prices';

    protected $fillable = [
        'heading',
        'upload_date',
        'items',   // array of {item, high, low, average, change, ordering}
    ];

    protected $casts = [
        'items' => 'array',
    ];
}