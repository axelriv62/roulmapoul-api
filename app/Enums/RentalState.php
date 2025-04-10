<?php

namespace App\Enums;

enum RentalState: string
{
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
}
