<?php

namespace App\Jobs;

use App\Enums\DocumentType;
use App\Mail\MailBill;
use App\Models\Customer;
use App\Models\Rental;
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
        try {
            print_r("Envoi de l'email de facture à {$this->customer->email} pour la location {$this->rental->id}.\n");

            $filePath = $this->rental->documents()->where('type', DocumentType::BILL->value)->first()->url;
            $success = Mail::to($this->customer->email)->send(new MailBill($this->customer, Storage::path($filePath)));

            print_r($this->customer->email.'  : '.($success ? 'Email envoyé' : 'Email non envoyé'));
        } catch (\Exception $e) {
            print_r('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
        }
    }
}
