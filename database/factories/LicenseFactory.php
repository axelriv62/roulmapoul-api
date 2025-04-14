<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<License>
 */
class LicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'num'               => $this->faker->randomNumber(12, true),
            'birthday'          => $this->faker->dateTimeBetween('-100 years', '-18 years')->format('Y-m-d'),
            'acquirement_date'  => $this->faker->date(),
            'distribution_date' => $this->faker->date(),
            'country'           => $this->faker->country(),
            'customer_id'       => Customer::factory()
        ];
    }
}
