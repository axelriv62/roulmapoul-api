<?php

namespace Database\Factories;

use App\Enums\CarCondition;
use App\Models\Handover;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Handover>
 */
class HandoverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fuel_level' => $this->faker->randomFloat(2, 0.01, 50),
            'interior_condition' => $this->faker->randomElement(CarCondition::toValuesArray()),
            'exterior_condition' => $this->faker->randomElement(CarCondition::toValuesArray()),
            'mileage' => $this->faker->randomFloat(2, 0.01, 200000),
            'comment' => $this->faker->text(),
        ];
    }
}
