<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportVouchersFromJson extends Command
{
    protected $signature = 'vouchers:import-json {--file= : Path to vouchers.json (default storage/app/vouchers.json)}';
    protected $description = 'Import vouchers from storage/app/vouchers.json into vouchers/voucher_lines tables';

    public function handle()
    {
        $file = $this->option('file') ?: storage_path('app/vouchers.json');
        if (!file_exists($file)) {
            $this->info('JSON file not found: ' . $file);
            return 0;
        }

        $contents = file_get_contents($file);
        $list = json_decode($contents, true);
        if (!is_array($list) || empty($list)) {
            $this->info('No records to import.');
            return 0;
        }

        $this->info('Found ' . count($list) . ' records. Importing...');
        $imported = 0;
        foreach ($list as $rec) {
            try {
                $vData = [
                    'type' => $rec['type'] ?? 'journal',
                    'date' => $rec['date'] ?? now()->toDateString(),
                    'account' => $rec['account'] ?? null,
                    'counterparty' => $rec['counterparty'] ?? null,
                    'amount' => $rec['amount'] ?? 0,
                    'payment_method' => $rec['payment_method'] ?? null,
                    'notes' => $rec['notes'] ?? null,
                    'created_by' => $rec['created_by'] ?? null,
                    'created_at' => $rec['created_at'] ?? now()->toDateTimeString(),
                    'updated_at' => $rec['created_at'] ?? now()->toDateTimeString(),
                ];

                $voucher = \App\Models\Voucher::create($vData);
                if (!empty($rec['lines']) && is_array($rec['lines'])) {
                    foreach ($rec['lines'] as $ln) {
                        \App\Models\VoucherLine::create([
                            'voucher_id' => $voucher->id,
                            'account' => $ln['account'] ?? null,
                            'debit' => $ln['debit'] ?? 0,
                            'credit' => $ln['credit'] ?? 0,
                            'description' => $ln['description'] ?? null,
                        ]);
                    }
                }

                $imported++;
            } catch (\Exception $e) {
                $this->error('Failed import record: ' . ($e->getMessage()));
            }
        }

        // backup the file
        $bak = $file . '.imported.' . date('YmdHis');
        try { copy($file, $bak); unlink($file); $this->info('Backup saved to: ' . $bak); } catch (\Exception $e) { $this->warn('Could not backup original JSON: ' . $e->getMessage()); }

        $this->info('Imported: ' . $imported . ' records.');
        return 0;
    }
}
