<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SubMenu extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sub_menus';

    protected $fillable = [
        'name',
        'main_menu_id',
        'main_menu_name',
        'pallet_applicable',
        'container_applicable',
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