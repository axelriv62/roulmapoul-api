<?php

namespace Database\Seeders;

use App\Enums\RentalState;
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
        $rentals = Rental::all();

        foreach ($rentals as $rental) {
            if ($rental->start->isNowOrPast() && $rental->state !== RentalState::CANCELED) {
                $customer = $rental->customer;

                $withdrawal = Withdrawal::factory()->make();
                $withdrawal->rental_id = $rental->id;
                $withdrawal->customer_id = $customer->id;
                $withdrawal->datetime = $rental->start->addMinutes(rand(0, 1440));
                $withdrawal->save();
            }
        }
    }
}
