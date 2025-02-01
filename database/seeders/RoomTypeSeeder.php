<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\PriceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Start seeding room types...');

        $roomTypes = json_decode(Storage::get('json/room-types.json'), true);

        if ($roomTypes === null) {
            $this->command->warn('Room types seed data not found, skipping...');

            return;
        }

        $this->command->info('Seeding room types...');

        $dateFormat = 'Y-m-d';

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
            RoomPrice::create([
                'room_type_id' => $createdRoomType->id,
                'weekday' => $roomType['price']['weekday'],
                'weekend' => $roomType['price']['weekend'],
                'type' => PriceType::STANDARD,
                'effective_from' => now()->subMonth()->format($dateFormat),
                'room_type_name' => $createdRoomType->name,
                'room_type_code' => $createdRoomType->code,
            ]);

            RoomPrice::create([
                'room_type_id' => $createdRoomType->id,
                'weekday' => $roomType['price']['weekday'],
                'weekend' => $roomType['price']['weekend'],
                'type' => PriceType::STANDARD,
                'effective_from' => now()->format($dateFormat),
                'room_type_name' => $createdRoomType->name,
                'room_type_code' => $createdRoomType->code,
            ]);

            /**
             * This is the expired promotion price for the room type
             *
             * TODO: add a condition to run only in development environment
             */
            RoomPrice::create([
                'room_type_id' => $createdRoomType->id,
                'weekday' => $roomType['price']['weekday'] * 0.9,
                'weekend' => $roomType['price']['weekend'] * 0.9,
                'type' => PriceType::PROMOTION,
                'promotion_name' => 'Promotion 1',
                'effective_from' => now()->subMonth()->subWeeks(2)->format($dateFormat),
                'effective_to' => now()->subMonth()->format($dateFormat),
                'room_type_name' => $createdRoomType->name,
                'room_type_code' => $createdRoomType->code,
            ]);

            /**
             * This is the future promotion price for the room type
             *
             * TODO: add a condition to run only in development environment
             */
            RoomPrice::create([
                'room_type_id' => $createdRoomType->id,
                'weekday' => $roomType['price']['weekday'] * 0.9,
                'weekend' => $roomType['price']['weekend'] * 0.9,
                'type' => PriceType::PROMOTION,
                'promotion_name' => 'Promotion 2',
                'effective_from' => now()->addMonth()->format($dateFormat),
                'effective_to' => now()->addMonth()->addWeeks(2)->format($dateFormat),
                'room_type_name' => $createdRoomType->name,
                'room_type_code' => $createdRoomType->code,
            ]);

            for ($i = 1; $i <= $roomType['quantity']; $i++) {
                Room::create([
                    'name' => "{$createdRoomType->code}{$i}",
                    'room_type_id' => $createdRoomType->id,
                    'room_type_name' => $createdRoomType->name,
                    'room_type_code' => $createdRoomType->code,
                ]);
            }
        }

        $this->command->info('Room types and rooms seeded.');
    }
}
