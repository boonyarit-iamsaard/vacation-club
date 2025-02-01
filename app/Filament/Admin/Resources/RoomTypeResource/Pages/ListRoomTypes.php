<?php

namespace App\Filament\Admin\Resources\RoomTypeResource\Pages;

use App\Filament\Admin\Resources\RoomTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoomTypes extends ListRecords
{
    protected static string $resource = RoomTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
