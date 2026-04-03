<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Schedule extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'schedules';

    protected $fillable = [
        'requester',
        'requester_email',
        'title',
        'date',
        'time',
        'duration',
        'status',
    ];
}