<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HrExpense;
use App\Models\User;
use Carbon\Carbon;

class HrExpenseSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command?->info('No users found. Creating sample user...');
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user]);
        }

        $expenseTypes = array_keys(HrExpense::getExpenseTypes());
        $categories = array_keys(HrExpense::getCategories());
        $departments = array_keys(HrExpense::getDepartments());
        $statuses = ['pending', 'approved', 'rejected', 'paid'];

        // Son 6 ay için örnek harcamalar oluştur
        for ($i = 0; $i < 50; $i++) {
            $employee = $users->random();
            $expenseDate = Carbon::now()->subDays(rand(1, 180));
            $submissionDate = $expenseDate->copy()->addDays(rand(0, 7));
            $status = $statuses[array_rand($statuses)];
            $amount = rand(50, 2000) + (rand(0, 99) / 100); // 50.00 - 2000.99 TL arası

            $expense = HrExpense::create([
                'employee_id' => $employee->id,
                'expense_type' => $expenseTypes[array_rand($expenseTypes)],
                'category' => $categories[array_rand($categories)],
                'title' => $this->generateExpenseTitle(),
                'description' => $this->generateExpenseDescription(),
                'amount' => $amount,
                'currency' => 'TRY',
                'expense_date' => $expenseDate,
                'submission_date' => $submissionDate,
                'status' => $status,
                'department' => $departments[array_rand($departments)],
                'reference_no' => 'EXP-' . strtoupper(uniqid()),
                'created_by' => $employee->id,
                'created_at' => $submissionDate,
                'updated_at' => $submissionDate,
            ]);

            // Onaylanmış veya ödenmiş olanlar için onay bilgileri
            if (in_array($status, ['approved', 'paid'])) {
                $expense->update([
                    'approved_by' => $users->random()->id,
                    'approved_at' => $submissionDate->copy()->addDays(rand(1, 5)),
                    'approval_notes' => $this->generateApprovalNote(),
                ]);
            }

            // Ödenmiş olanlar için ödeme bilgileri
            if ($status === 'paid') {
                $expense->update([
                    'reimbursed_amount' => $amount,
                    'reimbursement_date' => $expense->approved_at->copy()->addDays(rand(1, 10)),
                    'payment_method' => ['bank_transfer', 'cash', 'cheque'][array_rand(['bank_transfer', 'cash', 'cheque'])],
                ]);
            }
        }

        $this->command?->info('Created 50 sample HR expenses');
    }

    private function generateExpenseTitle()
    {
        $titles = [
            'İş Yemeği - Müşteri Toplantısı',
            'Ankara İş Seyahati',
            'Taksi Ücreti',
            'Otobüs Bileti',
            'Otel Konaklama',
            'Kursiya Semineri',
            'Ofis Malzemeleri',
            'İnternet Faturası',
            'Telefon Görüşme Ücreti',
            'Park Ücreti',
            'Havaalanı Transfer',
            'Müşteri Ağırlamaya',
            'Çalışan Yemeği',
            'Kargo Ücreti',
            'Benzin Masrafı',
            'Temizlik Malzemeleri',
            'Kırtasiye Alımı',
            'Konferans Ücreti',
            'Workshop Katılım',
            'Sağlık Muayenesi'
        ];

        return $titles[array_rand($titles)];
    }

    private function generateExpenseDescription()
    {
        $descriptions = [
            'İş gereği yapılan harcama',
            'Müşteri ile görüşme için gerekli masraf',
            'Proje kapsamında yapılan harcama',
            'Departman ihtiyaçları için alım',
            'Eğitim ve gelişim amaçlı harcama',
            'Operasyonel gereksinimler için',
            'Acil ihtiyç nedeniyle yapılan alım',
            'Planlanan bütçe kapsamında harcama',
            'Müşteri memnuniyeti için yapılan masraf',
            'Verimlilik artışı amaçlı harcama'
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function generateApprovalNote()
    {
        $notes = [
            'Uygun görülmüştür.',
            'Belgeler incelendi, onaylanmıştır.',
            'Bütçe dahilinde, onaylanmıştır.',
            'İş gereği yapılan harcama, uygun.',
            'Gerekli harcama olarak değerlendirilmiştir.',
            'Onay verilmiştir.',
            'Makul tutarda, onaylanmıştır.'
        ];

        return $notes[array_rand($notes)];
    }
}
