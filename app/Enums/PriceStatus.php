<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Arr;

enum PriceStatus: string implements HasColor, HasLabel
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case FUTURE = 'future';

    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return Arr::pluck(self::cases(), 'value');
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::EXPIRED => 'danger',
            self::FUTURE => 'info',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::EXPIRED => 'Expired',
            self::FUTURE => 'Future',
        };
    }
}
