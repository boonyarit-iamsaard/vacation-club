<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperRoomType
 */
class RoomType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected $casts = [
        'room_price_id' => 'integer',
    ];

    /**
     * @return HasMany<RoomPrice, covariant RoomType>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(RoomPrice::class);
    }

    /**
     * @return HasMany<Room, covariant RoomType>
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
