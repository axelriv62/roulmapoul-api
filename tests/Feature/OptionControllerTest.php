<?php

namespace Tests\Feature;

use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que la route de la liste des catégories fonctionne.
     */
    public function test_index(): void
    {
        $response = $this->get(route('options.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que les catégories sont bien récupérées.
     */
    public function test_get_index(): void
    {
        Option::factory(2)->create();
        $response = $this->get(route('options.index'));
        $response->assertJsonCount(2, 'data.options');
    }
}
