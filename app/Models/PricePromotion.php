<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class PricePromotion extends Model
{
    use HasTranslations;
    protected $connection = 'mongodb';
    protected $collection = 'price_promotions';

    protected $fillable = [
        'heading',
        'event_place',
        'event_date',
        'description',
        'image',
    ];

    public array $translatable = ['heading', 'description', 'event_place'];
}