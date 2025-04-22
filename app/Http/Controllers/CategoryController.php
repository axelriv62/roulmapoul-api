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
     * @param  Request  $request  La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des catégories.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::all();
        $success['categories'] = new CategoryCollection($categories);

        return $this->sendResponse($success, 'Liste des catégories retrouvées avec succès.');
    }
}
