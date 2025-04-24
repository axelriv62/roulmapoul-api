<?php

namespace Tests\Feature;

use App\Enums\RentalState;
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

    public function test_index_rental(): void
    {
        // Se connecter en tant qu'agent avant
        $this->actingAs($this->agent);
        // Executer la requete
        $response = $this->withHeader('Accept', 'application/json')->get(route('rentals.index'));
        // Verifier qu'on récupère bien 200 (accepted) en status
        $response->assertStatus(200);
    }

    public function test_index_of_customer(): void
    {
        // Se connecter en tant qu'agent
        $this->actingAs($this->agent);

        // Utiliser l'ID du client existant créé dans setUp()
        $customerId = $this->customerUser->customer->id;

        // Executer la requete
        $response = $this->withHeader('Accept', 'application/json')
            ->get(route('rentals.index-customer', ['id' => $customerId]));

        // Verifier qu'on récupère bien 200 (accepted) en status
        $response->assertStatus(200);
    }

    public function test_index_of_agencies(): void
    {
        // Se connecter en tant qu'agent
        $this->actingAs($this->agent);

        // Créer une agence pour le test
        $agency = Agency::factory()->create();

        // Créer d'abord une catégorie de voiture
        $category = Category::factory()->create();

        // réer une voiture avec tous les champs requis
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        // Créer un client pour la location
        $customer = Customer::factory()->create([
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean.dupont@example.com',
        ]);

        // Créer une location associée à cette voiture et ce client
        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'customer_id' => $customer->id,
        ]);

        // Exécuter la requête
        $response = $this->withHeader('Accept', 'application/json')
            ->get(route('rentals.index-agency', ['id' => $agency->id]));

        // Vérifier qu'on récupère bien 200 (accepted) en status
        $response->assertStatus(200);

        // vérifier que la réponse contient bien la location
        $response->assertJsonFragment([
            'id' => $rental->id,
        ]);
    }

    public function test_index_of_car(): void
    {
        // Se connecter en tant qu'agent
        $this->actingAs($this->agent);

        // Créer une agence
        $agency = Agency::factory()->create();

        // Créer une catégorie
        $category = Category::factory()->create();

        // Créer une voiture
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
            'plate' => 'AB123CD',
        ]);

        // Créer un client
        $customer = Customer::factory()->create([
            'first_name' => 'Pierre',
            'last_name' => 'Martin',
            'email' => 'pierre.martin@example.com',
        ]);

        // Créer plusieurs locations pour cette voiture
        $rental1 = Rental::factory()->create([
            'car_plate' => $car->plate,
            'customer_id' => $customer->id,
        ]);

        $rental2 = Rental::factory()->create([
            'car_plate' => $car->plate,
            'customer_id' => $customer->id,
        ]);

        // Créer une location pour une autre voiture (ne devrait pas apparaître dans les résultats)
        $otherCar = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        //
        Rental::factory()->create([
            'car_plate' => $otherCar->plate,
            'customer_id' => $customer->id,
        ]);

        // Exécuter la requête
        $response = $this->withHeader('Accept', 'application/json')
            ->get("/api/rentals/car/{$car->plate}");

        // Vérifier le statut de la réponse
        $response->assertStatus(200);

        // Vérifier que la réponse contient les bonnes locations
        $response->assertJsonCount(2, 'data.rentals');

        // Vérifier que les IDs des locations correspondent
        $response->assertJsonFragment(['id' => $rental1->id]);
        $response->assertJsonFragment(['id' => $rental2->id]);

        // Test avec une plaque en minuscules (devrait fonctionner grâce à mb_strtoupper)
        $response = $this->withHeader('Accept', 'application/json')
            ->get('/api/rentals/car/'.strtolower($car->plate));

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data.rentals');
    }

    public function test_index_of_car_unauthorized(): void
    {
        // Créer et se connecter en tant qu'utilisateur
        $unauthorizedUser = User::factory()->create();
        $this->actingAs($unauthorizedUser);

        // Exécuter la requête
        $response = $this->withHeader('Accept', 'application/json')
            ->get('/api/rentals/car/AB123CD');

        // Vérifier que l'accès est refusé
        $response->assertStatus(403);
    }

    public function test_show_rental(): void
    {
        // Se connecter en tant qu'agent
        $this->actingAs($this->agent);

        // Créer une agence
        $agency = Agency::factory()->create();

        // Créer une catégorie
        $category = Category::factory()->create();

        // Créer une voiture
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        // Créer une location
        $rental = Rental::factory()->create([
            'car_plate' => $car->plate,
            'customer_id' => $this->customerUser->customer->id,
        ]);

        // Exécuter la requête
        $response = $this->withHeader('Accept', 'application/json')
            ->get(route('rentals.show', ['id' => $rental->id]));

        // Vérifier que le status vaut 200 (accepted)
        $response->assertStatus(200);

    }

    public function test_update_rental(): void
    {
        // Se connecter en tant qu'agent
        $this->actingAs($this->agent);

        // Créer une agence
        $agency = Agency::factory()->create();

        // Créer une catégorie
        $category = Category::factory()->create();

        // Créer une voiture
        $car = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
            'plate' => 'AB123CD',
        ]);

        // Créer un client
        $customer = Customer::factory()->create([
            'first_name' => 'Pierre',
            'last_name' => 'Martin',
            'email' => 'pierre.martin@example.com',
        ]);

        // Créer plusieurs locations pour cette voiture
        $rental1 = Rental::factory()->create([
            'car_plate' => $car->plate,
            'customer_id' => $customer->id,
        ]);

        $rental2 = Rental::factory()->create([
            'car_plate' => $car->plate,
            'customer_id' => $customer->id,
        ]);

        // Créer une location pour une autre voiture (ne devrait pas apparaître dans les résultats)
        $otherCar = Car::factory()->create([
            'agency_id' => $agency->id,
            'category_id' => $category->id,
        ]);

        //
        Rental::factory()->create([
            'car_plate' => $otherCar->plate,
            'customer_id' => $customer->id,
        ]);

        // Modifier la location
        $response = $this->withHeader('Accept', 'application/json')
            ->put(route('rentals.update', ['id' => $rental1->id]), [
                'customer_id' => $customer->id,
                'car_plate' => $car->plate,
                'start' => now()->addDay()->format('Y-m-d'),
                'end' => now()->addDays(5)->format('Y-m-d'),
                'warranty_id' => $rental1->warranty_id,
                'options' => $rental1->options->pluck('id')->toArray(),
            ]);

        // Vérifier que le status vaut 200 (accepted)
        $response->assertStatus(200);

    }

    public function test_destroy_rental(): void
    {
        $this->actingAs($this->admin);

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
            'customer_id' => $this->customerUser->customer->id,
            'warranty_id' => $warranty->id,
            'state' => RentalState::PAID,
            'start' => now()->addDays(2)->format('Y-m-d'), // Date de début dans le futur
            'end' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $rental->options()->attach($options->pluck('id'));

        $response = $this->withHeader('Accept', 'application/json')
            ->delete(route('rentals.delete', ['id' => $rental->id]));

        $response->assertStatus(200);

        // Vérifier que l'état a été changé à CANCELED au lieu de vérifier la suppression
        $this->assertDatabaseHas('rentals', [
            'id' => $rental->id,
            'state' => RentalState::CANCELED,
        ]);
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
