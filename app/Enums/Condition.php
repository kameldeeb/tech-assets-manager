<?php

namespace App\Enums;

enum Condition: string
{
    case EXCELLENT = 'excellent';
    case GOOD = 'good';
    case FAIR = 'fair';
    case NEEDS_REPAIR = 'needs_repair';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
