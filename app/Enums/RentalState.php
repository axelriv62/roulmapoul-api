<?php

namespace App\Enums;

enum RentalState: string
{
    case PAID = 'paid';
    case CANCELED = 'canceled';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';

    public static function toValuesArray(): array
    {
        return array_map(fn (self $state) => $state->value, self::toArray());
    }

    public static function toArray(): array
    {
        return [
            self::PAID,
            self::CANCELED,
            self::ONGOING,
            self::COMPLETED,
        ];
    }
}
