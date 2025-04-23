<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Repositories\CarRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends BaseController
{
    /**
     * Liste les voitures.
     *
     * @param  Request  $request  La requête HTTP qui contient les paramètres de filtrage.
     * @return JsonResponse La réponse JSON contenant la liste des voitures.
     */
    public function index(Request $request): JsonResponse
    {
        $category_id = $request->query('category_id');
        $availability = $request->query('availability');

        $cars = Car::query()
            ->when($category_id, fn ($query) => $query->where('category_id', $category_id))
            ->when($availability, fn ($query) => $query->where('availability', $availability))
            ->get();

        $success['cars'] = CarResource::collection($cars);

        return $this->sendResponse($success, 'Voitures récupérées avec succès.');
    }

    /**
     * Liste les voitures d'une agence spécifique.
     *
     * @param  Request  $request  La requête HTTP qui contient les paramètres de filtrage.
     * @param  string  $id  L'identifiant de l'agence.
     * @return JsonResponse La réponse JSON contenant la liste des voitures de l'agence.
     */
    public function indexAgency(Request $request, string $id): JsonResponse
    {
        $category_id = $request->query('category_id');
        $availability = $request->query('availability');
        $start = $request->query('start');
        $end = $request->query('end');

        $cars = Car::query()
            ->where('agency_id', $id)
            ->when($category_id, fn ($query) => $query->where('category_id', $category_id))
            ->when($availability, fn ($query) => $query->where('availability', $availability))
            ->get();

        if ($start && $end) {
            $start = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();

            $cars = $cars->filter(function ($car) use ($start, $end) {
                return CarRepository::isRentable($car->plate, $start, $end);
            });
        }

        $success['cars'] = CarResource::collection($cars);

        return $this->sendResponse($success, 'Voitures récupérées avec succès.');
    }
}
