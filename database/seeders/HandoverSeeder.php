<?php

namespace Database\Seeders;

use App\Enums\RentalState;
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
        // Récupérer les locations qui sont terminées
        $rentals = Rental::all()->where('state', RentalState::COMPLETED->value);
        $rental_ids = $rentals->pluck('id');

        foreach ($rental_ids as $rental_id) {
            $rental = Rental::find($rental_id);
            $customer = $rental->customer;
            $user = $customer->user;

            $handover = Handover::factory()->make();
            $handover->rental_id = $rental->id;
            $handover->user_id = $user->id;
            $handover->datetime = $rental->end->addMinutes(rand(-1440, 1440)); // date de retour entre 24 heures avant et 24 heures après la fin de la location
            $handover->save();
        }
    }
}
