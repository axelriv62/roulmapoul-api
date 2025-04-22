<?php

namespace App\Repositories;

use App\Enums\RentalState;
use App\Models\Rental;
use Carbon\Carbon;

class CarRepository implements CarRepositoryInterface
{
    /**
     * Vérifie si la voiture est louable à une date donnée.
     *
     * @param  string  $car_plate  La plaque d'immatriculation de la voiture à louer.
     * @param  Carbon  $start  La date de début de la location.
     * @param  Carbon  $end  La date de fin de la location.
     * @return bool true si la location est faisable, sinon false.
     */
    public static function isRentable(string $car_plate, Carbon $start, Carbon $end): bool
    {
        $existingCarRentals = Rental::where('car_plate', $car_plate)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('start', '<=', $end)
                                ->where('end', '>=', $start);
                        });
                });
            })
            ->where('state', '!=', RentalState::CANCELED->value)
            ->get();

        return $existingCarRentals->isEmpty();
    }
}
