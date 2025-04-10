<?php

namespace App\Enums;

enum CarAvailability: string
{
    case RESERVED = 'reserved';
    case AVAILABLE = 'available';
    case RENTED = 'rented';
    case UNDER_MAINTENANCE = 'under_maintenance';
    case UNDER_REPAIR = 'under_repair';
}
