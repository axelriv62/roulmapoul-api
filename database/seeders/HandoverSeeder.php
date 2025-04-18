<?php

namespace Database\Seeders;

use App\Models\Handover;
use App\Models\Rental;
use Illuminate\Database\Seeder;

class HandoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $handovers = Handover::factory(30)->make();
        $rental_ids = Rental::all()->pluck('id');

        foreach ($handovers as $handover) {
            $rental = Rental::find($rental_ids->random());
            $customer = $rental->customer;
            $user = $customer->user;

            $handover->rental_id = $rental->id;
            $handover->user_id = $user->id;
            $handover->save();
        }
    }
}
