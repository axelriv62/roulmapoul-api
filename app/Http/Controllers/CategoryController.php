<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseController
{
    /**
     * Liste les catégories.
     *
     * @return JsonResponse La réponse JSON contenant la liste des catégories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        $success['categories'] = CategoryResource::collection($categories);

        return $this->sendResponse($success, 'Liste des catégories retrouvées avec succès.');
    }
}
