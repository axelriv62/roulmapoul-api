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
        $options = Option::all();
        $success['options'] = new OptionCollection($options);
        return $this->sendResponse($success, "Liste des options retrouvées avec succès.");
    }
}
