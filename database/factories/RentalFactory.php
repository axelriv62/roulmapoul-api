<?php

namespace Database\Factories;

use App\Enums\RentalState;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::now()->addDays(rand(1, 30));
        $end = $start->copy()->addDays(rand(1, 10));

        $state = Carbon::now()->between($start, $end)
            ? RentalState::ONGOING->value
            : $this->faker->randomElement(RentalState::toValuesArray());

        return [
            'start'       => $start,
            'end'         => $end,
            'nb_days'     => $start->diffInDays($end),
            'state'       => $state,
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
            'car_plate'   => Car::factory(),
            'customer_id' => Customer::factory(),
            'warranty_id' => Warranty::factory(),
        ];
    }
}
