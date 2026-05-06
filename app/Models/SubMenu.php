<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class SubMenu extends Model
{
    use HasTranslations;

    protected $connection = 'mongodb';
    protected $collection = 'sub_categories';


    protected $fillable = [
        'sub_category_name',
        'category_id',
        'slug',
        'sub_category_icon_image',
        'icon_alt_tag',
        'category_name',
        'is_hold',
        'stock_value',
        'pallet_applicable',     
        'container_applicable',   
        'created_by',
    ];

    protected $casts = [
        'is_hold'     => 'boolean',
        'stock_value' => 'boolean',
        'pallet_applicable'    => 'boolean',   
        'container_applicable' => 'boolean', 
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function getTable(): string
    {
        return $this->collection ?? parent::getTable();
    }

    public array $translatable = [
    'sub_category_name',
    'short_description',
    'meta_title',
    'meta_description',
    'content',
];
}