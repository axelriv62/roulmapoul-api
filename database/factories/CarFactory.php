<?php

namespace Database\Factories;

use App\Enums\CarAvailability;
use App\Enums\RentalState;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plate'       => $this->faker->unique()->bothify('??###??'), // les ? seront remplacé par des lettres et les # des chiffres
            'availability'=> CarAvailability::AVAILABLE->value,
            'price_day'   => $this->faker->randomFloat(2, 50, 200), // Prix entre 50 et 200 par jour
            'agency_id'   => Agency::factory(),
            'category_id' => Category::factory(),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Car $car) {
            // Création d'une location associée à la voiture
            $rental = Rental::factory()->create(['car_plate' => $car->plate]);

            // Récupération de la date de début et de fin de la location
            $start_date = $rental->start ?? Carbon::now();
            $end_date = $rental->end ?? null;

            // De base, la voiture est disponible
            $availability = CarAvailability::AVAILABLE->value;

            // Vérification de la disponibilité de la voiture
            if ($end_date && Carbon::now()->between($start_date, $end_date)) {
                $availability = CarAvailability::RENTED->value;
            } elseif (!$end_date && $start_date->isPast()) {
                $availability = CarAvailability::RENTED->value;
            } elseif (rand(0, 100) < 10) { // probabilité de 10% d'être en maintenance ou en réparation
                $availability = rand(0, 1)
                    ? CarAvailability::UNDER_MAINTENANCE->value
                    : CarAvailability::UNDER_REPAIR->value;
            }

            // Mise à jour de la disponibilité de la voiture
            $car->update(['availability' => $availability]);
        });
    }
}
