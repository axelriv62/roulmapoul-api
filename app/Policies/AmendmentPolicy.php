<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class AmendmentPolicy
{
    /**
     * Détermine si l'utilisateur peut créer un avenant.
     *
     * @param  User  $user  L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de créer un avenant, sinon false.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_AMENDMENT);
    }
}
