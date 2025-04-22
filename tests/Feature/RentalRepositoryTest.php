<?php

namespace Tests\Feature;

use App\Enums\RentalState;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Option;
use App\Models\Rental;
use App\Models\Warranty;
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

    /**
     * Vérifie le bon calcul du prix d'une location.
     */
    public function test_calculate_price_of_rental(): void
    {
        $warranty = Warranty::factory()->create();
        $options = Option::factory(2)->create();

        $this->rental->warranty_id = $warranty->id;
        $this->rental->options()->attach($options);
        $this->rental->total_price = RentalRepository::calculateTotalPrice($this->rental);
        $this->rental->save();

        $this->assertEquals($this->rental->total_price, round($this->rental->car->price_day * $this->rental->nb_days + ($options->sum('price') ?? 0) + ($warranty->price ?? 0), 2));
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
            'customer_id' => $customer->id,
        ]);
    }
}
