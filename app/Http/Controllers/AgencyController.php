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
        $sort = $request->query('sort');

        $agencies = Agency::query()
            ->when(isset($filters['city']), fn($query) => $query->where('city', 'like', '%' . $filters['city'] . '%'))
            ->when(isset($filters['zip']), fn($query) => $query->where('zip', 'like', '%' . $filters['zip'] . '%'))
            ->when($sort,
                fn($query) => $query->orderBy('name', $sort), // Trier par nom s'il y a un paramètre de tri
                fn($query) => $query->orderBy('id', 'asc') // Trier par ID par défaut
            )
            ->get();

        $success = new AgencyCollection($agencies);
        return $this->sendResponse($success, 'Agences récupérées avec succès.');
    }
}
