<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class Unit extends Model
{
    use HasTranslations;
    protected $connection = 'mongodb';
    protected $collection = 'units';

    protected $fillable = [
        'unit_name',
        'unit_code',
        'description',
        'is_active',
    ];

    public array $translatable = ['unit_name', 'description'];
}