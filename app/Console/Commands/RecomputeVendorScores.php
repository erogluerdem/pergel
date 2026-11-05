<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vendor;
use App\Models\VendorReview;

class RecomputeVendorScores extends Command
{
    protected $signature = 'vendors:recompute-scores';
    protected $description = 'Recompute performance scores for all vendors from reviews';

    public function handle()
    {
        $this->info('Recomputing vendor scores...');
        $count = 0;
        foreach (Vendor::cursor() as $vendor) {
            $reviews = VendorReview::where('vendor_id',$vendor->id)->get();
            if ($reviews->isEmpty()) continue;

            $avgQuality = $reviews->avg('quality');
            $avgSchedule = $reviews->avg('schedule');
            $avgSafety = $reviews->avg('safety');
            $avgCommunication = $reviews->avg('communication');

            $score = ($avgQuality + $avgSchedule + $avgSafety + $avgCommunication) / 4;
            $vendor->performance_score = round($score,1);
            $vendor->save();
            $count++;
        }
        $this->info("Done. Updated {$count} vendors.");
        return 0;
    }
}
