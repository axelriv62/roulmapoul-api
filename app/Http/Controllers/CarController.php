<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarCollection;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends BaseController
{
    /**
     * Liste les voitures.
     *
     * @param Request $request La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des voitures.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->query('filter', []);

        $cars = Car::query()
            ->when(isset($filters['category']), fn($query) => $query->where('category_id', $filters['category']))
            ->when(isset($filters['availability']), fn($query) => $query->where('availability', $filters['availability']))
            ->get();

        $success = new CarCollection($cars);
        return $this->sendResponse($success, 'Voitures récupérées avec succès.');
    }

    /**
     * Liste les voitures d'une agence spécifique.
     *
     * @param Request $request La requête HTTP qui contient les paramètres de filtrage.
     * @param string $id L'identifiant de l'agence.
     * @return JsonResponse La réponse JSON contenant la liste des voitures de l'agence.
     */
    public function indexAgency(Request $request, string $id): JsonResponse
    {
        $filters = $request->query('filter', []);

        $cars = Car::query()
            ->where('agency_id', $id)
            ->when(isset($filters['category']), fn($query) => $query->where('category_id', $filters['category']))
            ->when(isset($filters['availability']), fn($query) => $query->where('availability', $filters['availability']))
            ->get();

        $success = new CarCollection($cars);
        return $this->sendResponse($success, 'Voitures récupérées avec succès.');
    }


}
