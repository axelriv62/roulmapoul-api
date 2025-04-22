<?php

namespace App\Repositories;

use App\Enums\RentalState;
use App\Models\Rental;
use Carbon\Carbon;

class CarRepository implements CarRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public static function isRentable(string $car_plate, Carbon $start, Carbon $end): bool
    {
        $car_rentals = Rental::where('car_plate', $car_plate)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end]);
            })
            ->where('state', '!=', RentalState::CANCELED->value)
            ->get();

        return $car_rentals->isEmpty();
    }
}
