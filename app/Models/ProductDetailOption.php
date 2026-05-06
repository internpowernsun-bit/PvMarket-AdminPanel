<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductDetailOption extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'specifications';

    public function getTable(): string  
    {
        return 'specifications';
    }

    protected $fillable = [
        'option_name',
        'data_type',
        'category_id',
        'category_name',
        'sub_category_id',
        'sub_category_name',
        'unit_ids',
        'unit_names',
    ];

    protected $casts = [
        'unit_ids'   => 'array',
        'unit_names' => 'array',
    ];
}