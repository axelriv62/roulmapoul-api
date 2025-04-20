<?php

namespace App\Http\Controllers;

use App\Http\Resources\OptionCollection;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OptionController extends BaseController
{
    /**
     * Liste les options.
     *
     * @param Request $request La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des options.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->query('filter', []);

        $options = Option::query()
            ->when(isset($filters['name']), fn($query) => $query->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(isset($filters['description']), fn($query) => $query->where('description', 'like', '%' . $filters['description'] . '%'))
            ->when(isset($filters['price']), fn($query) => $query->where('price', $filters['price']))
            ->get();

        $success = new OptionCollection($options);
        return $this->sendResponse($success, "Liste des options retrouvées avec succès.");
    }
}
