<?php

namespace App\DataTransferObjects;

readonly class RoomData
{
    public function __construct(
        public string $name,
        public int $room_type_id,
    ) {}

    /**
     * Create from request data
     *
     * @param  array<string, mixed>  $validated
     */
    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            room_type_id: $validated['room_type_id'],
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
            name: $data['name'],
            room_type_id: $data['room_type_id'],
        );
    }
}
