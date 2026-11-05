<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CommunicationAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->payload['title'] ?? 'Yeni iletişim ataması',
            'body' => $this->payload['body'] ?? '',
            'link' => $this->payload['link'] ?? null,
            'meta' => $this->payload['meta'] ?? []
        ];
    }
}
