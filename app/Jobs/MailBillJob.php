<?php

namespace App\Jobs;

use App\Enums\DocumentType;
use App\Mail\MailBill;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Rental;
use Dompdf\Dompdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailBillJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Rental $rental, private readonly Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml(view('pdf.bill', ['rental' => $this->rental]));
        $dompdf->setPaper('A4');
        $dompdf->render();
        $filePath = 'docs/bill_'.$this->customer->id.'_'.$this->rental->id.'.pdf';
        Storage::put($filePath, $dompdf->output());

        Document::create([
            'type' => DocumentType::BILL,
            'url' => $filePath,
            'rental_id' => $this->rental->id,
        ]);

        try {
            print_r("Envoi de l'email de facture à {$this->customer->email} pour la location {$this->rental->id}.\n");

            $success = Mail::to($this->customer->email)->send(new MailBill($this->customer, Storage::path($filePath)));

            print_r($this->customer->email.'  : '.($success ? 'Email envoyé' : 'Email non envoyé'));
        } catch (\Exception $e) {
            print_r('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
        }
    }
}
