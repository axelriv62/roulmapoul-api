<?php

namespace App\Http\Controllers;

use App\Http\Resources\AgencyCollection;
use App\Models\Agency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgencyController extends BaseController
{
    /**
     * Liste les agences.
     *
     * @param Request $request La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des agences.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->query('filter', []);

        $agencies = Agency::query()
            ->when(isset($filters['name']), fn($query) => $query->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(isset($filters['num']), fn($query) => $query->where('num', 'like', '%' . $filters['num'] . '%'))
            ->when(isset($filters['street']), fn($query) => $query->where('street', 'like', '%' . $filters['street'] . '%'))
            ->when(isset($filters['zip']), fn($query) => $query->where('zip', 'like', '%' . $filters['zip'] . '%'))
            ->when(isset($filters['city']), fn($query) => $query->where('city', 'like', '%' . $filters['city'] . '%'))
            ->when(isset($filters['country']), fn($query) => $query->where('country', 'like', '%' . $filters['country'] . '%'))
            ->get();

        $success = new AgencyCollection($agencies);
        return $this->sendResponse($success, 'Agences récupérées avec succès.');
    }
}
