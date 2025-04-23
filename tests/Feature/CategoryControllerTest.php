<?php

namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{

    /**
     * Vérifie que la route de la liste des catégories fonctionne.
     */
    public function test_index(): void
    {
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que les catégories sont bien récupérées.
     */
    public function test_get_index(): void
    {
        Category::factory(2)->create();
        $response = $this->get(route('categories.index'));
        $response->assertJsonCount(2, 'data.categories');
    }
}
