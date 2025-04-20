<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Liste les catégories.
     *
     * @param Request $request La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des catégories.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->query('filter', []);

        $categories = Category::query()
            ->when(isset($filters['name']), fn($query) => $query->where('name', 'like', '%' . $filters['name'] . '%'))
            ->when(isset($filters['description']), fn($query) => $query->where('description', 'like', '%' . $filters['description'] . '%'))
            ->get();

        $success = new CategoryCollection($categories);
        return $this->sendResponse($success, "Liste des catégories retrouvées avec succès.");
    }

    /**
     * Crée une nouvelle catégorie.
     *
     * @param CategoryRequest $request La requête HTTP contenant les données de la catégorie à créer.
     * @return JsonResponse La réponse JSON contenant les détails de la catégorie créée.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        if (auth()->user()->cannot('create', Category::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $category = Category::create($request->validated());
        $success = new CategoryResource($category);
        return $this->sendResponse($success, "La catégorie a été créée avec succès.");
    }

    /**
     * Modifie une catégorie existante.
     *
     * @param CategoryRequest $request La requête HTTP contenant les données de la catégorie à modifier.
     * @param string $id L'identifiant de la catégorie à modifier.
     * @return JsonResponse La réponse JSON contenant les détails de la catégorie modifiée.
     */
    public function update(CategoryRequest $request, string $id): JsonResponse
    {
        if (auth()->user()->cannot('update', Category::class)) {
            return $this->sendError('Non autorisé.', 'Vous n\'êtes pas autorisé à effectuer cette opération.', 403);
        }

        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('Non trouvé.', 'La catégorie demandée n\'existe pas.', 404);
        }

        $category->update($request->validated());
        $success = new CategoryResource($category);
        return $this->sendResponse($success, "La catégorie a été mise à jour avec succès.");
    }
}
