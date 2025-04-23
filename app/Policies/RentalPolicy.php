<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Rental;
use App\Models\User;

class RentalPolicy
{
    /**
     * Détermine si l'utilisateur peut créer une location.
     *
     * @param  User  $user  l'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de créer une location, sinon false.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_RENTAL);
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une location.
     *
     * @param  User  $user  l'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de mettre à jour une location, sinon false.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_RENTAL);
    }

    /**
     * Détermine si l'utilisateur peut consulter une location.
     *
     * @param  User  $user  l'utilisateur qui effectue la demande.
     * @param  Rental  $rental  la location à consulter.
     * @return bool true si l'utilisateur a la permission de consulter une location, sinon false.
     */
    public function read(User $user, Rental $rental): bool
    {
        return $user->hasPermissionTo(Permission::READ_ALL_RENTAL)
            || ($user->customer->id === $rental->customer_id);
    }

    /**
     * Détermine si l'utilisateur peut consulter toutes les locations.
     *
     * @param  User  $user  l'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de consulter toutes les locations, sinon false.
     */
    public function readAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::READ_ALL_RENTAL);
    }

    /**
     * Vérifie qu'un utilisateur consulte ses propres locations.
     *
     * @param  User  $user  l'utilisateur qui effectue la demande.
     * @param  int  $id  L'identifiant du client qui consulte ses locations.
     */
    public function readOwn(User $user, int $id): bool
    {
        return $user->hasPermissionTo(Permission::READ_ALL_RENTAL) || $user->customer->id === $id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une location.
     *
     * @param  User  $user  l'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de supprimer une location, sinon false.
     */
    public function delete(User $user, Rental $rental): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_RENTAL) || $user->customer->id === $rental->customer_id;
    }
}
