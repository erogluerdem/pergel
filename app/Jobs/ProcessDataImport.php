<?php

namespace App\Jobs;

use App\Models\DataImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessDataImport implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private DataImport $import,
        private array $data,
    ) {}

    public function handle(): void
    {
        try {
            $this->import->update(['status' => 'processing']);

            $imported = 0;
            $failed = 0;
            $errors = [];

            foreach ($this->data as $index => $row) {
                try {
                    $this->importRow($row, $index + 1);
                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'row' => $index + 1,
                        'message' => $e->getMessage(),
                    ];
                }

                // Her 50 satırda bir update
                if (($index + 1) % 50 === 0) {
                    $this->import->update([
                        'imported_rows' => $imported,
                        'failed_rows' => $failed,
                        'error_messages' => $errors,
                    ]);
                }
            }

            // Final update
            $this->import->update([
                'status' => $failed === 0 ? 'completed' : 'completed',
                'imported_rows' => $imported,
                'failed_rows' => $failed,
                'error_messages' => $errors,
            ]);
        } catch (\Exception $e) {
            $this->import->update([
                'status' => 'failed',
                'error_messages' => [['row' => 0, 'message' => $e->getMessage()]],
            ]);
        }
    }

    private function importRow(array $row, int $lineNumber): void
    {
        // Model type'a göre veri ekle
        match($this->import->module) {
            'invoices' => $this->insertInvoice($row),
            'projects' => $this->insertProject($row),
            'users' => $this->insertUser($row),
            'contacts' => $this->insertContact($row),
            'tasks' => $this->insertTask($row),
            default => throw new \Exception("Bilinmeyen modül: {$this->import->module}"),
        };
    }

    private function insertInvoice(array $row): void
    {
        $required = ['invoice_number', 'amount'];
        foreach ($required as $field) {
            if (empty($row[$field])) {
                throw new \Exception("Zorunlu alan eksik: $field");
            }
        }

        DB::table('invoices')->insert([
            'invoice_number' => $row['invoice_number'],
            'amount' => (float) $row['amount'],
            'date' => $row['date'] ?? now()->format('Y-m-d'),
            'status' => $row['status'] ?? 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertProject(array $row): void
    {
        $required = ['name'];
        foreach ($required as $field) {
            if (empty($row[$field])) {
                throw new \Exception("Zorunlu alan eksik: $field");
            }
        }

        DB::table('projects')->insert([
            'name' => $row['name'],
            'budget' => isset($row['budget']) ? (float) $row['budget'] : 0,
            'status' => $row['status'] ?? 'pending',
            'start_date' => $row['start_date'] ?? now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertUser(array $row): void
    {
        $required = ['name', 'email'];
        foreach ($required as $field) {
            if (empty($row[$field])) {
                throw new \Exception("Zorunlu alan eksik: $field");
            }
        }

        DB::table('users')->insert([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => bcrypt($row['password'] ?? 'password'),
            'role' => $row['role'] ?? 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertContact(array $row): void
    {
        DB::table('contacts')->insert([
            'name' => $row['name'] ?? '',
            'email' => $row['email'] ?? '',
            'phone' => $row['phone'] ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function insertTask(array $row): void
    {
        DB::table('tasks')->insert([
            'title' => $row['title'] ?? '',
            'description' => $row['description'] ?? '',
            'status' => $row['status'] ?? 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
