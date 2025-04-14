<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\License>
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
            'birthday'          => $this->faker->date(),
            'acquirement_date'  => $this->faker->date(),
            'distribution_date' => $this->faker->date(),
            'country'           => $this->faker->country(),
            'customer_id'       => Customer::factory()
        ];
    }
}
