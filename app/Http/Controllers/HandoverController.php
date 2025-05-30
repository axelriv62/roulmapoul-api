<?php

namespace App\Http\Controllers;

use App\Enums\CarAvailability;
use App\Enums\CarCondition;
use App\Enums\DocumentType;
use App\Enums\RentalState;
use App\Http\Requests\AmendmentsRequest;
use App\Http\Requests\HandoverRequest;
use App\Http\Resources\AmendmentResource;
use App\Http\Resources\HandoverResource;
use App\Jobs\MailBillJob;
use App\Jobs\MailHandoverJob;
use App\Models\Amendment;
use App\Models\Document;
use App\Models\Handover;
use App\Models\Rental;
use Dompdf\Dompdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        if ($rental->withdrawal->datetime->diffInDays($request->input('datetime')) < 0) {
            return $this->sendError('Erreur', 'La date de retour ne peut pas être antérieure à la date de retrait.', 422);
        }

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
     * @param  AmendmentsRequest  $request  Les informations des avenants.
     * @param  string  $id  L'identifiant de la réservation.
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
            $this->generateDocuments($rental);
            MailHandoverJob::dispatch($rental->handover, $rental->customer);
            MailBillJob::dispatch($rental, $rental->customer);

            return $this->sendResponse([], 'Aucun avenant à ajouter.');
        }

        foreach ($request->input('amendments') as $amendment) {
            $rental->amendments()->create($amendment);
        }

        $rental->total_price += $rental->amendments()->sum('price');
        $rental->save();

        $this->generateDocuments($rental);
        MailHandoverJob::dispatch($rental->handover, $rental->customer);
        MailBillJob::dispatch($rental, $rental->customer);

        $success['amendments'] = AmendmentResource::collection($rental->amendments);

        return $this->sendResponse($success, 'Avenants ajoutés avec succès.');
    }

    /**
     * Génère les documents de retour et de facture pour une réservation.
     *
     * @param  Rental  $rental  La réservation concernée.
     */
    private function generateDocuments(Rental $rental): void
    {
        $this->generateDocument(
            'handover',
            ['handover' => $rental->handover],
            'docs/handover_'.$rental->customer->id.'_'.$rental->handover->id.'.pdf',
            DocumentType::HANDOVER->value,
            $rental
        );

        $this->generateDocument(
            'bill',
            ['rental' => $rental],
            'docs/bill_'.$rental->customer->id.'_'.$rental->id.'.pdf',
            DocumentType::BILL->value,
            $rental
        );
    }

    /**
     * Génère un document PDF et l'enregistre.
     *
     * @param  string  $view  La vue utilisée pour générer le PDF.
     * @param  array  $data  Les données à passer à la vue.
     * @param  string  $filePath  Le chemin du fichier à enregistrer.
     * @param  string  $type  Le type de document.
     * @param  Rental  $rental  La réservation concernée.
     */
    private function generateDocument(string $view, array $data, string $filePath, string $type, Rental $rental): void
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml(view('pdf.'.$view, $data));
        $dompdf->setPaper('A4');
        $dompdf->render();

        Storage::put($filePath, $dompdf->output());

        $rental->documents()->create([
            'type' => $type,
            'url' => $filePath,
        ]);
    }
}
