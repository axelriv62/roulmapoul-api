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

        foreach ($customers as $customer) {
            if (rand(0, 100) < 80) {
                $user = User::factory()->create([
                    'name' => $customer->first_name . '.' . $customer->last_name,
                    'email' => $customer->email,
                ]);
                $customer->user_id = $user->id;
            }
            $customer->save();
        }
    }
}

