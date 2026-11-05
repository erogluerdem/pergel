<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guarantee;
use App\Notifications\GuaranteeExpiringNotification;

class CheckGuaranteesExpiry extends Command
{
    protected $signature = 'guarantees:check-expiry';
    protected $description = 'Check guarantees that are about to expire and notify responsible users';

    public function handle()
    {
        $this->info('Checking guarantees for upcoming expiries...');

        $threshold = now()->addDays(15)->toDateString();

        $guarantees = Guarantee::with('contract')->whereNotNull('end_date')->where('end_date', '<=', $threshold)->get();

        foreach ($guarantees as $g) {
            // notify contract uploader or project manager if available
            $contract = $g->contract;
            if ($contract && $contract->project && $contract->project->manager_id) {
                $user = \App\Models\User::find($contract->project->manager_id);
                if ($user) {
                    $user->notify(new GuaranteeExpiringNotification($g));
                }
            }
            // also notify admin users (simple approach: first user)
            $admin = \App\Models\User::first();
            if ($admin) {
                $admin->notify(new GuaranteeExpiringNotification($g));
            }
        }

        $this->info('Done. Notifications sent: ' . $guarantees->count());
        return 0;
    }
}
