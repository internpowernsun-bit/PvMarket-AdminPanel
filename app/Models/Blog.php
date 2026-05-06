<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;   

class Blog extends Model
{
    use HasTranslations;  
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

    public array $translatable = ['heading', 'description'];

    
}