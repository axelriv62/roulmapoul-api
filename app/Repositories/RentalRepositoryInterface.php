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
}
