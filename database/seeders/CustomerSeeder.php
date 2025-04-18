<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::factory(50)->make();
        $user_ids = User::all()->pluck('id');

        foreach ($customers as $customer) {
            $customer->user_id = $user_ids->random();
            $customer->save();
        }
    }
}
