<?php

namespace Tests\Feature;

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

class WithdrawalControllerTest extends TestCase
{
    protected User $admin;

    protected User $agent;

    protected User $customerUser;

    /**
     * Vérifie que le retrait d'un véhicule fonctionne correctement.
     */
    public function test_withdrawal(): void
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

        $response = $this->withHeader('Accept', 'application/json')->post(route('withdrawals.store', $rental->id), [
            'datetime' => Carbon::createFromFormat('Y-m-d H:i:s', $rental->start)->format('Y-m-d H:i:s'),
            'car_plate' => $car->plate,
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => 'Clean',
            'exterior_condition' => 'Clean',
            'comment' => 'Roule that poule',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Withdrawal::count() > 0);
        $this->assertTrue(Rental::all()->first()->withdrawal->exists);
    }

    /**
     * Vérifie qu'un client ne peut pas effectuer un retrait.
     */
    public function test_customer_cannot_withdraw(): void
    {
        $this->actingAs($this->customerUser);

        $response = $this->withHeader('Accept', 'application/json')->post(route('withdrawals.store', 0));

        $response->assertStatus(403);
    }

    /**
     * Vérifie qu'un agent ne peut pas effectuer un retrait sur une réservation déjà retirée.
     */
    public function test_agent_cannot_withdraw_twice(): void
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

        $response = $this->withHeader('Accept', 'application/json')->post(route('withdrawals.store', $rental->id), [
            'datetime' => Carbon::createFromFormat('Y-m-d H:i:s', $rental->start)->format('Y-m-d H:i:s'),
            'car_plate' => $car->plate,
            'mileage' => 102,
            'fuel_level' => 50,
            'interior_condition' => 'Clean',
            'exterior_condition' => 'Clean',
            'comment' => 'Roule that poule',
        ]);

        $response->assertStatus(422);
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
