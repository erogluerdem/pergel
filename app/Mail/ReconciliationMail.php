<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReconciliationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $bodyHtml;

    public function __construct($customer, $bodyHtml)
    {
        $this->customer = $customer;
        $this->bodyHtml = $bodyHtml;
    }

    public function build()
    {
        return $this->subject('Mutabakat Mektubu - ' . ($this->customer->name ?? ''))
            ->view('emails.reconciliation')
            ->with(['bodyHtml' => $this->bodyHtml, 'customer' => $this->customer]);
    }
}
