<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Blog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'blogs';

    protected $fillable = [
    'heading',
    'image',
    'alt_tag',
    'slug',
    'related_blog_id',
    'related_blog_title',
    'description',  
    'blog_comments',
];
}