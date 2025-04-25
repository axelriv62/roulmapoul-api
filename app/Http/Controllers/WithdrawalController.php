<?php

namespace App\Http\Controllers;

use App\Enums\CarAvailability;
use App\Enums\DocumentType;
use App\Enums\RentalState;
use App\Http\Requests\WithdrawalRequest;
use App\Http\Resources\WithdrawalResource;
use App\Jobs\MailWithdrawalJob;
use App\Models\Rental;
use App\Models\Withdrawal;
use Dompdf\Dompdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WithdrawalController extends BaseController
{
    /**
     * Crée un retrait pour une réservation.
     *
     * @param  WithdrawalRequest  $request  Les informations de la demande de retrait.
     * @param  string  $id  L'identifiant de la réservation.
     * @return JsonResponse La réponse contenant les informations du retrait créé.
     */
    public function store(WithdrawalRequest $request, string $id): JsonResponse
    {
        if (Auth::user()->cannot('create', Withdrawal::class)) {
            return $this->sendError('Non autorisé', 'Vous n\'êtes pas autorisé à effectuer cette opération', 403);
        }

        $rental = Rental::findOrFail($id);

        if ($rental->withdrawal) {
            return $this->sendError('Erreur', 'Un retrait a déjà été effectué pour cette réservation.', 422);
        }

        $withdrawal = Withdrawal::create(array_merge($request->validated(), [
            'rental_id' => $rental->id,
            'customer_id' => $rental->customer_id,
        ]));

        $rental->state = RentalState::ONGOING;
        $rental->save();

        $rental->car->availability = CarAvailability::RENTED;
        $rental->car->save();

        $dompdf = new Dompdf;
        $dompdf->loadHtml(view('pdf.withdrawal', ['withdrawal' => $withdrawal]));
        $dompdf->setPaper('A4');
        $dompdf->render();

        $filePath = 'docs/withdrawal_'.$rental->customer->id.'_'.$withdrawal->id.'.pdf';
        Storage::put($filePath, $dompdf->output());

        $rental->documents()->create([
            'type' => DocumentType::WITHDRAWAL,
            'url' => $filePath,
        ]);

        MailWithdrawalJob::dispatch($withdrawal, $rental->customer);
        $success['withdrawal'] = new WithdrawalResource($withdrawal);

        return $this->sendResponse($success, 'Le retrait a été créé avec succès.');
    }
}
