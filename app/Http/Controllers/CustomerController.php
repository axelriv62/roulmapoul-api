<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerCollection;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
}
