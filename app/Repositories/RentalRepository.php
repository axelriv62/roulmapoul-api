<?php

namespace App\Repositories;

use App\Enums\RentalState;
use App\Models\Rental;
use Carbon\Carbon;

class RentalRepository implements RentalRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public static function isDeletable(Rental $rental): bool
    {
        return $rental->state == RentalState::PAID->value;
    }

    /**
     * {@inheritDoc}
     */
    public static function calculateTotalPrice(Rental $rental): float
    {
        return round($rental->car->price_day * $rental->nb_days + ($rental->options->sum('price') ?? 0) + ($rental->warranty->price ?? 0), 2);
    }

    /**
     * {@inheritDoc}
     */
    public static function isUpdatable(Rental $rental, Carbon $start, Carbon $end): bool
    {
        $existingCarRentals = Rental::where('car_plate', $rental->car_plate)
            ->where('id', '!=', $rental->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end]);
            })
            ->where('state', '!=', RentalState::CANCELED->value)
            ->exists();

        return ! $existingCarRentals;
    }
}
