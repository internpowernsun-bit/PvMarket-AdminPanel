<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PricePromotion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'price_promotions';

    protected $fillable = [
        'heading',
        'event_place',
        'event_date',
        'description',
        'image',
    ];
}