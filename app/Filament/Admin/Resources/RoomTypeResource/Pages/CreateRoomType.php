<?php

namespace App\Filament\Admin\Resources\RoomTypeResource\Pages;

use App\Filament\Admin\Resources\RoomTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoomType extends CreateRecord
{
    protected static string $resource = RoomTypeResource::class;

    protected static bool $canCreateAnother = false;
}
