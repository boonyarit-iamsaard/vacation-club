<?php

namespace App\Services;

use App\Data\RoomData;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;

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

    public function create(RoomData $roomData): Room
    {
        // TODO: invoke RoomTypeService instead of directly accessing RoomType
        $roomType = RoomType::findOrFail($roomData->room_type_id);

        $room = new Room($roomData->toArray());

        $this->syncRoomTypeData($room, $roomType);

        $room->save();

        return $room->load('type');
    }

    public function update(Room $room, RoomData $roomData): Room
    {
        // TODO: invoke RoomTypeService instead of directly accessing RoomType
        $roomType = RoomType::findOrFail($roomData->room_type_id);

        $room->fill($roomData->toArray());

        $this->syncRoomTypeData($room, $roomType);

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
     * Sync denormalized room type data to ensure data integrity
     */
    private function syncRoomTypeData(Room $room, RoomType $roomType): void
    {
        $room->room_type_id = $roomType->id;
        $room->room_type_name = $roomType->name;
        $room->room_type_code = $roomType->code;
    }
}
