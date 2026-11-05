<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenderInvitation;
use App\Models\TenderPackage;

class SendTestTenderMail extends Command
{
    protected $signature = 'tender:send-test-mail {package_id} {email}';
    protected $description = 'Send a test tender invitation email (uses queue)';

    public function handle()
    {
        $pkg = TenderPackage::find($this->argument('package_id'));
        if (!$pkg) return $this->error('Package not found');
        $email = $this->argument('email');
        $url = url('/');
        Mail::to($email)->queue(new TenderInvitation($pkg, $url));
        $this->info('Queued test mail to '.$email);
    }
}
