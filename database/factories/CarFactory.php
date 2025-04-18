<?php

namespace Database\Factories;

use App\Enums\CarAvailability;
use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $availability = CarAvailability::AVAILABLE->value;

        // Simuler une voiture en maintenance ou en réparation
        if (rand(0, 100) < 10) { // probabilité de 10%
            $availability = rand(0, 1)
                ? CarAvailability::UNDER_MAINTENANCE->value
                : CarAvailability::UNDER_REPAIR->value;
        }

        return [
            'plate' => strtoupper($this->faker->unique()->bothify('??###??')), // les ? seront remplacé par des lettres et les # des chiffres
            'availability' => $availability,
            'price_day' => $this->faker->randomFloat(2, 50, 200), // prix entre 50 et 200 par jour
        ];
    }
}
