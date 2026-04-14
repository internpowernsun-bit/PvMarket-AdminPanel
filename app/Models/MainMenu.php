<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MainMenu extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'main_menus';

    protected $fillable = [
        'name',
        'icon',
        'alt_tag',
        'slug',
        'meta_title',
        'meta_description',
        'meta_image',
        'short_description',
        'content',
        'is_active',
        'stock_value',
    ];
}