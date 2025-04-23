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
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\isEmpty;

class RentalControllerTest extends TestCase
{
    /**
     * Vérifier le bon fonctionnement de la création d'une réservation.
     */
    public function test_create_valid_rental(): void
    {
        $agency = Agency::factory()->create();
        $category = Category::factory()->create();
        $car = Car::factory()->create([
            'category_id' => $category->id,
            'agency_id' => $agency->id,
        ]);

        $options = Option::factory(2)->create();
        $warranty = Warranty::factory()->create();

        $response = $this->withHeader('Accept', 'application/json')->post(route('rentals.store'), [
            'customer_id' => $this->customerUser->customer->id,
            'car_plate' => $car->plate,
            'start' => now()->addDay()->format('Y-m-d'),
            'end' => now()->addDays(5)->format('Y-m-d'),
            'warranty_id' => $warranty->id,
            'options' => $options->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('rental_option', [
            'rental_id' => Rental::first()->id,
            'option_id' => $options[0]->id,
        ]);
        $this->assertDatabaseHas('rental_option', [
            'rental_id' => Rental::first()->id,
            'option_id' => $options[1]->id,
        ]);
    }

    public function test_index_rental(): void{
        //Se connecter en tant qu'agent avant
        $this->actingAs($this->agent);
        //Executer la requete
        $response = $this->withHeader('Accept', 'application/json')->get(route('rentals.index'));
        //Verifier qu'on récupère bien 200 (accepted) en status
        $response->assertStatus(200);
    }

    public function test_indexOfCustomer(): void
    {
        //Se connecter en tant qu'agent
        $this->actingAs($this->agent);

        //Utiliser l'ID du client existant créé dans setUp()
        $customerId = $this->customerUser->customer->id;

        //Executer la requete
        $response = $this->withHeader('Accept', 'application/json')
            ->get(route('rentals.index-customer', ['id' => $customerId]));

        //Verifier qu'on récupère bien 200 (accepted) en status
        $response->assertStatus(200);
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
