<?php

namespace App\Http\Controllers;

use App\Http\Resources\AgencyResource;
use App\Models\Agency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgencyController extends BaseController
{
    /**
     * Liste les agences.
     *
     * @param  Request  $request  La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des agences.
     */
    public function index(Request $request): JsonResponse
    {
        $city = $request->query('city');
        $zip = $request->query('zip');
        $sort = $request->query('sort');

        if ($sort && ! ($sort === 'asc' || $sort === 'desc')) {
            $sort = 'asc';
        }

        $agencies = Agency::query()
            ->when($city, fn ($query) => $query->where('city', 'like', '%'.$city.'%'))
            ->when($zip, fn ($query) => $query->where('zip', 'like', '%'.$zip.'%'))
            ->when($sort,
                fn ($query) => $query->orderBy('name', $sort), // Trier par nom s'il y a un paramètre de tri
                fn ($query) => $query->orderBy('id', 'asc') // Trier par ID par défaut
            )
            ->get();

        $success['agencies'] = AgencyResource::collection($agencies);

        return $this->sendResponse($success, 'Agences récupérées avec succès.');
    }
}
