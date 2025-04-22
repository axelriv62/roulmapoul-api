<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarrantyCollection;
use App\Models\Warranty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarrantyController extends BaseController
{
    /**
     * Liste les garanties.
     *
     * @param  Request  $request  La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des garanties.
     */
    public function index(Request $request): JsonResponse
    {
        $warranties = Warranty::all();
        $success['warranties'] = new WarrantyCollection($warranties);

        return $this->sendResponse($success, 'Garanties récupérées avec succès.');
    }
}
