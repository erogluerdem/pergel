<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RfiAssigned extends Notification
{
    use Queueable;

    protected $rfi;

    public function __construct($rfi)
    {
        $this->rfi = $rfi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'rfi_assigned',
            'rfi_id' => $this->rfi->id,
            'subject' => $this->rfi->subject,
            'project_id' => $this->rfi->project_id,
        ];
    }
}
