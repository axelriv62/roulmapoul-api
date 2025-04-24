<?php

namespace App\Jobs;

use App\Enums\DocumentType;
use App\Mail\MailHandover;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Handover;
use Dompdf\Dompdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailHandoverJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Handover $handover, private readonly Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml(view('pdf.handover', ['handover' => $this->handover]));
        $dompdf->setPaper('A4');
        $dompdf->render();
        $filePath = 'docs/handover_'.$this->customer->id.'_'.$this->handover->id.'.pdf';
        Storage::put($filePath, $dompdf->output());

        Document::create([
            'type' => DocumentType::HANDOVER,
            'url' => $filePath,
            'rental_id' => $this->handover->rental->id,
        ]);

        try {
            print_r("Envoi de l'email de retour à {$this->customer->email} pour le retour {$this->handover->id}.\n");

            $success = Mail::to($this->customer->email)->send(new MailHandover($this->customer, Storage::path($filePath)));

            print_r($this->customer->email.'  : '.($success ? 'Email envoyé' : 'Email non envoyé'));
        } catch (\Exception $e) {
            print_r('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
        }
    }
}
