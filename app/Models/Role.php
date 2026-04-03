<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'roles';

    protected $fillable = [
        'role',
        'slug',
        'guard_name',
    ];
}