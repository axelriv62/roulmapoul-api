<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class HandoverPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_HANDOVER);
    }
}
