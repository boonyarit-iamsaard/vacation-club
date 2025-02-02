<?php

namespace App\Services;

use App\Data\RoomPriceData;
use App\Enums\PriceType;
use App\Models\RoomPrice;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class RoomPriceService
{
    /**
     * @return Collection<int, RoomPrice>
     */
    public function list(): Collection
    {
        return RoomPrice::with('roomType')->get();
    }

    public function find(int $id): ?RoomPrice
    {
        return RoomPrice::with('roomType')->find($id);
    }

    public function create(RoomPriceData $roomPriceData): RoomPrice
    {
        $roomType = RoomType::findOrFail($roomPriceData->room_type_id);

        $roomPrice = new RoomPrice($roomPriceData->toArray());

        $this->syncRoomTypeData($roomPrice, $roomType);

        match ($roomPrice->type) {
            PriceType::STANDARD => $this->handleStandardPrice($roomPrice),
            PriceType::PROMOTION => $this->handlePromotionPrice($roomPrice),
        };

        $roomPrice->save();

        return $roomPrice->load('roomType');
    }

    public function update(RoomPrice $roomPrice, RoomPriceData $roomPriceData): RoomPrice
    {
        $roomType = RoomType::findOrFail($roomPriceData->room_type_id);

        $roomPrice = $roomPrice->fill($roomPriceData->toArray());

        $this->syncRoomTypeData($roomPrice, $roomType);

        match ($roomPrice->type) {
            PriceType::STANDARD => $this->handleStandardPrice($roomPrice),
            PriceType::PROMOTION => $this->handlePromotionPrice($roomPrice),
        };

        $roomPrice->save();

        return $roomPrice->load('roomType');
    }

    public function delete(RoomPrice $roomPrice): void
    {
        if ($roomPrice->type === PriceType::STANDARD) {
            $this->ensureNotLastActiveStandardPrice($roomPrice);
        }

        $roomPrice->delete();
    }

    /**
     * Prevent deletion of the last active standard price for a room type
     * This ensures that each room type always has at least one standard price
     */
    private function ensureNotLastActiveStandardPrice(RoomPrice $roomPrice): void
    {
        $remainingStandardPrices = RoomPrice::query()
            ->where('room_type_id', $roomPrice->room_type_id)
            ->standard()
            ->active()
            ->whereNot('id', $roomPrice->id)
            ->exists();

        if (! $remainingStandardPrices) {
            throw new InvalidArgumentException('Cannot delete the last active standard price for this room type');
        }
    }

    private function handleStandardPrice(RoomPrice $roomPrice): void
    {
        $previousStandardPrice = RoomPrice::query()
            ->where('room_type_id', $roomPrice->room_type_id)
            ->standard()
            ->active()
            ->first();

        if ($previousStandardPrice) {
            $previousStandardPrice->effective_to = $roomPrice->effective_from->subDay();
            $previousStandardPrice->save();
        }
    }

    private function handlePromotionPrice(RoomPrice $roomPrice): void
    {
        $previousPromotionPrice = RoomPrice::query()
            ->where('room_type_id', $roomPrice->room_type_id)
            ->promotion()
            ->latest()
            ->first();

        if ($previousPromotionPrice && ! $roomPrice->effective_from->isAfter($previousPromotionPrice->effective_from)) {
            throw new InvalidArgumentException('Promotion price must be effective after the previous promotion price');
        }
    }

    /**
     * Sync denormalized room type data to ensure data integrity
     */
    private function syncRoomTypeData(RoomPrice $roomPrice, RoomType $roomType): void
    {
        $roomPrice->room_type_id = $roomType->id;
        $roomPrice->room_type_name = $roomType->name;
        $roomPrice->room_type_code = $roomType->code;
    }
}
