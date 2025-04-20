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
}
