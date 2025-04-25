<?php

namespace App\Enums;

enum CarAvailability: string
{
    case RESERVED = 'reserved';
    case AVAILABLE = 'available';
    case RENTED = 'rented';
    case UNDER_MAINTENANCE = 'under_maintenance';
    case UNDER_REPAIR = 'under_repair';

    public static function toValuesArray(): array
    {
        return array_map(fn ($case) => $case->value, self::toArray());
    }

    public static function toArray(): array
    {
        return [
            self::RESERVED,
            self::AVAILABLE,
            self::RENTED,
            self::UNDER_MAINTENANCE,
            self::UNDER_REPAIR,
        ];
    }
}
