<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AgencySeeder::class,
            CategorySeeder::class,
            CarSeeder::class,
            CustomerSeeder::class,
            WarrantySeeder::class,
            RentalSeeder::class,
            AmendmentSeeder::class,
            HandoverSeeder::class,
            LicenseSeeder::class,
            OptionSeeder::class,
            WithdrawalSeeder::class
        ]);

        User::factory()->create([
            'name' => 'robert.duchmol',
            'email' => 'robert.duchmol@roulmapoul.fr',
        ]);
    }
}
