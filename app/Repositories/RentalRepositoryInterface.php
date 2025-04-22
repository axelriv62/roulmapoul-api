<?php

namespace App\Repositories;

use App\Models\Rental;

interface RentalRepositoryInterface
{
    /**
     * Vérifie si la location est annulable.
     *
     * @param Rental $rental La location à vérifier.
     * @return bool true si la location est annulable, sinon false.
     */
    public static function isDeleteable(Rental $rental): bool;

    /**
     * Calcule le prix total d'une location.
     *
     * @param Rental $rental La location pour laquelle le prix doit être calculé.
     * @return float Le prix total de la location.
     */
    public static function calculateTotalPrice(Rental $rental): float;
}
