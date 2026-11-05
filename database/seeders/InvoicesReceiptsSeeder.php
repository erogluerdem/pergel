<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InvoicesReceiptsSeeder extends Seeder
{
    public function run()
    {
        // attach some invoices to first customers
        $customers = \App\Models\Customer::all();
        foreach ($customers as $c) {
            // create 2 invoices per customer
            $inv1 = Invoice::create([
                'customer_id' => $c->id,
                'no' => 'INV-'.str_pad($c->id,3,'0',STR_PAD_LEFT).'-01',
                'date' => now()->subDays(40)->toDateString(),
                'due_date' => now()->subDays(10)->toDateString(),
                'amount' => 10000 + ($c->id * 1000),
                'paid_amount' => 2000,
                'status' => 'partially_paid'
            ]);

            $inv2 = Invoice::create([
                'customer_id' => $c->id,
                'no' => 'INV-'.str_pad($c->id,3,'0',STR_PAD_LEFT).'-02',
                'date' => now()->subDays(70)->toDateString(),
                'due_date' => now()->subDays(40)->toDateString(),
                'amount' => 5000 + ($c->id * 200),
                'paid_amount' => 0,
                'status' => 'open'
            ]);

            // add receipt for inv1
            Receipt::create([
                'invoice_id' => $inv1->id,
                'date' => now()->subDays(20)->toDateString(),
                'amount' => 2000,
                'method' => 'Banka Havalesi',
                'reference' => 'REF'.$inv1->id
            ]);

            // update customer balances simply
            $balance = $c->invoices()->sum(DB::raw('amount - paid_amount'));
            $c->balance = $balance;
            if(Schema::hasColumn('customers','overdue_balance')){
                $c->overdue_balance = $c->invoices()->whereRaw('due_date < ? and (amount - paid_amount) > 0', [now()->toDateString()])->sum(DB::raw('amount - paid_amount'));
            }
            $c->save();
        }
    }
}
