<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Opportunity;

class BackfillOpportunityKdv extends Command
{
    protected $signature = 'opportunities:backfill-kdv {--dry : Do not persist changes, just show what would be done}';
    protected $description = 'Backfill kdv_amount and estimated_value_kdv for existing opportunities using FinanceService';

    public function handle()
    {
        $dry = $this->option('dry');
        $this->info('Scanning opportunities...');
        $count = 0;
        Opportunity::chunk(100, function($items) use (&$count, $dry) {
            foreach ($items as $opp) {
                $est = (float) ($opp->estimated_value ?: 0);
                // if record has kdv_percent use it, otherwise fallback to 18
                $kdvPercent = intval($opp->kdv_percent ?? 18);
                $kdvAmount = \App\Services\FinanceService::kdvAmount($est, $kdvPercent);
                $estKdv = \App\Services\FinanceService::applyKdv($est, $kdvPercent);

                // If values are different or null, update
                if ($opp->kdv_amount != $kdvAmount || $opp->estimated_value_kdv != $estKdv || $opp->kdv_percent != $kdvPercent) {
                    $this->line("Updating #{$opp->id}: est={$est}, kdv%={$kdvPercent}, kdvAmount={$kdvAmount}, estKdv={$estKdv}");
                    $count++;
                    if (!$dry) {
                        $opp->kdv_percent = $kdvPercent;
                        $opp->kdv_amount = $kdvAmount;
                        $opp->estimated_value_kdv = $estKdv;
                        $opp->save();
                    }
                }
            }
        });

        $this->info("Done. Updated: {$count} records" . ($dry ? ' (dry run)' : ''));
        return 0;
    }
}
