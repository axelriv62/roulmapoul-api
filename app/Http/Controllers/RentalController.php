<?php

namespace App\Http\Controllers;

use App\Enums\RentalState;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\RentalResource;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class RentalController extends BaseController
{
    /**
     * Créer une nouvelle location.
     *
     * @param RentalRequest $request
     * @return JsonResponse
     */
    public function store(RentalRequest $request): JsonResponse
    {
        $rental = Rental::create(array_merge(
            $request->validated(),
            [
                'nb_days' => Carbon::parse($request->input('start'))->diffInDays(Carbon::parse($request->input('end'))),
                'state' => RentalState::PAID,
            ]
        ));
        if ($request->has('options')) {
            $rental->options()->attach($request->input('options'));
        }
        
        $rental->total_price += $rental->car->price_day * $rental->nb_days + ($rental->options->sum('price') ?? 0) + ($rental->warranty->price ?? 0);
        $rental->save();


        $success = new RentalResource($rental);

        return $this->sendResponse($success, "La location a été créée avec succès.");
    }
}
