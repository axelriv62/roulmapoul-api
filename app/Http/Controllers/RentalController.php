<?php

namespace App\Http\Controllers;

use App\Enums\RentalState;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\RentalResource;
use App\Models\Rental;
use App\Repositories\CarRepository;
use App\Repositories\RentalRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends BaseController
{
    /**
     * Crée une nouvelle location.
     */
    public function store(RentalRequest $request): JsonResponse // TODO Ajouter l'état réservé à la voiture si elle est actuellement available
    {
        if (! CarRepository::isRentable($request->input('car_plate'), Carbon::parse($request->input('start')), Carbon::parse($request->input('end')))) {
            return $this->sendError([], "La voiture n'est pas disponible à ces dates.");
        }

        $rental = Rental::create(array_merge(
            $request->validated(),
            [
                'nb_days' => Carbon::parse($request->input('start'))->diffInDays(Carbon::parse($request->input('end'))),
                'state' => RentalState::PAID,
            ]
        ));

        if ($request->has('options')) {
            $rental->options()->attach($request->input('options'));
        }

        $rental->total_price = RentalRepository::calculateTotalPrice($rental);
        $rental->save();

        $success['rental'] = new RentalResource($rental);

        return $this->sendResponse($success, 'La location a été créée avec succès.');
    }

    /**
     * Liste les locations.
     */
    public function index(Request $request): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Rental::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $state = $request->query('state', []);
        $start = $request->query('start', []);
        $end = $request->query('end', []);

        $rentals = Rental::query()
            ->when($state, fn ($query) => $query->where('state', $state))
            ->when($start, fn ($query) => $query->where('start', '>=', $start))
            ->when($end, fn ($query) => $query->where('end', '<=', $end))
            ->get();

        $success['rentals'] = RentalResource::collection($rentals);

        return $this->sendResponse($success, 'Liste des locations retrouvées avec succès.');
    }

    /**
     * Liste les locations d'un client.
     *
     * @param  string  $id  L'identifiant du client.
     */
    public function indexOfCustomer(string $id): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Rental::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $rentals = Rental::where('customer_id', $id)->get();
        $success[] = RentalResource::collection($rentals);

        return $this->sendResponse($success, 'Liste des locations retrouvées avec succès.');
    }

    /**
     * Liste les locations d'une agence.
     *
     * @param  string  $id  L'identifiant de l'agence.
     */
    public function indexOfAgency(string $id): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Rental::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $rentals = Rental::whereHas('car', function ($query) use ($id) {
            $query->where('agency_id', $id);
        })->get();

        $success['rentals'] = RentalResource::collection($rentals);

        return $this->sendResponse($success, 'Liste des locations retrouvées avec succès.');
    }

    /**
     * Liste les locations d'une voiture
     *
     * @param  string  $plate  La plaque d'immatriculation de la voiture.
     */
    public function indexOfCar(string $plate): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Rental::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $rentals = Rental::where('car_plate', mb_strtoupper($plate))->get();
        $success['rentals'] = RentalResource::collection($rentals);

        return $this->sendResponse($success, 'Liste des locations retrouvées avec succès.');
    }

    /**
     * Affiche les détails d'une location.
     *
     * @param  string  $id  L'identifiant de la location.
     */
    public function show(string $id): JsonResponse
    {
        $rental = Rental::findOrFail($id);

        if (Auth::user()->cannot('read', $rental)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $success['rental'] = new RentalResource($rental);

        return $this->sendResponse($success, 'Location retrouvée avec succès.');
    }

    /**
     * Met à jour une location.
     *
     * @param  RentalRequest  $request  La requête HTTP contenant les données de la location.
     * @param  string  $id  L'identifiant de la location.
     */
    public function update(RentalRequest $request, string $id): JsonResponse
    {
        $rental = Rental::findOrFail($id);

        if (Auth::user()->cannot('update', $rental)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        if (! RentalRepository::isUpdatable($rental, Carbon::parse($request->input('start')), Carbon::parse($request->input('end')))) {
            return $this->sendError([], "La voiture n'est pas disponible à ces dates.");
        }

        $rental->update($request->validated());

        if ($request->has('options')) {
            $rental->options()->sync($request->input('options'));
        }

        $rental->total_price = RentalRepository::calculateTotalPrice($rental);
        $rental->save();

        $success['rental'] = new RentalResource($rental);

        return $this->sendResponse($success, 'Location mise à jour avec succès.');
    }

    /**
     * Supprime une location.
     *
     * @param  string  $id  L'identifiant de la location.
     */
    public function destroy(string $id): JsonResponse
    {
        $rental = Rental::findOrFail($id);

        if (Auth::user()->cannot('delete', $rental)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        if (! RentalRepository::isDeletable($rental)) {
            return $this->sendError([], 'La location ne peut pas être annulée car elle est déjà en cours, finie ou a déjà été annulée.');
        }

        $rental->state = RentalState::CANCELED;
        $rental->save();

        $success['rental'] = new RentalResource($rental);

        return $this->sendResponse($success, 'Location annulée avec succès.');
    }
}
