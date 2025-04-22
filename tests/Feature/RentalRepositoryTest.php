<?php

namespace Tests\Feature;

use App\Enums\RentalState;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Rental;
use App\Repositories\RentalRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private Rental $rental;

    /**
     * Vérifie qu'une location est annulable quand elle est payée.
     */
    public function test_rental_is_deletable_when_paid(): void
    {
        $this->rental->state = RentalState::PAID->value;
        $this->rental->save();
        $this->assertTrue(RentalRepository::isDeletable($this->rental));
    }

    /**
     * Vérifie qu'une location n'est pas annulable quand elle est annulée.
     */
    public function test_rental_is_not_deletable_when_canceled(): void
    {
        $this->rental->state = RentalState::CANCELED->value;
        $this->rental->save();
        $this->assertFalse(RentalRepository::isDeletable($this->rental));
    }

    /**
     * Vérifie qu'une location n'est pas annulable quand elle est finie.
     */
    public function test_rental_is_not_deletable_when_completed(): void
    {
        $this->rental->state = RentalState::COMPLETED->value;
        $this->rental->save();
        $this->assertFalse(RentalRepository::isDeletable($this->rental));
    }

    /**
     * Vérifie qu'une location n'est pas annulable quand elle est en cours.
     */
    public function test_rental_is_not_deletable_when_ongoing(): void
    {
        $this->rental->state = RentalState::ONGOING->value;
        $this->rental->save();
        $this->assertFalse(RentalRepository::isDeletable($this->rental));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $customer = Customer::factory()->create();
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        $this->rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now()->addDay(),
            'end' => now()->addDays(5),
            'customer_id' => $customer->id,
        ]);
    }
}
