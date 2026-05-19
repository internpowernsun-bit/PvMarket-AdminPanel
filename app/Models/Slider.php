<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class Slider extends Model
{
    use HasTranslations;

    protected $connection = 'mongodb';
    protected $collection = 'sliders';

    protected $fillable = [
        'name',
        'image',
        'alt_tag',
        'redirect_link',
        'slider_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public array $translatable = ['name', 'alt_tag'];
}