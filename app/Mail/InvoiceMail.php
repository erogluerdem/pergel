<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $bodyText;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, $bodyText = null)
    {
        $this->invoice = $invoice;
        $this->bodyText = $bodyText;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.invoice_plain')
                    ->with(['invoice' => $this->invoice, 'body' => $this->bodyText]);
    }
}
