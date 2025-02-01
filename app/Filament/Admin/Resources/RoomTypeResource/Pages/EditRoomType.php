<?php

namespace App\Filament\Admin\Resources\RoomTypeResource\Pages;

use App\Filament\Admin\Resources\RoomTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoomType extends EditRecord
{
    protected static string $resource = RoomTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
