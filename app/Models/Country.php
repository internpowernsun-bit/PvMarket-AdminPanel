<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Country extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'countries';

    protected $fillable = [
        'name',
        'code',
        'iso2',
        'iso3',
        'capital',
        'currency',
        'flag',
        'alt_tag',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function getFlagUrlAttribute(): ?string
{
    if (!$this->flag) {
        return null;
    }

    // Build URL directly — no network call needed
    $baseUrl = rtrim(config('filesystems.disks.r2.url'), '/');

    return $baseUrl . '/' . $this->flag;
}
}