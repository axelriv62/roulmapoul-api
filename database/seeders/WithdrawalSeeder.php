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
        $rental_ids = Rental::all()->pluck('id');

        foreach ($rental_ids as $rental_id) {
            $rental = Rental::find($rental_id);
            $customer = $rental->customer;
            $user = $customer->user;

            $withdrawal = Withdrawal::factory()->make();
            $withdrawal->rental_id = $rental->id;
            $withdrawal->user_id = $user->id;
            $withdrawal->save();
        }
    }
}
