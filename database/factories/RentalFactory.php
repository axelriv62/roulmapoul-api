<?php

namespace Database\Factories;

use App\Enums\RentalState;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Rental>
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
        $start = Carbon::now()->addDays(rand(-60, 60));
        $end = $start->copy()->addDays(rand(7, 30));

        $state = Carbon::now()->between($start, $end)
            ? RentalState::ONGOING->value
            : $this->faker->randomElement(RentalState::toValuesArray());

        return [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'nb_days' => $start->diffInDays($end),
            'state' => $state,
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
