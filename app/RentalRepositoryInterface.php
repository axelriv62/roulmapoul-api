<?php

namespace App;

use App\Models\Rental;

interface RentalRepositoryInterface
{
    /**
     * Vérifie si la location est supprimable.
     *
     * @param Rental $rental La location à vérifier.
     * @return bool true si la location est supprimable, sinon false.
     */
    public static function isDeleteable(Rental $rental): bool;
}
