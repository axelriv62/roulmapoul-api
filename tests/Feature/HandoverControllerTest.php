<?php

namespace Tests\Feature;

use App\Enums\CarCondition;
use App\Enums\Role;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\License;
use App\Models\Option;
use App\Models\Rental;
use App\Models\User;
use App\Models\Warranty;
use App\Models\Withdrawal;
use App\Repositories\RentalRepository;
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

        $options = Option::factory(2)->create();
        $warranty = Warranty::factory()->create();
        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
            'warranty_id' => $warranty->id,
        ]);
        $rental->options()->attach($options->pluck('id')->toArray());
        $rental->total_price = RentalRepository::calculateTotalPrice($rental);
        $rental->save();

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

        $options = Option::factory(2)->create();
        $warranty = Warranty::factory()->create();
        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'start' => now(),
            'end' => now()->addDays(3),
            'nb_days' => 3,
            'customer_id' => $this->customerUser->customer->id,
            'warranty_id' => $warranty->id,
        ]);
        $rental->options()->attach($options->pluck('id')->toArray());
        $rental->total_price = RentalRepository::calculateTotalPrice($rental);
        $rental->save();

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
