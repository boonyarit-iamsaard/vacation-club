<?php

namespace App\DataTransferObjects;

use App\Enums\PriceType;
use Carbon\Carbon;

readonly class RoomPriceData
{
    public function __construct(
        public int $room_type_id,
        public int $weekday,
        public int $weekend,
        public PriceType $type,
        public Carbon $effective_from,
        public ?Carbon $effective_to = null,
        public ?string $promotion_name = null,
    ) {}

    /**
     * Create from request data
     *
     * @param  array<string, mixed>  $validated
     */
    public static function fromRequest(array $validated): self
    {
        return new self(
            room_type_id: $validated['room_type_id'],
            weekday: $validated['weekday'],
            weekend: $validated['weekend'],
            type: PriceType::from($validated['type']),
            effective_from: Carbon::parse($validated['effective_from']),
            effective_to: isset($validated['effective_to']) ? Carbon::parse($validated['effective_to']) : null,
            promotion_name: $validated['promotion_name'] ?? null,
        );
    }

    /**
     * Create from array (useful for seeding)
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            room_type_id: $data['room_type_id'],
            weekday: $data['weekday'],
            weekend: $data['weekend'],
            type: $data['type'],
            effective_from: $data['effective_from'],
            effective_to: $data['effective_to'] ?? null,
            promotion_name: $data['promotion_name'] ?? null,
        );
    }
}
