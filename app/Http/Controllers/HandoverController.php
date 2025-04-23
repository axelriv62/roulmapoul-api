<?php

namespace App\Http\Controllers;

use App\Enums\CarAvailability;
use App\Enums\CarCondition;
use App\Enums\RentalState;
use App\Http\Requests\HandoverRequest;
use App\Http\Resources\HandoverResource;
use App\Models\Handover;
use App\Models\Rental;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HandoverController extends BaseController
{
    /**
     * Crée un retour pour une réservation.
     *
     * @param  HandoverRequest  $request  Les informations de la demande de retour.
     * @param  string  $id  L'identifiant de la location.
     * @return JsonResponse La réponse contenant les informations du retour créé.
     */
    public function store(HandoverRequest $request, string $id): JsonResponse
    {
        if (Auth::user()->cannot('create', Handover::class)) {
            return $this->sendError('Non autorisé', 'Vous n\'êtes pas autorisé à effectuer cette opération', 403);
        }

        $rental = Rental::findOrFail($id);

        if (! $rental->withdrawal) {
            return $this->sendError('Erreur', 'Aucun retrait n\'a été effectué pour cette réservation.', 422);
        }

        if ($rental->handover) {
            return $this->sendError('Erreur', 'Un retour a déjà été effectué pour cette réservation.', 422);
        }

        $handover = Handover::create(array_merge($request->validated(), [
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]));

        $rental->state = RentalState::COMPLETED;
        $rental->save();

        if ($rental->car->rentals()->exists()) {
            $rental->car->availability = CarAvailability::RESERVED;
        } else {
            $interiorCondition = $handover->interior_condition;
            $exteriorCondition = $handover->exterior_condition;

            if ($interiorCondition || $exteriorCondition) {
                if (in_array(CarCondition::NEEDS_REPAIR->value, [$interiorCondition, $exteriorCondition])) {
                    $rental->car->availability = CarAvailability::UNDER_REPAIR;
                } elseif (in_array(CarCondition::NEEDS_MAINTENANCE->value, [$interiorCondition, $exteriorCondition])) {
                    $rental->car->availability = CarAvailability::UNDER_MAINTENANCE;
                } else {
                    $rental->car->availability = CarAvailability::AVAILABLE;
                }
            }
        }
        $rental->car->save();

        $success['handover'] = new HandoverResource($handover);

        return $this->sendResponse($success, 'Retour enregistré avec succès.');
    }
}
