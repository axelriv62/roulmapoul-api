<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionRequest;
use App\Http\Resources\OptionCollection;
use App\Http\Resources\OptionResource;
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

    /**
     * Crée une nouvelle option.
     *
     * @param OptionRequest $request La requête HTTP contenant les données de l'option à créer.
     * @return JsonResponse La réponse JSON contenant l'option créée.
     */
    public function store(OptionRequest $request): JsonResponse
    {
        if (auth()->user()->cannot('create', Option::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $option = Option::create($request->validated());
        $success = new OptionResource($option);
        return $this->sendResponse($success, "L'option a été créée avec succès.");
    }
}
