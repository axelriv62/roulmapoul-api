<?php

namespace Database\Factories;

use App\Models\Amendment;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Amendment>
 */
class AmendmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'      => $this->faker->unique()->word(),
            'price'     => $this->faker->randomFloat(2, 10, 100),
            'content'   => $this->faker->text(),
            'rental_id' => Rental::factory()
        ];
    }
}
