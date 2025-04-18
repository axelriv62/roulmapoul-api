<?php

namespace Database\Seeders;

use App\Enums\RentalState;
use App\Models\Handover;
use App\Models\Rental;
use Database\Factories\AmendmentFactory;
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

        foreach ($rentals as $rental) {
            $rental = Rental::find($rental->id);
            $customer = $rental->customer;
            $user = $customer->user;

            $handover = Handover::factory()->make();
            $handover->rental_id = $rental->id;
            $handover->user_id = $user->id;
            $handover->datetime = $rental->end->addMinutes(rand(-1440, 1440)); // date de retour entre 24 heures avant et 24 heures après la fin de la location
            $handover->save();

            if ($handover->datetime > $rental->end) {
                AmendmentFactory::new()->create([
                    'name' => "Retard",
                    'price' => intval($rental->end->diffInHours($handover->datetime)) * 10, // 10€ par heure de retard
                    'content' => "Véhicule retourné après la date de retour prévue",
                    'rental_id' => $rental->id,
                ]);
            }
        }
    }
}
