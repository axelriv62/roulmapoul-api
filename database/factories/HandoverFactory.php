<?php

namespace Database\Factories;

use App\Models\Handover;
use App\Models\Rental;
use App\Models\User;
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
            'fuel_level'         => $this->faker->randomFloat(2, 0.01, 50),
            'interior_condition' => $this->faker->text(20),
            'exterior_condition' => $this->faker->text(20),
            'mileage'            => $this->faker->randomFloat(2, 0.01, 200000),
            'datetime'           => fn (array $attributes) => Rental::find($attributes['rental_id'])->end->copy()->addDays(rand(-1, 1)),
            'comment'            => $this->faker->text(),
            'user_id'            => User::factory(),
            'rental_id'          => Rental::factory()
        ];
    }
}
