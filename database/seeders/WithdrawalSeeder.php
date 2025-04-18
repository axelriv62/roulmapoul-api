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

            $withdrawal = Withdrawal::factory()->make();
            $withdrawal->rental_id = $rental->id;
            $withdrawal->customer_id = $customer->id;
            $withdrawal->datetime = $rental->start->addMinutes(rand(30, 1440)); // date de retrait entre 30 minutes et 24 heures après le début de la location
            $withdrawal->save();
        }
    }
}
