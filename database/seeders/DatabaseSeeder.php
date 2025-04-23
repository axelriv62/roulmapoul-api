<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Customer;
use App\Models\License;
use App\Models\Rental;
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
            WithdrawalSeeder::class,
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

        $gerard = Customer::factory()->create([
            'first_name' => 'Gérard',
            'last_name' => 'Martin',
            'email' => 'gerard.martin@domain.fr',
        ]);

        License::factory()->create([
            'num' => '12345678912',
            'birthday' => '1980-01-01',
            'acquirement_date' => '2000-01-01',
            'distribution_date' => '2000-01-09',
            'country' => 'Belgium',
            'customer_id' => $gerard->id,
        ]);

        $gerardUser = User::factory()->create([
            'name' => 'gerard.martin',
            'email' => $gerard->email,
        ])->assignRole(Role::CLIENT->value);
        $gerard->user_id = $gerardUser->id;
        $gerard->save();

        $gerard = Customer::where('email', 'gerard.martin@domain.fr')->first();
        if ($gerard) {
            Rental::factory()->create([
                'start' => '2023-12-01 10:00:00',
                'end' => '2023-12-10 10:00:00',
                'nb_days' => 9,
                'state' => 'paid',
                'total_price' => 450.00,
                'car_plate' => 'XX000XX',
                'customer_id' => $gerard->id, // Utilisation de l'ID de Gérard
            ]);
        } else {
            $this->command->warn('Le client Gérard Martin n\'existe pas.');
        }
    }
}
