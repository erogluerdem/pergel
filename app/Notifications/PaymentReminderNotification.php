<?php

namespace App\Notifications;

use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Installment $installment, public int $daysRemaining) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $customer = $this->installment->invoice->customer;

        return (new MailMessage)
            ->subject('Ödeme Hatırlatması - ' . config('app.name'))
            ->greeting("Sayın {$customer->name},")
            ->line("Fatura #{$this->installment->invoice->invoice_number} için taksit ödemeniz yapılmıştır.")
            ->line("**Ödeme Detayları:**")
            ->line("- Taksit Numarası: {$this->installment->installment_number}")
            ->line("- Tutar: " . number_format($this->installment->amount, 2) . " TL")
            ->line("- Son Ödeme Tarihi: " . $this->installment->due_date->format('d.m.Y'))
            ->line("- Günler Kaldı: {$this->daysRemaining}")
            ->action('Fatura Detaylarını Gör', route('invoices.show', $this->installment->invoice))
            ->line("Ödemenizi zamanında yapmanız gerektiğini hatırlatırız.")
            ->salutation('Saygılarımızla, ' . config('app.name'));
    }
}
