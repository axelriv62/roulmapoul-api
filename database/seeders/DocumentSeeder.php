<?php

namespace Database\Seeders;

use App\Enums\DocumentType;
use App\Enums\RentalState;
use App\Models\Rental;
use Dompdf\Dompdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentals = Rental::all();

        foreach ($rentals as $rental) {
            if ($rental->withdrawal) {
                $dompdf = new Dompdf;
                $dompdf->loadHtml(view('pdf.withdrawal', ['withdrawal' => $rental->withdrawal]));
                $dompdf->setPaper('A4');
                $dompdf->render();

                $filePath = 'docs/withdrawal_'.$rental->customer->id.'_'.$rental->withdrawal->id.'.pdf';
                Storage::put($filePath, $dompdf->output());

                $rental->documents()->create([
                    'type' => DocumentType::WITHDRAWAL,
                    'url' => $filePath,
                ]);
            }
            if ($rental->handover) {
                $dompdf = new Dompdf;
                $dompdf->loadHtml(view('pdf.handover', ['handover' => $rental->handover]));
                $dompdf->setPaper('A4');
                $dompdf->render();
                $filePath = 'docs/handover_'.$rental->customer->id.'_'.$rental->handover->id.'.pdf';
                Storage::put($filePath, $dompdf->output());

                $rental->documents()->create([
                    'type' => DocumentType::HANDOVER,
                    'url' => $filePath,
                ]);

            }
            if ($rental->state == RentalState::COMPLETED->value) {
                $dompdf = new Dompdf;
                $dompdf->loadHtml(view('pdf.bill', ['rental' => $rental]));
                $dompdf->setPaper('A4');
                $dompdf->render();
                $filePath = 'docs/bill_'.$rental->customer->id.'_'.$rental->id.'.pdf';
                Storage::put($filePath, $dompdf->output());

                $rental->documents()->create([
                    'type' => DocumentType::BILL,
                    'url' => $filePath,
                ]);
            }
        }
    }
}
