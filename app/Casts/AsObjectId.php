<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MongoDB\BSON\ObjectId;

class AsObjectId implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (!$value) return null;
        if ($value instanceof ObjectId) return $value;
        return new ObjectId((string)$value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value) return null;
        if ($value instanceof ObjectId) return $value;
        return new ObjectId((string)$value);
    }
}