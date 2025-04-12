<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agency>
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
            'num'     => $this->faker->unique()->buildingNumber(),
            'street'  => $this->faker->streetAddress(),
            'zip'     => $this->faker->postcode(),
            'city'    => $this->faker->city(),
            'country' => $this->faker->country(),
        ];
    }
}
