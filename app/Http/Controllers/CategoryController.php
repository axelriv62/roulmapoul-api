<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
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
}
