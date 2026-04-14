<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
    'product_name',
    'datasheets',
    'brand_id',
    'brand_name',
    'one_pallet',
    'one_container',
    'description',
    'is_popular',
    'real_time_price',
    'product_details',      // array of {label, value, unit}
    'measurement_details',  // object {height, height_unit, width, width_unit, …}
    'verification_status',
    'updated_by',
    'main_menu_id',
    'main_menu_name',
    'sub_menu_id',
    'sub_menu_name',
];

protected $casts = [
    'is_popular'          => 'boolean',
    'real_time_price'     => 'boolean',
    'product_details'     => 'array',
    'datasheets'          => 'array',
    'measurement_details' => 'array',   // ← added
];
}