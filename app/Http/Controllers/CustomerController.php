<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillingAddressRequest;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\LicenseRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\LicenseResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends BaseController
{
    /**
     * Liste les clients.
     *
     * @param  Request  $request  La requête HTTP qui contient les paramètres de filtrage.
     */
    public function index(Request $request): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Customer::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $first_name = $request->query('first_name');
        $last_name = $request->query('last_name');
        $email = $request->query('email');
        $phone = $request->query('phone');
        $rental_id = $request->query('rental_id');

        $customers = Customer::query()
            ->when($first_name, fn ($query) => $query->where('first_name', 'like', '%'.$first_name.'%'))
            ->when($last_name, fn ($query) => $query->where('last_name', 'like', '%'.$last_name.'%'))
            ->when($email, fn ($query) => $query->where('email', 'like', '%'.$email.'%'))
            ->when($phone, fn ($query) => $query->where('phone', 'like', '%'.$phone.'%'))
            ->when($rental_id, fn ($query) => $query->whereHas('rentals', fn ($q) => $q->where('id', $rental_id)))
            ->get();

        $success['customers'] = CustomerResource::collection($customers);

        return $this->sendResponse($success, 'Liste des clients retrouvées avec succès.');
    }

    /**
     * Crée un nouveau client
     *
     * @param  CustomerRequest  $request  requête de création du client.
     */
    public function store(CustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());
        $success['customer'] = new CustomerResource($customer);

        return $this->sendResponse($success, 'Le client a été créé avec succès.');
    }

    /**
     * Associe le client à son permis de conduire.
     *
     * @param  LicenseRequest  $request  La requête HTTP contenant les données du permis de conduire.
     * @param  string  $id  L'identifiant du client.
     */
    public function addLicense(LicenseRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $customer->license()->create($request->validated());

        return $this->sendResponse([], 'Le permis de conduire a été associé au client '.$id.' avec succès.');
    }

    /**
     * Ajoute les informations de facturation du client.
     *
     * @param  BillingAddressRequest  $request  La requête HTTP contenant les informations de facturation.
     * @param  string  $id  L'identifiant du client.
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
     * Met à jour les informations du client.
     *
     * @param  CustomerRequest  $request  La requête de mise à jour du client.
     * @param  string  $id  L'identifiant du client à modifier.
     */
    public function updateInfos(CustomerRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        if (Auth::user()->cannot('update', $customer)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $customer->update($request->validated());
        $success['customer'] = new CustomerResource($customer);

        return $this->sendResponse($success, 'Les informations du client ont été mises à jour avec succès.');
    }

    /**
     * Met à jour le permis du client.
     *
     * @param  LicenseRequest  $request  La requête de modification du permis.
     * @param  string  $id  Le client dont le permis est modifié
     */
    public function updateLicense(LicenseRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        if (Auth::user()->cannot('update', $customer)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $customer->license()->update($request->validated());
        $success['license'] = new LicenseResource($customer->license);

        return $this->sendResponse($success, 'Le permis de conduire a été mis à jour avec succès.');
    }

    /**
     * Met à jour les informations de facturation du client.
     *
     * @param  BillingAddressRequest  $request  La requête de modification des informations de facturation.
     * @param  string  $id  Le client dont les informations de facturation sont modifiées.
     */
    public function updateBillingAddress(BillingAddressRequest $request, string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        if (Auth::user()->cannot('update', $customer)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $customer->update([
            'num_bill' => $request->validated()['num'],
            'street_bill' => $request->validated()['street'],
            'zip_bill' => $request->validated()['zip'],
            'city_bill' => $request->validated()['city'],
            'country_bill' => $request->validated()['country'],
        ]);
        $success['customer'] = new CustomerResource($customer);

        return $this->sendResponse($success, "L'adresse de facturation a été mise à jour avec succès.");
    }

    /**
     * Détails les informations du client
     *
     * @param  string  $id  L'identifiant du client demandé.
     */
    public function show(string $id): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Customer::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $customer = Customer::findOrFail($id);
        $success['customer'] = new CustomerResource($customer);

        return $this->sendResponse($success, 'Client retrouvé avec succès.');
    }
}
