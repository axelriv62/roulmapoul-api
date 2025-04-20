<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseController
{
    public function index(): JsonResponse
    {
        $success = new CategoryCollection(Category::all());
        return $this->sendResponse($success, "Liste des catégories retrouvées avec succès.");
    }
}
