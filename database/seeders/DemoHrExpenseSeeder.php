<?php

namespace Database\Seeders;

use App\Models\HrExpense;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoHrExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $admin = User::first();

        $expenses = [
            [
                'reference_no' => 'EXP-2026-000101',
                'employee_id' => $admin->id,
                'created_by' => $admin->id,
                'expense_type' => 'travel',
                'category' => 'business',
                'title' => 'Ankara Hızlı Tren & Şantiye Ulaşım Harcırahı',
                'description' => 'Güneşli projesi saha denetimi ve bakanlık görüşmesi için tren ve taksi harcamaları.',
                'amount' => 4850.00,
                'currency' => 'TRY',
                'expense_date' => now()->subDays(2),
                'submission_date' => now()->subDays(2),
                'status' => 'pending',
                'department' => 'Proje Yönetimi',
                'project_code' => 'PRJ-001',
                'payment_method' => 'Kişisel Kredi Kartı',
            ],
            [
                'reference_no' => 'EXP-2026-000102',
                'employee_id' => $users->skip(1)->first()?->id ?? $admin->id,
                'created_by' => $admin->id,
                'expense_type' => 'meal',
                'category' => 'business',
                'title' => 'Taşeron Mühendisleri Saha Ağırlama Yemeği',
                'description' => 'Aksu Ticaret Merkezi hakediş toplantısı sonrası teknik heyet iş yemeği.',
                'amount' => 6200.00,
                'currency' => 'TRY',
                'expense_date' => now()->subDays(4),
                'submission_date' => now()->subDays(3),
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(2),
                'approval_notes' => 'Fatura ve harcama fişleri incelendi, onaylandı.',
                'department' => 'Saha Uygulama',
                'project_code' => 'PRJ-002',
                'payment_method' => 'Nakit',
            ],
            [
                'reference_no' => 'EXP-2026-000103',
                'employee_id' => $admin->id,
                'created_by' => $admin->id,
                'expense_type' => 'training',
                'category' => 'business',
                'title' => 'İSG A Sınıfı Uzmanlık Yenileme Eğitimi Harcı',
                'description' => 'Şantiye İSG uzmanlık belgesi vize yenileme ve harç ödemesi.',
                'amount' => 12500.00,
                'currency' => 'TRY',
                'expense_date' => now()->subDays(7),
                'submission_date' => now()->subDays(6),
                'status' => 'paid',
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(5),
                'reimbursed_amount' => 12500.00,
                'reimbursement_date' => now()->subDays(4),
                'department' => 'Kalite Yönetimi',
                'project_code' => 'GENEL',
                'payment_method' => 'Banka Transferi',
            ],
            [
                'reference_no' => 'EXP-2026-000104',
                'employee_id' => $users->skip(2)->first()?->id ?? $admin->id,
                'created_by' => $admin->id,
                'expense_type' => 'office_supplies',
                'category' => 'business',
                'title' => 'Şantiye Konteyneri Plotter Ozalit Çıktıları',
                'description' => 'A-Blok As-Built uygulama projeleri ozalit ve pafta basım masrafı.',
                'amount' => 3400.00,
                'currency' => 'TRY',
                'expense_date' => now()->subDays(10),
                'submission_date' => now()->subDays(9),
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(8),
                'department' => 'MEP Tesisat',
                'project_code' => 'PRJ-003',
                'payment_method' => 'Kişisel Kredi Kartı',
            ],
            [
                'reference_no' => 'EXP-2026-000105',
                'employee_id' => $admin->id,
                'created_by' => $admin->id,
                'expense_type' => 'accommodation',
                'category' => 'business',
                'title' => 'İzmir Liman Şantiyesi Konaklama Faturası',
                'description' => 'Çelik yapı imalat kontrolü için 3 günlük otel konaklama faturası.',
                'amount' => 14200.00,
                'currency' => 'TRY',
                'expense_date' => now()->subDays(12),
                'submission_date' => now()->subDays(11),
                'status' => 'pending',
                'department' => 'Yönetim',
                'project_code' => 'PRJ-004',
                'payment_method' => 'Kredi Kartı',
            ],
        ];

        foreach ($expenses as $exp) {
            HrExpense::create($exp);
        }
    }
}
