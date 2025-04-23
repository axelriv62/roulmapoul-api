<?php

namespace Tests\Feature;

use App\Models\Warranty;
use Tests\TestCase;

class WarrantyControllerTest extends TestCase
{
    /**
     * Vérifie que la route de la liste des catégories fonctionne.
     */
    public function test_index(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->get(route('warranties.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que les catégories sont bien récupérées.
     */
    public function test_get_index(): void
    {
        Warranty::factory(2)->create();
        $response = $this->withHeader('Accept', 'application/json')->get(route('warranties.index'));
        $response->assertJsonCount(2, 'data.warranties');
    }
}
