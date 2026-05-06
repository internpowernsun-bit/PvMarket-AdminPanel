<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Traits\HasTranslations;

class PvSpotPrice extends Model
{
    use HasTranslations;
    protected $connection = 'mongodb';
    protected $collection = 'pv_spot_prices';

    protected $fillable = [
        'heading',
        'upload_date',
        'items',   // array of {item, high, low, average, change, ordering}
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public array $translatable = ['heading'];
}