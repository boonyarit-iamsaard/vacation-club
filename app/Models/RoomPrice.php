<?php

namespace App\Models;

use App\PriceType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

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
    ];

    protected $casts = [
        'weekday' => 'integer',
        'weekend' => 'integer',
        'type' => PriceType::class,
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    // boot
    protected static function boot(): void
    {
        parent::boot();

        // TODO: implement full validation
        // TODO: take effective time into account
        // TODO: set app level configuration for the date format
        // TODO: consider move business logic to a service layer

        static::creating(function ($roomPrice) {
            if ($roomPrice->type === PriceType::STANDARD) {
                $roomPrice->handleStandardPriceCreation();
            }

            if ($roomPrice->type === PriceType::PROMOTION) {
                $roomPrice->handlePromotionalPriceCreation();
            }
        });

        static::deleting(function ($roomPrice) {
            $roomPrice->handleDeletion();
        });
    }

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
    public function scopePromotional(Builder $query): Builder
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

    public function handleStandardPriceCreation(): void
    {
        /** @var RoomPrice|null $previousStandardPrice */
        $previousStandardPrice = $this->roomType->prices()->standard()->active()->first();

        if ($previousStandardPrice) {
            $previousStandardPrice->effective_to = $this->effective_from->subDay();
            $previousStandardPrice->save();
        }
    }

    public function handlePromotionalPriceCreation(): void
    {
        $previousPromotionalPrice = $this->roomType->prices()->promotional()->latest()->first();

        if ($previousPromotionalPrice && ! $this->effective_from->isAfter($previousPromotionalPrice->effective_from)) {
            throw new InvalidArgumentException('Promotional price must be effective after the previous promotional price');
        }
    }

    public function handleDeletion(): void
    {
        $remainingStandardPrices = $this->roomType->prices()
            ->standard()
            ->active()
            ->whereNot('id', $this->id)
            ->exists();

        if (! $remainingStandardPrices) {
            throw new InvalidArgumentException('Cannot delete the last active standard price for this room type');
        }
    }
}
