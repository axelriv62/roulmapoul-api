<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Customer;
use App\Models\License;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\isEmpty;

class CustomerControllerTest extends TestCase
{
    protected User $admin;

    protected User $agent;

    protected User $customer;

    /**
     * Vérifie que la liste des clients est accessible en étant agent.
     */
    public function test_index_accessible_as_agent(): void
    {
        $this->actingAs($this->agent);
        $response = $this->get(route('customers.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que la liste des clients est accessible en étant admin.
     */
    public function test_index_accessible_as_admin(): void
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('customers.index'));
        $response->assertStatus(200);
    }

    /**
     * Vérifie que la liste des clients est inaccessible en étant client.
     */
    public function test_index_inaccessible_as_customer(): void
    {
        $this->actingAs($this->customer);
        $response = $this->get(route('customers.index'));
        $response->assertStatus(403);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
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

        $this->customer = User::factory()->create([
            'name' => 'gerard.martin',
            'email' => $customer->email,
        ])->assignRole(Role::CUSTOMER->value);
        $customer->user_id = $this->customer->id;
        $customer->save();
    }
}
