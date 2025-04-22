<?php

namespace App\Repositories;

use Carbon\Carbon;

interface CarRepositoryInterface
{
    /**
     * Vérifie si la voiture est louable à une date donnée.
     *
     * @param string $car_plate La plaque d'immatriculation de la voiture à louer.
     * @param Carbon $start La date de début de la location.
     * @param Carbon $end La date de fin de la location.
     * @return bool true si la location est faisable, sinon false.
     */
    public static function isRentable(string $car_plate, Carbon $start, Carbon $end): bool;
}
