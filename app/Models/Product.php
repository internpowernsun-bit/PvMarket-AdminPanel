<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'product_name',
        'image',
        'alt_tag',
        'datasheets',
        'brand_id',
        'brand_name',
        'one_pallet',
        'one_container',
        'description',
        'is_popular',
        'specification_value',
        'real_time_price',
        'product_details',    // array of {option_id, option_name, unit_id, unit_name, value}
        'height',
        'width',
        'depth',
        'weight',
        'length',
        'verification_status', // pending | verified | rejected
        'updated_by',
        'main_menu_id',
    ];

    protected $casts = [
        'is_popular'       => 'boolean',
        'real_time_price'  => 'boolean',
        'product_details'  => 'array',
        'datasheets'       => 'array',
    ];
}