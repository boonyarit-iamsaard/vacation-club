<?php

namespace App\Models;

use App\Services\RoomService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperRoom
 */
class Room extends Model
{
    protected $fillable = [
        'name',
        'room_type_id',
        'room_type_name',
        'room_type_code',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($room) {
            app(RoomService::class)->maintainRoomTypeIntegrity($room);
        });

        static::updating(function ($room) {
            if ($room->isDirty('room_type_id')) {
                app(RoomService::class)->maintainRoomTypeIntegrity($room);
            }
        });
    }

    /**
     * @return BelongsTo<RoomType, covariant Room>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
