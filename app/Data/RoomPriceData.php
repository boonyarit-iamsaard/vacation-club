<?php

namespace App\Data;

use App\Enums\PriceType;
use App\Models\RoomType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\RequiredUnless;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\FieldReference;

class RoomPriceData extends Data
{
    public function __construct(
        #[Exists(RoomType::class, 'id')]
        public int $room_type_id,
        public int $weekday,
        public int $weekend,
        public PriceType $type,
        #[AfterOrEqual('tomorrow')]
        public CarbonImmutable $effective_from,
        // TODO: research more on why "require if" is not working
        // #[RequiredIf(new FieldReference('type'), PriceType::PROMOTION)]
        #[RequiredUnless(new FieldReference('type'), PriceType::STANDARD)]
        #[After(new FieldReference('effective_from'))]
        public ?CarbonImmutable $effective_to,
        public ?string $promotion_name,
    ) {}
}
