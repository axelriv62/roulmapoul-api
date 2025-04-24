<?php

namespace App\Http\Controllers;

use App\Enums\CarAvailability;
use App\Enums\CarCondition;
use App\Enums\RentalState;
use App\Http\Requests\AmendmentsRequest;
use App\Http\Requests\HandoverRequest;
use App\Http\Resources\HandoverResource;
use App\Jobs\MailHandoverJob;
use App\Models\Amendment;
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

        // TODO Vérifier que le retour est effectué après le retrait en terme de date

        $handover = Handover::create(array_merge($request->validated(), [
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]));

        $rental->state = RentalState::COMPLETED;
        $rental->save();

        $conditions = [$handover->interior_condition, $handover->exterior_condition];

        if (in_array(CarCondition::NEEDS_REPAIR->value, $conditions)) {
            $rental->car->availability = CarAvailability::UNDER_REPAIR;
        } elseif (in_array(CarCondition::NEEDS_MAINTENANCE->value, $conditions)) {
            $rental->car->availability = CarAvailability::UNDER_MAINTENANCE;
        } else {
            $rental->car->availability = $rental->car->rentals()->where('state', RentalState::PAID)->exists()
                ? CarAvailability::RESERVED
                : CarAvailability::AVAILABLE;
        }

        $rental->car->save();

        $success['handover'] = new HandoverResource($handover);

        return $this->sendResponse($success, 'Retour enregistré avec succès.');
    }

    /**
     * Ajoute les avenants à la réservation.
     *
     * @param AmendmentsRequest $request Les informations des avenants.
     * @param string $id L'identifiant de la réservation.
     * @return JsonResponse La réponse contenant les informations des avenants ajoutés.
     */
    public function addAmendments(AmendmentsRequest $request, string $id): JsonResponse
    {
        if (Auth::user()->cannot('create', Amendment::class)) {
            return $this->sendError('Non autorisé', 'Vous n\'êtes pas autorisé à effectuer cette opération', 403);
        }

        $rental = Rental::findOrFail($id);

        if (! $rental->handover) {
            return $this->sendError('Erreur', 'Aucun retour n\'a été effectué pour cette réservation.', 422);
        }

        if (! $request->has('amendments')) {
            MailHandoverJob::dispatch($rental->handover, $rental->customer);
            return $this->sendResponse([], 'Aucun avenant à ajouter.');
        }
    }
}
