<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Rental;
use App\Repositories\CarRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CarRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Teste si une voiture est louable à une date donnée.
     */
    public function test_car_is_rentable(): void
    {
        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $customer = Customer::factory()->create();
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now()->addDay(),
            'end' => now()->addDays(5),
            'customer_id' => $customer->id,
        ]);

        $this->assertTrue(CarRepository::isRentable($car->plate, now()->addDays(6), now()->addDays(10)));
        $this->assertFalse(CarRepository::isRentable($car->plate, now()->addDays(3), now()->addDays(4)));
        $this->assertFalse(CarRepository::isRentable($car->plate, now()->addDay(), now()->addDays(3)));
    }
}
