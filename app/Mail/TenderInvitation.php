<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenderInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $package;
    public $accessUrl;

    public function __construct($package, $accessUrl)
    {
        $this->package = $package;
        $this->accessUrl = $accessUrl;
    }

    public function build()
    {
        return $this->subject('Yeni İhale Daveti: ' . $this->package->name)
                    ->view('emails.tender-invitation');
    }
}
