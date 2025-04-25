<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class HandoverPolicy
{
    /**
     * Détermine si l'utilisateur peut créer un retour de véhicule.
     *
     * @param  User  $user  L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de créer un retour de véhicule, sinon false.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_HANDOVER);
    }
}
