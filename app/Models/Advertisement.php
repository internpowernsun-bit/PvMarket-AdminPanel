<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class Advertisement extends Model {
    use HasTranslations;
    protected $connection = 'mongodb';
    protected $collection = 'advertisements';
    protected $fillable   = ['title', 'alt_tag', 'image', 'redirect_link', 'is_active',];
    protected $casts      = ['is_active' => 'boolean'];

    public array $translatable = ['title', 'alt_tag'];
}