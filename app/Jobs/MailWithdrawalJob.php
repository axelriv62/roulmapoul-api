<?php

namespace App\Jobs;

use App\Enums\DocumentType;
use App\Mail\MailWithdrawal;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Withdrawal;
use Dompdf\Dompdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailWithdrawalJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Withdrawal $withdrawal, private readonly Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml(view('pdf.withdrawal', ['withdrawal' => $this->withdrawal]));
        $dompdf->setPaper('A4');
        $dompdf->render();

        $filename = 'withdrawal_'.$this->customer->id.'_'.$this->withdrawal->id.'.pdf';
        Storage::put('docs/'.$filename, $dompdf->output());
        $filePath = Storage::path('docs/'.$filename);

        Document::create([
            'type' => DocumentType::WITHDRAWAL,
            'url' => $filePath,
            'rental_id' => $this->withdrawal->rental->id,
        ]);

        try {
            print_r("Envoi de l'email de retrait à {$this->customer->email} pour le retrait {$this->withdrawal->id}.\n");

            $success = Mail::to($this->customer->email)->send(new MailWithdrawal($this->customer, $filePath));

            print_r($this->customer->email.'  : '.($success ? 'Email envoyé' : 'Email non envoyé'));
        } catch (\Exception $e) {
            print_r('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
        }
    }
}
