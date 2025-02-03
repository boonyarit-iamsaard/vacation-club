<?php

namespace App\Services;

use App\Data\RoomTypeData;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class RoomTypeService
{
    private const SNAPSHOT_FIELDS = [
        'room_type_name' => 'name',
        'room_type_code' => 'code',
    ];

    /**
     * @return Collection<int, RoomType>
     */
    public function list(): Collection
    {
        return RoomType::with([
            'prices' => fn ($query) => $query
                ->active()
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->standard()->latest('effective_from')->take(1);
                    })->orWhere(function ($query) {
                        $query->promotion()->latest('effective_from')->take(1);
                    });
                }),
            'rooms',
        ])->get();
    }

    public function find(int $id): ?RoomType
    {
        return RoomType::with([
            'prices' => fn ($query) => $query
                ->active()
                ->where(function ($query) {
                    // TODO: reconsider if this query should be already in scope active
                    $query->where(function ($query) {
                        $query->standard()->latest('effective_from')->take(1);
                    })->orWhere(function ($query) {
                        $query->promotion()->latest('effective_from')->take(1);
                    });
                }),
            'rooms',
        ])->find($id);
    }

    public function create(RoomTypeData $roomTypeData): RoomType
    {
        return RoomType::create($roomTypeData->toArray());
    }

    public function update(RoomType $roomType, RoomTypeData $roomTypeData): RoomType
    {
        $roomType->fill($roomTypeData->toArray());

        $this->syncSnapshots($roomType);

        $roomType->save();

        return $roomType;
    }

    public function delete(RoomType $roomType): void
    {
        /** Nullify relationships while keeping existing snapshots */
        if ($roomType->rooms()->exists()) {
            $roomType->rooms()->update(['room_type_id' => null]);
        }

        if ($roomType->prices()->exists()) {
            $roomType->prices()->update(['room_type_id' => null]);
        }

        $roomType->delete();
    }

    /**
     * Sync room type snapshots to related models
     * Uses batch updates for better performance
     */
    private function syncSnapshots(RoomType $roomType): void
    {
        $snapshot = $this->getSnapshot($roomType);

        if ($roomType->rooms()->exists()) {
            $roomType->rooms()->update($snapshot);
        }

        if ($roomType->prices()->exists()) {
            $roomType->prices()->update($snapshot);
        }
    }

    /**
     * Get snapshot of room type fields
     *
     * @return array<string, string>
     */
    private function getSnapshot(RoomType $roomType): array
    {
        return Arr::mapWithKeys(self::SNAPSHOT_FIELDS, fn (string $sourceField, string $snapshotField) => [
            $snapshotField => $roomType->{$sourceField},
        ]);
    }
}
