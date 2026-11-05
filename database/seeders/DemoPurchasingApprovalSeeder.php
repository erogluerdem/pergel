<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Requisition;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoPurchasingApprovalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $project = Project::first();

        // Create sample requisitions if needed
        $req1 = Requisition::create([
            'project_id' => $project?->id,
            'requested_by' => $admin?->id,
            'required_date' => now()->addDays(10),
            'status' => 'pending',
            'notes' => 'A-Blok Çatı Katı 120 Tonluk Kule Vinç Kiralama Talebi',
        ]);

        $req2 = Requisition::create([
            'project_id' => $project?->id,
            'requested_by' => $admin?->id,
            'required_date' => now()->addDays(5),
            'status' => 'approved',
            'notes' => 'C35/40 Şantiye Temel Betonu ve Pompa Hizmeti Satınalma Talebi',
        ]);

        $req3 = Requisition::create([
            'project_id' => $project?->id,
            'requested_by' => $admin?->id,
            'required_date' => now()->addDays(15),
            'status' => 'pending',
            'notes' => 'MEP Şantiye Ana Dağıtım Panoları ve Jeneratör Seti',
        ]);

        $req4 = Requisition::create([
            'project_id' => $project?->id,
            'requested_by' => $admin?->id,
            'required_date' => now()->addDays(3),
            'status' => 'rejected',
            'notes' => 'Yüksek İrtifa Yaşam Halatları ve Kişisel İSG Malzemeleri',
        ]);

        $approvals = [
            [
                'requisition_id' => $req1->id,
                'approval_level' => 'manager',
                'approver_id' => $admin?->id,
                'status' => 'pending',
                'comments' => 'Saha proje müdürü teknik inceleme ve fiyat değerlendirme aşamasında.',
            ],
            [
                'requisition_id' => $req2->id,
                'approval_level' => 'finance',
                'approver_id' => $admin?->id,
                'status' => 'approved',
                'approved_at' => now()->subDays(1),
                'comments' => 'Finans bütçe limiti uygun görüldü, satın alma siparişine dönüştürülebilir.',
            ],
            [
                'requisition_id' => $req3->id,
                'approval_level' => 'executive',
                'approver_id' => $admin?->id,
                'status' => 'pending',
                'comments' => 'Genel müdürlük ve yönetim kurulu 2. onay seviyesinde bekliyor.',
            ],
            [
                'requisition_id' => $req4->id,
                'approval_level' => 'manager',
                'approver_id' => $admin?->id,
                'status' => 'rejected',
                'rejected_at' => now()->subDays(2),
                'rejection_reason' => 'Teknik şartnamedeki marka ve standart uyumsuzluğu nedeniyle revize talep edildi.',
            ],
        ];

        foreach ($approvals as $appData) {
            Approval::create($appData);
        }
    }
}
