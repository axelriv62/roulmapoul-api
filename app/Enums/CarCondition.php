<?php

namespace App\Enums;

enum CarCondition: string
{
    case GOOD = 'good';
    case NEEDS_REPAIR = 'needs_repair';
    case NEEDS_MAINTENANCE = 'needs_maintenance';

    public static function toValuesArray(): array
    {
        return array_map(fn ($type) => $type->value, self::toArray());
    }

    public static function toArray(): array
    {
        return [
            self::GOOD,
            self::NEEDS_REPAIR,
            self::NEEDS_MAINTENANCE,
        ];
    }
}
