<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class News extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'news';

    protected $fillable = [
        'heading',
        'slug',
        'content',
        'image',
        'alt_tag',
    ];
}