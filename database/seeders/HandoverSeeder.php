<?php

namespace Database\Seeders;

use App\Models\Handover;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;

class HandoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $handovers = Handover::factory(50)->make();
        $user_ids = User::all()->pluck('id');
        $rental_ids = Rental::all()->pluck('id');

        foreach ($handovers as $handover) {
            $handover->user_id = $user_ids->random();
            $handover->rental_id = $rental_ids->random();
            $handover->save();
        }
    }
}
