<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SubmittalReviewRequested extends Notification implements ShouldQueue
{
    use Queueable;
    protected $sub;
    public function __construct($sub){ $this->sub = $sub; }
    public function via($notifiable){ return ['database']; }
    public function toDatabase($notifiable){ return ['type'=>'submittal_review','submittal_id'=>$this->sub->id,'project_id'=>$this->sub->project_id,'desc'=>$this->sub->description]; }
}
