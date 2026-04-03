<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Advertisement extends Model {
    protected $connection = 'mongodb';
    protected $collection = 'advertisements';
    protected $fillable   = ['title', 'alt_tag', 'image', 'redirect_link', 'is_active'];
    protected $casts      = ['is_active' => 'boolean'];
}