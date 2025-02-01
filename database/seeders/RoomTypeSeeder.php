<?php

namespace Database\Seeders;

use App\DataTransferObjects\RoomData;
use App\DataTransferObjects\RoomPriceData;
use App\Enums\PriceType;
use App\Models\RoomType;
use App\Services\RoomPriceService;
use App\Services\RoomService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomTypeSeeder extends Seeder
{
    public function __construct(
        private readonly RoomPriceService $roomPriceService,
        private readonly RoomService $roomService,
    ) {}

    public function run(): void
    {
        $this->command->info('Start seeding room types...');

        $roomTypes = json_decode(Storage::get('json/room-types.json'), true);

        if ($roomTypes === null) {
            $this->command->warn('Room types seed data not found, skipping...');

            return;
        }

        $this->command->info('Seeding room types...');

        foreach ($roomTypes as $roomType) {
            $createdRoomType = RoomType::create([
                'name' => $roomType['name'],
                'code' => $roomType['code'],
                'description' => $roomType['description'],
            ]);

            /**
             * This is the previous standard price for the room type
             *
             * TODO: add a condition to run only in development environment
             */
            $this->roomPriceService->create(new RoomPriceData(
                room_type_id: $createdRoomType->id,
                weekday: (int) $roomType['price']['weekday'],
                weekend: (int) $roomType['price']['weekend'],
                type: PriceType::STANDARD,
                effective_from: now()->subMonth(),
            ));

            $this->roomPriceService->create(new RoomPriceData(
                room_type_id: $createdRoomType->id,
                weekday: (int) $roomType['price']['weekday'],
                weekend: (int) $roomType['price']['weekend'],
                type: PriceType::STANDARD,
                effective_from: now(),
            ));

            /**
             * This is the expired promotion price for the room type
             *
             * TODO: add a condition to run only in development environment
             */
            $this->roomPriceService->create(new RoomPriceData(
                room_type_id: $createdRoomType->id,
                weekday: (int) ($roomType['price']['weekday'] * 0.9),
                weekend: (int) ($roomType['price']['weekend'] * 0.9),
                type: PriceType::PROMOTION,
                effective_from: now()->subMonth()->subWeeks(2),
                effective_to: now()->subMonth(),
                promotion_name: 'Promotion 1',
            ));

            /**
             * This is the future promotion price for the room type
             *
             * TODO: add a condition to run only in development environment
             */
            $this->roomPriceService->create(new RoomPriceData(
                room_type_id: $createdRoomType->id,
                weekday: (int) ($roomType['price']['weekday'] * 0.9),
                weekend: (int) ($roomType['price']['weekend'] * 0.9),
                type: PriceType::PROMOTION,
                effective_from: now()->addMonth(),
                effective_to: now()->addMonth()->addWeeks(2),
                promotion_name: 'Promotion 2',
            ));

            for ($i = 1; $i <= $roomType['quantity']; $i++) {
                $this->roomService->create(new RoomData(
                    name: "$createdRoomType->code$i",
                    room_type_id: $createdRoomType->id,
                ));
            }
        }

        $this->command->info('Room types and rooms seeded.');
    }
}
