<?php

namespace App\Data;

use App\Models\RoomType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RoomTypeData extends Data
{
    public function __construct(
        public string $name,
        #[Max(3)]
        #[Unique(RoomType::class, 'code')]
        public string $code,
        public ?string $description = null
    ) {}
}
