<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VendorDocument;
use Carbon\Carbon;

class VendorDocumentExpiryCheck extends Command
{
    protected $signature = 'vendors:check-document-expiry';
    protected $description = 'Check vendor documents for upcoming expiry and list them';

    public function handle()
    {
        $this->info('Checking vendor document expiries...');
        $soon = Carbon::now()->addDays(30)->toDateString();
        $today = Carbon::now()->toDateString();

        $docs = VendorDocument::whereNotNull('expiry_date')->whereBetween('expiry_date', [$today, $soon])->get();
        foreach ($docs as $d) {
            $this->warn("Vendor {$d->vendor_id} document {$d->type} expires on {$d->expiry_date} (file: {$d->file_path})");
        }
        $this->info('Done.');
        return 0;
    }
}
