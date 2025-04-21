<?php

namespace App\Http\Controllers;

use App\Enums\RentalState;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\RentalCollection;
use App\Http\Resources\RentalResource;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends BaseController
{
    /**
     * Créer une nouvelle location.
     *
     * @param RentalRequest $request
     * @return JsonResponse
     */
    public function store(RentalRequest $request): JsonResponse
    {
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

        $rental->total_price += $rental->car->price_day * $rental->nb_days + ($rental->options->sum('price') ?? 0) + ($rental->warranty->price ?? 0);
        $rental->save();


        $success = new RentalResource($rental);

        return $this->sendResponse($success, "La location a été créée avec succès.");
    }

    /**
     * Lister les locations
     *
     * @param Request $request
     * @return JsonResponse
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
            ->when($state, fn($query) => $query->where('state', $state))
            ->when($start, fn($query) => $query->where('start', '>=', $start))
            ->when($end, fn($query) => $query->where('end', '<=', $end))
            ->get();

        $success = RentalResource::collection($rentals);
        return $this->sendResponse($success, "Liste des locations retrouvées avec succès.");
    }

    /**
     * Liste les locations d'un client.
     *
     * @param string $id L'identifiant du client.
     * @return JsonResponse
     */
    public function indexOfCustomer(string $id): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Rental::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $rentals = Rental::where('customer_id', $id)->get();
        $success = new RentalCollection($rentals);
        return $this->sendResponse($success, "Liste des locations retrouvées avec succès.");
    }

    /**
     * Liste les locations d'une agence.
     *
     * @param string $id L'identifiant de l'agence.
     * @return JsonResponse
     */
    public function indexOfAgency(string $id): JsonResponse
    {
        if (Auth::user()->cannot('readAny', Rental::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $rentals = Rental::whereHas('car', function ($query) use ($id) {
            $query->where('agency_id', $id);
        })->get();

        $success = new RentalCollection($rentals);
        return $this->sendResponse($success, "Liste des locations retrouvées avec succès.");
    }
}
