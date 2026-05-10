<?php

namespace App\Enums;

enum AssetStatus: string
{
    case AVAILABLE = 'available';
    case BORROWED = 'borrowed';
    case UNDER_INSPECTION = 'under_inspection';
    case MAINTENANCE = 'maintenance';
    case DAMAGED = 'damaged';
    case RETIRED = 'retired';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
