<?php

namespace App\Models;

use App\Enums\PriceType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperRoomPrice
 */
class RoomPrice extends Model
{
    protected $fillable = [
        'room_type_id',
        'weekday',
        'weekend',
        'type',
        'effective_from',
        'effective_to',
        'room_type_name',
        'room_type_code',
        'promotion_name',
    ];

    protected $casts = [
        'weekday' => 'integer',
        'weekend' => 'integer',
        'type' => PriceType::class,
        'effective_from' => 'immutable_datetime',
        'effective_to' => 'immutable_datetime',
    ];

    /**
     * @param  Builder<RoomPrice>  $query
     * @return Builder<RoomPrice>
     */
    public function scopeStandard(Builder $query): Builder
    {
        return $query->where('type', PriceType::STANDARD);
    }

    /**
     * @param  Builder<RoomPrice>  $query
     * @return Builder<RoomPrice>
     */
    public function scopePromotion(Builder $query): Builder
    {
        return $query->where('type', PriceType::PROMOTION);
    }

    /**
     * @param  Builder<RoomPrice>  $query
     * @return Builder<RoomPrice>
     */
    public function scopeActive(Builder $query, ?Carbon $date = null): Builder
    {
        $date ??= now();

        return $query
            ->where('effective_from', '<=', $date)
            ->where(function (Builder $query) use ($date) {
                $query
                    ->whereNull('effective_to')
                    ->orWhere('effective_to', '>', $date);
            });
    }

    /**
     * @param  Builder<RoomPrice>  $query
     * @return Builder<RoomPrice>
     */
    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('effective_from', '>', now());
    }

    /**
     * @return BelongsTo<RoomType, covariant RoomPrice>
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
