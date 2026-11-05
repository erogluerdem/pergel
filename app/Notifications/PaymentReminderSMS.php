<?php

namespace App\Notifications;

use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentReminderSMS extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Installment $installment) {}

    public function via(object $notifiable): array
    {
        return ['vonage'];
    }

    public function toVonage(object $notifiable): string
    {
        $customer = $this->installment->invoice->customer;
        $invoice = $this->installment->invoice;

        return sprintf(
            "Sayın %s, Fatura #%s için %d. taksit ödemesi %s tarihine kadar yapılmalıdır. Tutar: %.2f TL. Bilgiye sahip olmak için: %s",
            substr($customer->name, 0, 20),
            $invoice->invoice_number,
            $this->installment->installment_number,
            $this->installment->due_date->format('d.m.Y'),
            $this->installment->amount,
            route('finance.invoices.show', $invoice)
        );
    }
}
