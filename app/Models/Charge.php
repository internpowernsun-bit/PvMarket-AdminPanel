<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class Charge extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'charges';

    protected $fillable = [
        'name',
        'charge',
        'slug',
    ];
}