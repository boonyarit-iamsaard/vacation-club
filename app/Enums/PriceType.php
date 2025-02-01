<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Arr;

enum PriceType: string implements HasColor, HasLabel
{
    case STANDARD = 'standard';
    case PROMOTION = 'promotion';

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
            self::STANDARD => 'primary',
            self::PROMOTION => 'warning',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::STANDARD => 'Standard',
            self::PROMOTION => 'Promotion',
        };
    }
}
