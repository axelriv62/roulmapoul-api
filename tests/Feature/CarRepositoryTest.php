<?php

namespace Tests\Feature;

use App\Enums\RentalState;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Rental;
use App\Repositories\CarRepository;
use Carbon\Carbon;
use Tests\TestCase;

class CarRepositoryTest extends TestCase
{

    protected Car $car;

    protected Carbon $now;

    /**
     * Teste si la voiture est louable après la période de location existante.
     */
    public function test_car_is_rentable_after_period(): void
    {
        $this->assertTrue(CarRepository::isRentable($this->car->plate, $this->now->copy()->addDays(6), $this->now->copy()->addDays(10)));
    }

    /**
     * Teste si la voiture n'est pas louable quand les dates se chevauchent partiellement avec une réservation existante.
     */
    public function test_car_is_not_rentable_when_dates_partially_overlap(): void
    {
        $this->assertFalse(CarRepository::isRentable($this->car->plate, $this->now->copy()->addDays(3), $this->now->copy()->addDays(7)));
    }

    /**
     * Teste si la voiture n'est pas louable quand les dates se chevauchent entièrement avec une réservation existante.
     */
    public function test_car_is_not_rentable_when_dates_fully_overlap(): void
    {
        $this->assertFalse(CarRepository::isRentable($this->car->plate, $this->now->copy()->addDays(2), $this->now->copy()->addDays(4)));
    }

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d', '2026-01-01'));
        $this->now = Carbon::parse(Carbon::getTestNow()->format('Y-m-d'));
        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $customer = Customer::factory()->create();
        $this->car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        Rental::factory()->create([
            'car_plate' => $this->car->plate,
            'start' => $this->now->copy()->addDay(),
            'end' => $this->now->copy()->addDays(5),
            'state' => RentalState::PAID->value,
            'customer_id' => $customer->id,
        ]);
    }
}
