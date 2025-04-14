<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'      => $this->faker->randomElement(DocumentType::toValuesArray()),
            'url'       => $this->faker->url(),
            'rental_id' => Rental::factory()
        ];
    }
}
