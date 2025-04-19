<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillingAddressRequest;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\LicenseRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends BaseController
{
    /**
     * Liste les clients.
     * Seuls les agents et les administrateurs peuvent accéder à cette méthode.
     *
     * @param Request $request La requête HTTP qui contient les paramètres de filtrage.
     */
    public function index(Request $request): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Customer::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $filters = $request->query('filter', []);

        $customers = Customer::query()
            ->when(isset($filters['first_name']), fn($query) => $query->where('first_name', 'like', '%' . $filters['first_name'] . '%'))
            ->when(isset($filters['email']), fn($query) => $query->where('email', 'like', '%' . $filters['email'] . '%'))
            ->when(isset($filters['phone']), fn($query) => $query->where('phone', 'like', '%' . $filters['phone'] . '%'))
            ->when(isset($filters['rental_id']), fn($query) => $query->where('rental_id', $filters['rental_id']))
            ->get();

        $success = new CustomerCollection($customers);
        return $this->sendResponse($success, "Liste des clients retrouvées avec succès.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());
        $success = new CustomerResource($customer);
        return $this->sendResponse($success, "Le client a été créé avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Associe le client à son permis de conduire.
     *
     * @param LicenseRequest $request La requête HTTP contenant les données du permis de conduire.
     * @param string $id L'identifiant du client.
     */
    public function addLicense(LicenseRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        $customer->license()->create($request->validated());

        return $this->sendResponse([], "Le permis de conduire a été associé au client " . $id . " avec succès.");
    }

    /**
     * Ajoute les informations de facturation du client.
     *
     * @param BillingAddressRequest $request La requête HTTP contenant les informations de facturation.
     * @param string $id L'identifiant du client.
     */
    public function addBillingAddress(BillingAddressRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        $customer->num_bill = $request->validated()['num'];
        $customer->street_bill = $request->validated()['street'];
        $customer->zip_bill = $request->validated()['zip'];
        $customer->city_bill = $request->validated()['city'];
        $customer->country_bill = $request->validated()['country'];
        $customer->save();

        return $this->sendResponse([], "L'adresse de facturation a été ajoutée au client avec succès.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
