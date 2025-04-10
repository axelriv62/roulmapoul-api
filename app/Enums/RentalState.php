<?php

namespace App\Enums;

enum RentalState: string
{
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
    case IN_PROGRESS = 'in_progress';
    case FINISHED = 'finished';
}
