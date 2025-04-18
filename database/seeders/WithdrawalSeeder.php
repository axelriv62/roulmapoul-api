<?php

namespace Database\Seeders;

use App\Models\Rental;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class WithdrawalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $withdrawals = Withdrawal::factory(50)->make();
        $rental_ids = Rental::all()->pluck('id');

        foreach ($withdrawals as $withdrawal) {
            $rental = Rental::find($rental_ids->random());
            $customer = $rental->customer;
            $user = $customer->user;

            $withdrawal->rental_id = $rental->id;
            $withdrawal->user_id = $user->id;
            $withdrawal->save();
        }
    }
}
