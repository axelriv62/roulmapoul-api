<?php

namespace Database\Seeders;

use App\Enums\CarAvailability;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Warranty;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentals = Rental::factory(50)->make();
        $customer_ids = Customer::all()->pluck('id');
        $warranty_ids = Warranty::all()->pluck('id');

        foreach ($rentals as $rental) {
            // Récupérer les voitures disponibles
            $cars = Car::all()->where('availability', CarAvailability::AVAILABLE->value);
            $car_plates = $cars->pluck('plate');

            $rental->car_plate = $car_plates->random();
            $rental->customer_id = $customer_ids->random();

            if (rand(0, 100) < 70) {
                $rental->warranty_id = $warranty_ids->random();
            }

            $rental->save();

            // Mettre à jour la disponibilité de la voiture
            $car = Car::where('plate', $rental->car_plate)->first();
            if ($car) {
                $availability = CarAvailability::RENTED->value;

                if ($rental->end && now()->greaterThan($rental->end)) {
                    $availability = CarAvailability::AVAILABLE->value;
                }

                $car->update(['availability' => $availability]);
            }
        }
    }
}
