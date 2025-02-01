<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Arr;

enum Role: string implements HasColor, HasLabel
{
    case ADMINISTRATOR = 'administrator';
    case USER = 'user';

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
            self::ADMINISTRATOR => 'danger',
            self::USER => 'info',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMINISTRATOR => 'Administrator',
            self::USER => 'User',
        };
    }
}
