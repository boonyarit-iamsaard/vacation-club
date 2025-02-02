<?php

namespace App\Data;

use App\Models\Room;
use App\Models\RoomType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RoomData extends Data
{
    public function __construct(
        #[Unique(Room::class, 'name')]
        public string $name,
        #[Exists(RoomType::class, 'id')]
        public int $room_type_id,
    ) {}
}
