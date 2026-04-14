<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class PageSetting extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'page_settings';

    protected $fillable = [
        'page',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_image',
        'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];
}