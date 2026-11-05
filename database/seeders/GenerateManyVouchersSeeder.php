<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class GenerateManyVouchersSeeder extends Seeder
{
    public function run()
    {
        // Ensure required tables exist
        if (!Schema::hasTable('vouchers') || !Schema::hasTable('voucher_lines') || !Schema::hasTable('invoices') || !Schema::hasTable('customers')) {
            $this->command->error('Required tables missing. Run migrations/create tables before running this seeder.');
            return;
        }

        // Ensure at least two customers exist; create defaults if not
        $customerIds = DB::table('customers')->pluck('id')->toArray();
        if (count($customerIds) < 2) {
            $id1 = DB::table('customers')->insertGetId([
                'name' => 'Acme A.Ş.',
                'balance' => 1000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $id2 = DB::table('customers')->insertGetId([
                'name' => 'Beta Ticaret',
                'balance' => 500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $customerIds = [$id1, $id2];
        } else {
            $customerIds = array_slice($customerIds, 0, 2);
        }

        $this->command->info('Using customer ids: ' . implode(',', $customerIds));

        $createdInvoices = 0;
        $createdVouchers = 0;

        for ($i = 1; $i <= 100; $i++) {
            $customerId = $customerIds[$i % 2];
            $amount = (float) (50 + (($i * 73) % 900)); // deterministic pseudo-random amount
            $paid_amount = ($i % 4 == 0) ? round($amount * 0.5, 2) : 0.00;

            $invoiceId = DB::table('invoices')->insertGetId([
                'no' => 'SIM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'amount' => $amount,
                'paid_amount' => $paid_amount,
                'customer_id' => $customerId,
                'date' => Carbon::today()->subDays($i)->toDateString(),
                'due_date' => Carbon::today()->addDays(30)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $createdInvoices++;

            $types = ['collection', 'payment', 'journal'];
            $type = $types[$i % 3];
            $payment_method = ($type === 'collection') ? 'cash' : (($type === 'payment') ? 'bank_transfer' : null);

            // Counterparty name from customers table (if exists)
            $counterpartyName = DB::table('customers')->where('id', $customerId)->value('name');

            $voucherId = DB::table('vouchers')->insertGetId([
                'type' => $type,
                'date' => Carbon::today()->subDays($i)->toDateString(),
                'no' => 'VCH-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'account' => ($type === 'journal') ? null : (($type === 'collection') ? 'Kasa ' . ($i % 3) : 'Banka ' . ($i % 2)),
                'counterparty' => ($type === 'journal') ? null : $counterpartyName,
                'amount' => $amount,
                'payment_method' => $payment_method,
                'notes' => 'Seed örnek fiş #' . $i . ' (invoice ' . $invoiceId . ')',
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $createdVouchers++;

            // Insert two balancing lines (debit/credit)
            DB::table('voucher_lines')->insert([
                [
                    'voucher_id' => $voucherId,
                    'account' => '100-KASA',
                    'debit' => ($type === 'payment' ? 0 : $amount),
                    'credit' => ($type === 'payment' ? $amount : 0),
                    'description' => 'Seed line A for voucher ' . $voucherId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'voucher_id' => $voucherId,
                    'account' => '200-ALACAK',
                    'debit' => ($type === 'payment' ? $amount : 0),
                    'credit' => ($type === 'payment' ? 0 : $amount),
                    'description' => 'Seed line B for voucher ' . $voucherId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Update invoice paid_amount when collection (to simulate partial/full payments)
            if ($type === 'collection') {
                DB::table('invoices')->where('id', $invoiceId)->update(['paid_amount' => DB::raw('paid_amount + ' . $amount)]);
            }
        }

        $this->command->info("Created {$createdInvoices} invoices and {$createdVouchers} vouchers (with lines).");
    }
}
