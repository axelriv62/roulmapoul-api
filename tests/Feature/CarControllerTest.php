<?php

namespace Tests\Feature;

use App\Enums\CarAvailability;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use Tests\TestCase;

class CarControllerTest extends TestCase
{

    /**
     * Vérifie que la route de la liste des voitures est accessible.
     */
    public function test_index(): void
    {
        $response = $this->get(route('cars.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que les filtres de la liste des voitures fonctionnent.
     */
    public function test_filter_index(): void
    {
        $agency = Agency::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category1->id,
            'availability' => CarAvailability::AVAILABLE,
        ]);
        Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category2->id,
            'availability' => CarAvailability::AVAILABLE,
        ]);

        $response = $this->get(route('cars.index', [
            'availability' => 'available',
            'category_id' => $category1->id,
        ]));

        $response->assertJsonCount(1, 'data.cars');
    }

    /**
     * Vérifie que la route de la liste des voitures filtrées par agence est accessible.
     */
    public function test_index_agency(): void
    {
        $response = $this->get(route('cars.index-agency', 1));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que le filtrage de la route de la liste des voitures filtrées par agence fonctionne.
     */
    public function test_filter_index_agency(): void
    {
        $agency1 = Agency::factory()->create();
        $agency2 = Agency::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        Car::factory()->create([
            'agency_id' => $agency1->id,
            'category_id' => $category1->id,
            'availability' => CarAvailability::AVAILABLE,
        ]);
        Car::factory()->create([
            'agency_id' => $agency1->id,
            'category_id' => $category2->id,
            'availability' => CarAvailability::AVAILABLE,
        ]);
        Car::factory()->create([
            'agency_id' => $agency2->id,
            'category_id' => $category1->id,
            'availability' => CarAvailability::AVAILABLE,
        ]);
        Car::factory()->create([
            'agency_id' => $agency2->id,
            'category_id' => $category2->id,
            'availability' => CarAvailability::AVAILABLE,
        ]);

        $response = $this->get(route('cars.index-agency', $agency1->id).'?availability=available&category_id='.$category1->id);
        $response->assertJsonCount(1, 'data.cars');
    }
}
