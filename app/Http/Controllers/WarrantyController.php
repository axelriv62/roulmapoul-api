<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarrantyResource;
use App\Models\Warranty;
use Illuminate\Http\JsonResponse;

class WarrantyController extends BaseController
{
    /**
     * Liste les garanties.
     *
     * @return JsonResponse La réponse JSON contenant la liste des garanties.
     */
    public function index(): JsonResponse
    {
        $warranties = Warranty::all();
        $success['warranties'] = WarrantyResource::collection($warranties);

        return $this->sendResponse($success, 'Garanties récupérées avec succès.');
    }
}
