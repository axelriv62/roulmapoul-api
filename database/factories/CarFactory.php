<?php

namespace Database\Factories;

use App\Enums\CarAvailability;
use App\Models\Agency;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
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
            'availability'=> $this->faker->randomElement(CarAvailability::toValuesArray()),
            'price_day'   => $this->faker->randomFloat(2, 50, 200), // Prix entre 50 et 200 par jour
            'agency_id'   => Agency::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
