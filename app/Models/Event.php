<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'heading',
        'place',
        'event_date',
        'description',
        'image',
        'alt_tag',
    ];
}