<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class OptionPolicy
{
    /**
     * Vérifie si l'utilisateur peut créer une option.
     *
     * @param User $user L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur peut créer une option, sinon false.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_OPTION);
    }

    /**
     * Vérifie si l'utilisateur peut modifier une option.
     *
     * @param User $user L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur peut modifier une option, sinon false.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_OPTION);
    }
}
