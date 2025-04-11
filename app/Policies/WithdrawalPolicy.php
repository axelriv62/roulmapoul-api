<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class WithdrawalPolicy
{
    /**
     * Détermine si l'utilisateur peut créer une demande de retrait.
     *
     * @param User $user L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur a la permission de créer une demande de retrait, sinon false.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_WITHDRAWAL);
    }

}
