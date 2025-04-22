<?php

namespace App\Http\Controllers;

use App\Http\Resources\OptionResource;
use App\Models\Option;
use Illuminate\Http\JsonResponse;

class OptionController extends BaseController
{
    /**
     * Liste les options.
     *
     * @return JsonResponse La réponse JSON contenant la liste des options.
     */
    public function index(): JsonResponse
    {
        $options = Option::all();
        $success['options'] = OptionResource::collection($options);

        return $this->sendResponse($success, 'Liste des options retrouvées avec succès.');
    }
}
