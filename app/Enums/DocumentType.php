<?php

namespace App\Enums;

enum DocumentType: string
{
    case BILL = 'bill';
    case WITHDRAWAL = 'withdrawal';
    case HANDOVER = 'handover';

    public static function toValuesArray(): array
    {
        return array_map(fn($type) => $type->value, self::toArray());
    }

    public static function toArray(): array
    {
        return [
            self::BILL,
            self::WITHDRAWAL,
            self::HANDOVER
        ];
    }
}
