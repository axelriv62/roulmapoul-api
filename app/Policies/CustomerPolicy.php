<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Vérifie si l'utilisateur consulte son propre profil.
     *
     * @param User $user L'utilisateur qui effectue la demande.
     * @param Customer $customer Le client dont le profil est consulté.
     * @return bool true si l'utilisateur consulte son propre profil, sinon false.
     */
    public function read(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo(Permission::READ_CUSTOMER)
            && ($user->id === $customer->user->id);
    }
}
