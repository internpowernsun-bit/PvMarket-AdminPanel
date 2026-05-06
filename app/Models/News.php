<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class News extends Model
{
    use HasTranslations;
    protected $connection = 'mongodb';
    protected $collection = 'news';

    protected $fillable = [
        'heading',
        'slug',
        'content',
        'image',
        'alt_tag',
    ];

    public array $translatable = ['heading', 'content'];
}