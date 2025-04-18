<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientRole = SpatieRole::create(['name' => Role::CLIENT->value]);
        $agentRole = SpatieRole::create(['name' => Role::AGENT->value]);
        $adminRole = SpatieRole::create(['name' => Role::ADMIN->value]);

        $permissions = [
            Permission::CREATE_RENTAL,
            Permission::UPDATE_RENTAL,
            Permission::READ_ALL_RENTAL,
            Permission::CREATE_WITHDRAWAL,
            Permission::CREATE_HANDOVER,
            Permission::CREATE_AMENDMENT,
            Permission::READ_CUSTOMER,
        ];

        foreach ($permissions as $permission) {
            SpatiePermission::create(['name' => $permission->value]);
        }

        $adminRole->givePermissionTo(SpatiePermission::all());

        $agentRole->givePermissionTo([
            Permission::CREATE_RENTAL,
            Permission::UPDATE_RENTAL,
            Permission::READ_ALL_RENTAL,
            Permission::CREATE_WITHDRAWAL,
            Permission::CREATE_HANDOVER,
            Permission::CREATE_AMENDMENT,
            Permission::READ_CUSTOMER,
        ]);

        $clientRole->givePermissionTo([
            Permission::CREATE_RENTAL,
            Permission::READ_ALL_RENTAL,
            Permission::READ_CUSTOMER,
        ]);
    }
}
