<?php

namespace App\Repositories;

use App\Models\Rental;
use Carbon\Carbon;

interface RentalRepositoryInterface
{
    /**
     * Vérifie si la location est annulable.
     *
     * @param  Rental  $rental  La location à vérifier.
     * @return bool true si la location est annulable, sinon false.
     */
    public static function isDeleteable(Rental $rental): bool;

    /**
     * Vérifie si la location est modifiable à une date donnée.
     *
     * @param  Rental  $rental  La location à vérifier.
     * @param  Carbon  $start  La date de début de la location.
     * @param  Carbon  $end  La date de fin de la location.
     * @return bool true si la location est modifiable, sinon false.
     */
    public static function isUpdatable(Rental $rental, Carbon $start, Carbon $end): bool;

    /**
     * Calcule le prix total d'une location.
     *
     * @param  Rental  $rental  La location pour laquelle le prix doit être calculé.
     * @return float Le prix total de la location.
     */
    public static function calculateTotalPrice(Rental $rental): float;
}
