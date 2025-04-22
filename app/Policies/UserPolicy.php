<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class UserPolicy
{
    /**
     * Détermine si l'utilisateur peut enregistrer un agent. Seuls les administrateurs peuvent enregistrer un agent.
     *
     * @param  User  $user  L'utilisateur authentifié.
     * @return bool Vrai si l'utilisateur a la permission de créer un agent, sinon faux.
     */
    public function createAgent(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_AGENT);
    }
}
