<?php

namespace App\Services;

use App\DataTransferObjects\RoomPriceData;
use App\Enums\PriceType;
use App\Http\Requests\RoomPrice\StoreRoomPriceRequest;
use App\Http\Requests\RoomPrice\UpdateRoomPriceRequest;
use App\Models\RoomPrice;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
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

    public function create(StoreRoomPriceRequest|RoomPriceData $data): RoomPrice
    {
        $priceData = $data instanceof StoreRoomPriceRequest
            ? RoomPriceData::fromRequest($data->validated())
            : $data;

        $roomType = RoomType::findOrFail($priceData->room_type_id);

        $roomPrice = new RoomPrice([
            'room_type_id' => $priceData->room_type_id,
            'weekday' => $priceData->weekday,
            'weekend' => $priceData->weekend,
            'type' => $priceData->type,
            'promotion_name' => $priceData->promotion_name,
            'effective_from' => $priceData->effective_from,
            'effective_to' => $priceData->effective_to,
        ]);

        $this->syncRoomTypeData($roomPrice, $roomType);

        if ($priceData->type === PriceType::STANDARD) {
            $this->handleStandardPriceCreation($roomPrice);
        }

        if ($priceData->type === PriceType::PROMOTION) {
            $this->handlePromotionalPriceCreation($roomPrice);
        }

        $roomPrice->save();

        return $roomPrice->load('roomType');
    }

    public function update(RoomPrice $roomPrice, UpdateRoomPriceRequest $request): RoomPrice
    {
        $validated = $request->validated();

        // If room type is being updated, ensure it exists and sync denormalized data
        if (isset($validated['room_type_id'])) {
            $roomType = RoomType::findOrFail($validated['room_type_id']);
            $this->syncRoomTypeData($roomPrice, $roomType);
        }

        // If type is being updated, handle the business logic
        if (isset($validated['type'])) {
            if ($validated['type'] === PriceType::STANDARD->value) {
                $this->handleStandardPriceCreation($roomPrice);
            }

            if ($validated['type'] === PriceType::PROMOTION->value) {
                $this->handlePromotionalPriceCreation($roomPrice);
            }
        }

        $roomPrice->fill($validated);
        $roomPrice->save();

        return $roomPrice->load('roomType');
    }

    /**
     * Delete a room price
     *
     * Note: Since we don't have cascading in database, we need to ensure
     * there are no dependent records and business rules are respected before deletion
     */
    public function delete(RoomPrice $roomPrice): void
    {
        if ($roomPrice->type === PriceType::STANDARD) {
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

        $roomPrice->delete();
    }

    /**
     * @return Collection<int, RoomPrice>
     */
    public function getActiveStandardPrices(): Collection
    {
        return RoomPrice::with('roomType')
            ->standard()
            ->active()
            ->get();
    }

    /**
     * @return Collection<int, RoomPrice>
     */
    public function getActivePromotionalPrices(): Collection
    {
        return RoomPrice::with('roomType')
            ->promotional()
            ->active()
            ->get();
    }

    /**
     * @return Collection<int, RoomPrice>
     */
    public function getFuturePrices(): Collection
    {
        return RoomPrice::with('roomType')
            ->future()
            ->get();
    }

    private function handleStandardPriceCreation(RoomPrice $roomPrice): void
    {
        $previousStandardPrice = RoomPrice::query()
            ->where('room_type_id', $roomPrice->room_type_id)
            ->standard()
            ->active()
            ->first();

        if ($previousStandardPrice) {
            $previousStandardPrice->effective_to = Carbon::parse($roomPrice->effective_from)->subDay();
            $previousStandardPrice->save();
        }
    }

    private function handlePromotionalPriceCreation(RoomPrice $roomPrice): void
    {
        $previousPromotionalPrice = RoomPrice::query()
            ->where('room_type_id', $roomPrice->room_type_id)
            ->promotional()
            ->latest()
            ->first();

        if ($previousPromotionalPrice && ! Carbon::parse($roomPrice->effective_from)->isAfter($previousPromotionalPrice->effective_from)) {
            throw new InvalidArgumentException('Promotional price must be effective after the previous promotional price');
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
