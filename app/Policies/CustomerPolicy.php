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
     * @param  User  $user  L'utilisateur qui effectue la demande.
     * @param  Customer  $customer  Le client dont le profil est consulté.
     * @return bool true si l'utilisateur consulte son propre profil, sinon false.
     */
    public function read(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo(Permission::READ_CUSTOMER)
            && ($user->id === $customer->user->id);
    }

    /**
     * Vérifie si l'utilisateur peut lister les clients
     *
     * @param  User  $user  L'utilisateur qui effectue la demande.
     * @return bool true si l'utilisateur peut lister les clients, sinon false.
     */
    public function readAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::READ_ALL_CUSTOMER);
    }

    /**
     * Vérifie si l'utilisateur peut modifier le profil d'un client.
     *
     * @param  User  $user  L'utilisateur qui effectue la demande.
     * @param  Customer  $customer  Le client dont le profil est modifié.
     * @return bool true si l'utilisateur peut modifier le profil, sinon false.
     */
    public function update(User $user, Customer $customer): bool
    {
        if ($user->hasPermissionTo(Permission::UPDATE_CUSTOMER)) {
            return true;
        }

        return $user->id === $customer->user->id;
    }
}
