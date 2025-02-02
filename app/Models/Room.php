<?php

namespace App\Models;

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

    /**
     * @return BelongsTo<RoomType, covariant Room>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
