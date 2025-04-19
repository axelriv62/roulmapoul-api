<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
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
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => strtolower($firstName . '.' . $lastName) . '@domain.fr',
            'phone' => $this->faker->phoneNumber(),
            'num' => $this->faker->buildingNumber(),
            'street' => $this->faker->streetName(),
            'zip' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'num_bill' => $this->faker->buildingNumber(),
            'street_bill' => $this->faker->streetName(),
            'zip_bill' => $this->faker->postcode(),
            'city_bill' => $this->faker->city(),
            'country_bill' => $this->faker->country(),
        ];
    }
}
