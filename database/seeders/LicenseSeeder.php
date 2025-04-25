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
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $license = License::factory()->make();
            $license->customer_id = $customer->id;
            $license->save();
        }
    }
}
