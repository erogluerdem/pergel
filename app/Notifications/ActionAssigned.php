<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ActionAssigned extends Notification
{
    use Queueable;
    protected $action;
    public function __construct($action){ $this->action = $action; }
    public function via($notifiable){ return ['database']; }
    public function toDatabase($notifiable){ return ['type'=>'action_assigned','action_id'=>$this->action->id,'title'=>$this->action->title,'project_id'=>$this->action->project_id]; }
}
