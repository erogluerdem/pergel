<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChangeOrder;

class ChangeOrdersInfo extends Command
{
    protected $signature = 'changeorders:info';
    protected $description = 'Show count and sample of change orders (for verification)';

    public function handle()
    {
        $count = ChangeOrder::count();
        $this->info("ChangeOrder count: {$count}");
        $this->line('---');
        ChangeOrder::orderBy('created_at','desc')->take(20)->get()->each(function($c){
            $this->line("{$c->id} | {$c->no} | {$c->subject} | {$c->status} | {$c->cost}");
        });
        return 0;
    }
}
