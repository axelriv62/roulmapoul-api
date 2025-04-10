<?php

namespace App\Enums;

enum RentalState: string
{
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';

    public static function toValuesArray(): array
    {
        return array_map(fn(self $state) => $state->value, self::toArray());
    }

    public static function toArray(): array
    {
        return [
            self::PAID,
            self::CANCELLED,
            self::ONGOING,
            self::COMPLETED
        ];
    }
}
