<?php

namespace Database\Factories;

use App\Enums\CarCondition;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Withdrawal>
 */
class WithdrawalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'datetime' => Carbon::now(),
            'fuel_level' => $this->faker->randomFloat(2, 0.01, 50),
            'interior_condition' => CarCondition::GOOD->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'mileage' => $this->faker->randomFloat(2, 0.01, 200000),
            'comment' => $this->faker->text(),
        ];
    }
}
