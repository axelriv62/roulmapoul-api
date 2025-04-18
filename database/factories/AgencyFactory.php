<?php

namespace Database\Factories;

use App\Models\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agency>
 */
class AgencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'num' => $this->faker->unique()->buildingNumber(),
            'street' => $this->faker->streetName(),
            'zip' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
        ];
    }
}
