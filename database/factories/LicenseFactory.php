<?php

namespace Database\Factories;

use App\Models\License;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
        $acquirement_date = Carbon::createFromFormat('Y-m-d', $this->faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'));
        $distribution_date = $acquirement_date->copy()->addDays(rand(1, 10));

        return [
            'num' => Hash::make($this->faker->unique()->numerify('############')),
            'birthday' => $this->faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'acquirement_date' => $acquirement_date,
            'distribution_date' => $distribution_date,
            'country' => $this->faker->country(),
        ];
    }
}
