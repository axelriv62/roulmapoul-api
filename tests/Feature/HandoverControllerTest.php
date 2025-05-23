<?php

namespace Tests\Feature;

use App\Enums\CarAvailability;
use App\Enums\CarCondition;
use App\Enums\RentalState;
use App\Enums\Role;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\License;
use App\Models\Rental;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\isEmpty;

class HandoverControllerTest extends TestCase
{
    protected User $admin;

    protected User $agent;

    protected User $customerUser;

    /**
     * Vérifie que le retour d'un véhicule fonctionne correctement.
     */
    public function test_handover(): void
    {
        $this->actingAs($this->agent);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
        ]);

        Withdrawal::factory()->create([
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer->id,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', $rental->id), [
            'datetime' => Carbon::createFromFormat('Y-m-d H:i:s', $rental->end)->format('Y-m-d H:i:s'),
            'car_plate' => $car->plate,
            'mileage' => $rental->withdrawal->mileage * 1.2,
            'fuel_level' => $rental->withdrawal->fuel_level * 0.7,
            'interior_condition' => CarCondition::NEEDS_MAINTENANCE->value,
            'exterior_condition' => CarCondition::NEEDS_REPAIR->value,
            'comment' => 'Caca partout + pipi sur le capot wtf',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Rental::all()->first()->handover->exists);
    }

    /**
     * Vérifie que le retour d'un véhicule échoue si le véhicule n'est pas retiré.
     */
    public function test_handover_without_withdrawal(): void
    {
        $this->actingAs($this->agent);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', $rental->id), [
            'datetime' => Carbon::createFromFormat('Y-m-d H:i:s', $rental->end)->format('Y-m-d H:i:s'),
            'car_plate' => $car->plate,
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => CarCondition::GOOD->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'comment' => 'Roule that poule',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Vérifie que seul un agent peut effectuer un retrait.
     */
    public function test_customer_cannot_handover(): void
    {
        $this->actingAs($this->customerUser);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', 0), [
            'datetime' => now()->format('Y-m-d H:i:s'),
            'car_plate' => 'ABC123',
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => CarCondition::GOOD->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'comment' => 'Roule that poule',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Vérifie que la voiture est marquée comme disponible après le retour.
     */
    public function test_handover_updates_car_availability_available(): void
    {
        $this->actingAs($this->agent);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'availability' => CarAvailability::RENTED,
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
        ]);

        Withdrawal::factory()->create([
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', $rental->id), [
            'datetime' => now()->format('Y-m-d H:i:s'),
            'car_plate' => 'ABC123',
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => CarCondition::GOOD->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'comment' => 'Roule that poule',
        ]);
        $response->assertStatus(200);

        $this->assertEquals(CarAvailability::AVAILABLE->value, Car::findOrFail($car->plate)->availability);
    }

    /**
     * Vérifie que la voiture est marquée comme réservée si elle a des réservations de prévue après le retour.
     */
    public function test_handover_updates_car_availability_reserved(): void
    {
        $this->actingAs($this->agent);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'availability' => CarAvailability::RENTED,
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
        ]);
        $rental->save();

        Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now()->addDays(4),
            'end' => now()->addDays(7),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
            'state' => RentalState::PAID->value,
        ]);

        Withdrawal::factory()->create([
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', $rental->id), [
            'datetime' => now()->format('Y-m-d H:i:s'),
            'car_plate' => 'ABC123',
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => CarCondition::GOOD->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'comment' => 'Roule that poule',
        ]);
        $response->assertStatus(200);

        $this->assertEquals(CarAvailability::RESERVED->value, Car::findOrFail($car->plate)->availability);
    }

    /**
     * Vérifie que la voiture est marquée comme en maintenance si elle a des réparations à faire.
     */
    public function test_handover_updates_car_availability_maintenance(): void
    {
        $this->actingAs($this->agent);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'availability' => CarAvailability::RENTED,
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
        ]);
        $rental->save();

        Withdrawal::factory()->create([
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', $rental->id), [
            'datetime' => now()->format('Y-m-d H:i:s'),
            'car_plate' => 'ABC123',
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => CarCondition::NEEDS_MAINTENANCE->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'comment' => 'Roule that poule',
        ]);
        $response->assertStatus(200);

        $this->assertEquals(CarAvailability::UNDER_MAINTENANCE->value, Car::findOrFail($car->plate)->availability);
    }

    /**
     * Vérifie que la voiture est marquée comme en réparation si elle a des réparations à faire.
     */
    public function test_handover_updates_car_availability_repair(): void
    {
        $this->actingAs($this->agent);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'availability' => CarAvailability::RENTED,
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
        ]);
        $rental->save();

        Withdrawal::factory()->create([
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->post(route('handovers.store', $rental->id), [
            'datetime' => now()->format('Y-m-d H:i:s'),
            'car_plate' => 'ABC123',
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => CarCondition::NEEDS_REPAIR->value,
            'exterior_condition' => CarCondition::GOOD->value,
            'comment' => 'Roule that poule',
        ]);
        $response->assertStatus(200);

        $this->assertEquals(CarAvailability::UNDER_REPAIR->value, Car::findOrFail($car->plate)->availability);
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (isEmpty(DB::select('SELECT * FROM roles'))) {
            $this->seed(RoleSeeder::class);
        }

        $this->admin = User::factory()->create()->assignRole(Role::ADMIN);

        $this->agent = User::factory()->create()->assignRole(Role::AGENT);

        $customer = Customer::factory()->create([
            'first_name' => 'Gérard',
            'last_name' => 'Martin',
            'email' => 'gerard.martin@domain.fr',
        ]);

        License::factory()->create([
            'num' => '12345678912',
            'birthday' => '1980-01-01',
            'acquirement_date' => '2000-01-01',
            'distribution_date' => '2000-01-09',
            'country' => 'Belgium',
            'customer_id' => $customer->id,
        ]);

        $this->customerUser = User::factory()->create([
            'name' => 'gerard.martin',
            'email' => $customer->email,
        ])->assignRole(Role::CUSTOMER->value);
        $customer->user_id = $this->customerUser->id;
        $customer->save();
    }
}
