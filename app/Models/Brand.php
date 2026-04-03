<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'brands';

    protected $fillable = [
        'name',
        'image',
        'alt_tag',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}