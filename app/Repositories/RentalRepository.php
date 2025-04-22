<?php

namespace App\Repositories;

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

    public static function calculateTotalPrice(Rental $rental): float
    {
        return $rental->car->price_day * $rental->nb_days + ($rental->options->sum('price') ?? 0) + ($rental->warranty->price ?? 0);
    }
}
