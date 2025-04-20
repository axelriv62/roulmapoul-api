<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Vérifie si l'utilisateur peut créer une catégorie.
     *
     * @param User $user L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur peut créer une catégorie, sinon false.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_CATEGORY);
    }

    /**
     * Vérifie si l'utilisateur peut modifier une catégorie.
     *
     * @param User $user L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur peut modifier une catégorie, sinon false.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_CATEGORY);
    }
}
