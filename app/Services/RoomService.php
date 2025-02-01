<?php

namespace App\Services;

use App\DataTransferObjects\RoomData;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class RoomService
{
    /**
     * @return Collection<int, Room>
     */
    public function list(): Collection
    {
        return Room::with('type')->get();
    }

    public function find(int $id): ?Room
    {
        return Room::with('type')->find($id);
    }

    public function create(StoreRoomRequest|RoomData $data): Room
    {
        $roomData = $data instanceof StoreRoomRequest
            ? RoomData::fromRequest($data->validated())
            : $data;

        $roomType = RoomType::findOrFail($roomData->room_type_id);

        $room = new Room([
            'name' => $roomData->name,
            'room_type_id' => $roomData->room_type_id,
        ]);
        $this->syncRoomTypeData($room, $roomType);
        $room->save();

        return $room->load('type');
    }

    public function update(Room $room, UpdateRoomRequest $request): Room
    {
        $validated = $request->validated();

        // If room type is being updated, ensure it exists and sync denormalized data
        if (isset($validated['room_type_id'])) {
            $roomType = RoomType::findOrFail($validated['room_type_id']);
            $this->syncRoomTypeData($room, $roomType);
        }

        $room->fill($validated);
        $room->save();

        return $room->load('type');
    }

    /**
     * Delete a room
     *
     * Note: Since we don't have cascading in database, we need to ensure
     * there are no dependent records before deletion
     */
    public function delete(Room $room): void
    {
        // Add any additional relationship checks here if needed in the future
        $room->delete();
    }

    /**
     * Maintain denormalized data integrity for room type relationship
     * Used by model observers to ensure data consistency during create/update operations
     */
    public function maintainRoomTypeIntegrity(Room $room): void
    {
        if (! $room->room_type_id) {
            throw new InvalidArgumentException('Room type ID is required');
        }

        $roomType = RoomType::find($room->room_type_id);

        if (! $roomType) {
            throw new InvalidArgumentException('Room type not found');
        }

        $this->syncRoomTypeData($room, $roomType);
    }

    /**
     * Sync denormalized room type data to ensure data integrity
     */
    private function syncRoomTypeData(Room $room, RoomType $roomType): void
    {
        $room->room_type_id = $roomType->id;
        $room->room_type_name = $roomType->name;
        $room->room_type_code = $roomType->code;
    }
}
