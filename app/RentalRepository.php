<?php

namespace App;

use App\Enums\RentalState;
use App\Models\Rental;

class RentalRepository implements RentalRepositoryInterface
{
    /**
     * @implements RentalRepositoryInterface::isDeleteable
     */
    public static function isDeleteable(Rental $rental): bool
    {
        return $rental->state == RentalState::PAID->value;
    }
}
