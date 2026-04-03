<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Unit extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'units';

    protected $fillable = [
        'unit_name',
        'unit_code',
        'description',
        'is_active',
    ];
}