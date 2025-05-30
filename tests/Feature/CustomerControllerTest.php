<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Category;
use App\Models\Customer;
use App\Models\License;
use App\Models\Rental;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\isEmpty;

class CustomerControllerTest extends TestCase
{
    protected User $admin;

    protected User $agent;

    protected User $customerUser;

    /**
     * Vérifie que la liste des clients est accessible en étant agent.
     */
    public function test_index_accessible_as_agent(): void
    {
        $this->actingAs($this->agent);
        $response = $this->withHeader('Accept', 'application/json')->get(route('customers.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que la liste des clients est accessible en étant admin.
     */
    public function test_index_accessible_as_admin(): void
    {
        $this->actingAs($this->admin);
        $response = $this->withHeader('Accept', 'application/json')->get(route('customers.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que la liste des clients est inaccessible en étant client.
     */
    public function test_index_inaccessible_as_customer(): void
    {
        $this->actingAs($this->customerUser);
        $response = $this->withHeader('Accept', 'application/json')->get(route('customers.index'));
        $response->assertStatus(403);
    }

    /**
     * Vérifie que la liste des clients est inaccessible sans authentification.
     */
    public function test_index_inaccessible_without_authentication(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->get(route('customers.index'));
        $response->assertStatus(401);
    }

    /**
     * Vérifie que les filtres fonctionnent correctement.
     */
    public function test_filter_index(): void
    {
        $this->actingAs($this->agent);
        Customer::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
        ]);
        $response = $this->withHeader('Accept', 'application/json')->get(route('customers.index', [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
        ]));
        $response->assertJsonCount(1, 'data.customers');
    }

    /**
     * Vérifie que la récupération de client par identifiant de location fonctionne.
     */
    public function test_filter_index_by_rental(): void
    {
        $this->actingAs($this->agent);
        $customer = Customer::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
        ]);

        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);
        Rental::factory()->create([
            'customer_id' => $this->customerUser->customer->id,
            'car_plate' => $car->plate,
        ]);
        Rental::factory()->create([
            'customer_id' => $customer->id,
            'car_plate' => $car->plate,
        ]);

        $response = $this->withHeader('Accept', 'application/json')->get(route('customers.index', [
            'rental_id' => $customer->rentals->first->id,
        ]));

        $response->assertJsonCount(1, 'data.customers');
    }

    /**
     * Vérifie que la création d'un client fonctionne.
     */
    public function test_create_valid_customer(): void
    {
        $this->actingAs($this->agent);

        $this->withHeader('Accept', 'application/json')->post(route('customers.store'), [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
            'num' => '1',
            'street' => 'Rue de la Paix',
            'zip' => '00000',
            'city' => 'Paris',
            'country' => 'France',
        ]);

        $customer = Customer::where('first_name', 'Test')->first();

        $this->withHeader('Accept', 'application/json')->post(route('customers.add-license', $customer->id), [
            'num' => '12345678912',
            'birthday' => '1980-01-01',
            'acquirement_date' => '2000-01-01',
            'distribution_date' => '2000-01-02',
            'country' => 'France',
        ]);

        $this->withHeader('Accept', 'application/json')->post(route('customers.add-billing-addr', $customer->id), [
            'num' => '2',
            'street' => 'Rue de la Liberté',
            'zip' => '00001',
            'city' => 'Lyon',
            'country' => 'France',
        ]);

        $response = $this->get(route('customers.index'));
        $response->assertJsonCount(2, 'data.customers');
        $this->assertTrue(Customer::where('first_name', 'Test')->exists());
    }

    /**
     * Vérifie que la liaison entre un client et un user fonctionne.
     */
    public function test_create_customer_user(): void
    {
        $this->actingAs($this->agent);

        $response = $this->withHeader('Accept', 'application/json')->post(route('customers.store'), [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
            'num' => '1',
            'street' => 'Rue de la Paix',
            'zip' => '00000',
            'city' => 'Paris',
            'country' => 'France',
        ]);
        $response->assertStatus(200);

        $customer = Customer::where('first_name', 'Test')->first();

        $response = $this->withHeader('Accept', 'application/json')->post(route('customers.add-license', $customer->id), [
            'num' => '111111111111',
            'birthday' => '1980-01-01',
            'acquirement_date' => '2000-01-01',
            'distribution_date' => '2000-01-02',
            'country' => 'France',
        ]);
        $response->assertStatus(200);

        $response = $this->withHeader('Accept', 'application/json')->post(route('customers.add-billing-addr', $customer->id), [
            'num' => '2',
            'street' => 'Rue de la Liberté',
            'zip' => '00001',
            'city' => 'Lyon',
            'country' => 'France',
        ]);
        $response->assertStatus(200);

        $response = $this->withHeader('Accept', 'application/json')->post(route('customers.register', $customer->id), [
            'name' => 'test',
            'password' => 'password',
        ]);
        $response->assertStatus(200);

        $customer = Customer::where('first_name', 'Test')->first();
        $user = User::where('name', 'test')->first();

        $this->assertTrue($customer->user_id === $user->id);
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

    /**
     * Vérifie que la mise à jour des infos d'un client fonctionne.
     */
    public function test_update_customer_infos_with_valid_mail(): void
    {
        $this->actingAs($this->customerUser);

        $response = $this->withHeader('Accept', 'application/json')->put(route('customers.update', $this->customerUser->customer->id), [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'gerard.martin@domain.fr',
            'phone' => '0606060606',
            'num' => '1',
            'street' => 'Rue de la Paix',
            'zip' => '00000',
            'city' => 'Paris',
            'country' => 'France',
        ]);
        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');
        $this->assertTrue(Customer::where('first_name', 'Test')->exists());
    }

    /**
     * Vérifie que la mise à jour des infos d'un client échoue si l'email existe déjà.
     */
    public function test_update_customer_infos_with_invalid_mail(): void
    {
        $this->actingAs($this->customerUser);

        Customer::factory()->create([
            'email' => 'test@domain.fr',
        ]);

        $response = $this->withHeader('Accept', 'application/json')->put(route('customers.update', $this->customerUser->customer->id), [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
            'num' => '1',
            'street' => 'Rue de la Paix',
            'zip' => '00000',
            'city' => 'Paris',
            'country' => 'France',
        ]);
        $response->assertStatus(422);

        $this->assertFalse(Customer::where('first_name', 'Test')->exists());
        $this->assertTrue(Customer::where('first_name', 'Gérard')->exists());
    }

    /**
     * Vérifie qu'un client ne peut pas modifier les infos d'un autre client.
     */
    public function test_update_other_customer_infos(): void
    {
        $this->actingAs($this->customerUser);

        $customer = Customer::factory()->create();

        $response = $this->withHeader('Accept', 'application/json')->put(route('customers.update', $customer->id), [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@domain.fr',
            'phone' => '0606060606',
            'num' => '1',
            'street' => 'Rue de la Paix',
            'zip' => '00000',
            'city' => 'Paris',
            'country' => 'France',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Vérifie que la mise à jour du permis d'un client fonctionne.
     */
    public function test_update_license(): void
    {
        $this->actingAs($this->customerUser);

        $response = $this->withHeader('Accept', 'application/json')->put(route('customers.update-license', $this->customerUser->customer->id), [
            'num' => '12345678912',
            'birthday' => '1980-01-01',
            'acquirement_date' => '2000-01-01',
            'distribution_date' => '2000-01-02',
            'country' => 'France',
        ]);
        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');
        $this->assertTrue(License::where('num', '12345678912')->exists());
    }

    /**
     * Vérifie que la mise à jour des informations de facturation d'un client fonctionne.
     */
    public function test_update_billing_address(): void
    {
        $this->actingAs($this->customerUser);

        $response = $this->withHeader('Accept', 'application/json')->put(route('customers.update-billing-addr', $this->customerUser->customer->id), [
            'num' => '2',
            'street' => 'Rue de la Liberté',
            'zip' => '00001',
            'city' => 'Lyon',
            'country' => 'France',
        ]);
        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');
        $this->assertTrue(Customer::where('city_bill', 'Lyon')->exists());
    }
}
