<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'    => $this->faker->firstName(),
            'last_name'     => $this->faker->lastName(),
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->phoneNumber(),

            'num'           => $this->faker->buildingNumber(),
            'street'        => $this->faker->streetName(),
            'zip'           => $this->faker->postcode(),
            'city'          => $this->faker->city(),
            'country'       => $this->faker->country(),
            'num_bill'      => $this->faker->buildingNumber(),
            'street_bill'   => $this->faker->streetName(),
            'zip_bill'      => $this->faker->postcode(),
            'city_bill'     => $this->faker->city(),
            'country_bill'  => $this->faker->country(),

            'user_id'       => User::factory(),
        ];
    }
}
