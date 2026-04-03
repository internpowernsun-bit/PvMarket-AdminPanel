<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductDetailOption extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_detail_options';

    protected $fillable = [
        'option_name',
        'data_type',
        'main_menu_id',
        'sub_menu_id',
        'unit_ids',
    ];

    protected $casts = [
    'unit_ids' => 'array',
];
}