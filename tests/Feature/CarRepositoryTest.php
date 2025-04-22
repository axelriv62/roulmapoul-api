<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Rental;
use App\Repositories\CarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $customer = Customer::factory()->create();
        $this->car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        Rental::factory()->create([
            'car_plate' => $this->car->plate,
            'start' => now()->addDay(),
            'end' => now()->addDays(5),
            'customer_id' => $customer->id,
        ]);
    }

    /**
     * Teste si la voiture est louable après la période de location existante.
     */
    public function test_car_is_rentable_after_period(): void
    {
        $this->assertTrue(CarRepository::isRentable($this->car->plate, now()->addDays(6), now()->addDays(10)));
    }

    /**
     * Teste si la voiture n'est pas louable quand les dates se chevauchent partiellement avec une réservation existante.
     */
    public function test_car_is_not_rentable_when_dates_partially_overlap(): void
    {
        $this->assertFalse(CarRepository::isRentable($this->car->plate, now()->addDays(3), now()->addDays(4)));
    }

    /**
     * Teste si la voiture n'est pas louable quand les dates se chevauchent entièrement avec une réservation existante.
     */
    public function test_car_is_not_rentable_when_dates_fully_overlap(): void
    {
        $this->assertFalse(CarRepository::isRentable($this->car->plate, now()->addDay(), now()->addDays(3)));
    }
}
