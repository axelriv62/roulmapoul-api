<?php

namespace Tests\Feature;

use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que la liste des agences est accessible.
     */
    public function test_index(): void
    {
        $response = $this->get(route('agencies.index'));

        $response->assertStatus(200);
    }

    /**
     * Vérifie que le filtrage de la liste des agences fonctionne.
     */
    public function test_filter_index(): void
    {
        Agency::factory()->create(
            [
                'city' => 'Paris',
                'zip' => '75000',
            ]
        );
        Agency::factory()->create(
            [
                'city' => 'Paris',
                'zip' => '75195',
            ]
        );

        $response = $this->get(route('agencies.index').'?city=Paris&zip=75000');

        $response->assertJsonCount(1, 'data.agencies');
    }

    /**
     * Vérifie que le tri ascendant de la liste des agences fonctionne.
     */
    public function test_sort_index_asc(): void
    {
        Agency::factory()->create(
            [
                'name' => 'Agence',
            ]
        );
        Agency::factory()->create(
            [
                'name' => 'Zoo',
            ]
        );

        $response = $this->get(route('agencies.index').'?sort=asc');

        $response->assertJsonPath('data.agencies.0.name', 'Agence');
    }

    /**
     * Vérifie que le tri descendant de la liste des agences fonctionne.
     */
    public function test_sort_index_desc(): void
    {
        Agency::factory()->create(
            [
                'name' => 'Agence',
            ]
        );
        Agency::factory()->create(
            [
                'name' => 'Zoo',
            ]
        );

        $response = $this->get(route('agencies.index').'?sort=desc');

        $response->assertJsonPath('data.agencies.0.name', 'Zoo');
    }
}
