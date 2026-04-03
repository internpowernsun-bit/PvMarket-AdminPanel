<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Slider extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sliders';

    protected $fillable = [
        'name',
        'image',
        'alt_tag',
        'redirect_link',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];
}