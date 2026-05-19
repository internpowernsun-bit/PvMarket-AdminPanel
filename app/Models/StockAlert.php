<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Casts\AsObjectId;

class StockAlert extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'stock_alerts';

    protected $fillable = [
        'listing_id',
        'user_id',
        'threshold',
        'notification_enabled',
        'is_active',
    ];

    protected $casts = [
        'listing_id'           => AsObjectId::class,
        'user_id'              => AsObjectId::class,
        'threshold'            => 'integer',
        'notification_enabled' => 'boolean',
        'is_active'            => 'boolean',
    ];

    /**
     * Fetch the active alert for a given listing + user, or null if none exists.
     */
    public static function getAlert(string $listingId, string $userId): ?static
    {
        return static::where('listing_id', new \MongoDB\BSON\ObjectId($listingId))
                     ->where('user_id',    new \MongoDB\BSON\ObjectId($userId))
                     ->first();
    }
}