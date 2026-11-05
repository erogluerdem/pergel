<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuaranteeExpiringNotification extends Notification
{
    use Queueable;

    protected $guarantee;

    public function __construct($guarantee)
    {
        $this->guarantee = $guarantee;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        $g = $this->guarantee;
        return (new MailMessage)
                    ->subject('Teminat Süresi Yaklaşıyor: ' . ($g->contract->name ?? 'Sözleşme'))
                    ->line('Teminat türü: ' . ($g->type ?? '-'))
                    ->line('Bitiş tarihi: ' . ($g->end_date ? $g->end_date->format('Y-m-d') : '-'))
                    ->action('Sözleşme Görüntüle', url('/contracts/management'))
                    ->line('Lütfen gerekli yenileme veya düzenlemeleri yapın.');
    }

    public function toArray($notifiable)
    {
        $g = $this->guarantee;
        return [
            'guarantee_id' => $g->id,
            'contract_id' => $g->contract_id,
            'end_date' => $g->end_date,
            'message' => 'Teminat süresi yaklaşıyor: ' . ($g->contract->name ?? ''),
        ];
    }
}
