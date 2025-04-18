<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\License;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licenses = License::factory(50)->make();
        $customer_ids = Customer::all()->pluck('id');

        foreach ($licenses as $license) {
            $license->customer_id = $customer_ids->random(); // TODO Corriger le problème du linter
            $license->save();
        }
    }
}
