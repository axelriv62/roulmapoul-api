<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AgencySeeder::class,
            CategorySeeder::class,
            CarSeeder::class,
            CustomerSeeder::class,
            WarrantySeeder::class,
            OptionSeeder::class,
            RentalSeeder::class,
            HandoverSeeder::class,
            LicenseSeeder::class,
            WithdrawalSeeder::class
        ]);

        $robert = User::factory()->create([
            'name' => 'robert.duchmol',
            'email' => 'robert.duchmol@roulmapoul.fr',
            'password' => Hash::make('GrosSecret'),
            'email_verified_at' => now(),
        ]);
        $robert->assignRole(Role::ADMIN->value);

        $carl = User::factory()->create([
            'name' => 'carl.kolodziejski',
            'email' => 'carl.kolodziejski@roulmapoul.fr',
            'password' => Hash::make('GrosSecret'),
            'email_verified_at' => now(),
        ]);
        $carl->assignRole(Role::AGENT->value);

        $axel = User::factory()->create([
            'name' => 'axel.riviere',
            'email' => 'axel.riviere@roulmapoul.fr',
            'password' => Hash::make('GrosSecret'),
            'email_verified_at' => now(),
        ]);
        $axel->assignRole(Role::AGENT->value);

        $quentin = User::factory()->create([
            'name' => 'quentin.tripognez',
            'email' => 'quentin.tripognez@roulmapoul.fr',
            'password' => Hash::make('GrosSecret'),
            'email_verified_at' => now(),
        ]);
        $quentin->assignRole(Role::AGENT->value);

        $bylel = User::factory()->create([
            'name' => 'bylel.longelin',
            'email' => 'bylel.longelin@roulmapoul.fr',
            'password' => Hash::make('GrosSecret'),
            'email_verified_at' => now(),
        ]);
        $bylel->assignRole(Role::AGENT->value);
    }
}
