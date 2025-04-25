<?php

namespace App\Jobs;

use App\Enums\DocumentType;
use App\Mail\MailWithdrawal;
use App\Models\Customer;
use App\Models\Withdrawal;
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
        try {
            print_r("Envoi de l'email de retrait à {$this->customer->email} pour le retrait {$this->withdrawal->id}.\n");
            $filePath = $this->withdrawal->rental->documents()->where('type', DocumentType::WITHDRAWAL->value)->first()->url;
            $success = Mail::to($this->customer->email)->send(new MailWithdrawal($this->customer, Storage::path($filePath)));

            print_r($this->customer->email.'  : '.($success ? 'Email envoyé' : 'Email non envoyé'));
        } catch (\Exception $e) {
            print_r('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
        }
    }
}
