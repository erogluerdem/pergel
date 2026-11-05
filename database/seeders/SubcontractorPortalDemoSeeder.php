<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Subcontractor;
use App\Models\SubcontractorContract;
use App\Models\SubcontractorMaterialEntry;
use App\Models\SubcontractorServiceEntry;
use App\Models\SubcontractorUser;
use Illuminate\Database\Seeder;

class SubcontractorPortalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::first();

        if (! $project) {
            $project = Project::create([
                'name' => 'Pergel Towers Faz-1',
                'description' => 'Subcontractor portal test projesi',
                'status' => 'active',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
            ]);
        }

        $subcontractor = Subcontractor::updateOrCreate(
            ['company_name' => 'Demo Taşeron İnşaat A.Ş.'],
            [
                'address' => 'İzmir Teknokent, Gaziemir / İzmir',
                'tax_number' => '1234567890',
                'phone' => '+90 (232) 000 00 00',
                'email' => 'iletisim@demotas.com',
                'contact_person' => 'Mert Kılıç',
                'expertise_area' => 'Beton ve Kalıp',
                'performance_score' => 4.5,
                'safety_score' => 92.00,
                'status' => 'approved',
                'active_projects' => 1,
                'documents' => [
                    ['name' => 'SGK Hizmet Dökümü', 'uploaded_at' => now()->subDays(10)],
                    ['name' => 'İSG Sertifikası', 'uploaded_at' => now()->subDays(30)],
                ],
            ]
        );

        $contract = SubcontractorContract::updateOrCreate(
            ['contract_number' => 'SUB-2025-001'],
            [
                'contract_name' => 'Pergel Towers Kaba İnşaat İşleri',
                'subcontractor_id' => $subcontractor->id,
                'project_id' => $project->id,
                'contract_type' => 'unit_price',
                'total_amount' => 1250000,
                'paid_amount' => 275000,
                'status' => 'active',
                'start_date' => now()->subMonths(5),
                'end_date' => now()->addMonths(7),
                'description' => 'Kaba inşaat kalıp, demir ve beton işlerini kapsar.',
                'documents' => [
                    ['name' => 'Sözleşme.pdf', 'url' => '#'],
                ],
                'specifications' => [
                    'C35 beton dökümü',
                    'Tünel kalıp sistemi',
                ],
                'insurance_expiry_dates' => [
                    'işveren_mali_mesuliyet' => now()->addMonths(2)->toDateString(),
                ],
            ]
        );

        $user = SubcontractorUser::updateOrCreate(
            ['email' => 'demo.subcontractor@pergel.local'],
            [
                'subcontractor_id' => $subcontractor->id,
                'name' => 'Demo Taşeron Kullanıcı',
                'phone' => '+90 (555) 111 22 33',
                'password' => 'DemoPortal!23',
                'role' => 'manager',
                'status' => 'active',
                'last_login_at' => null,
            ]
        );

        $user->contracts()->syncWithoutDetaching([$contract->id]);

        SubcontractorServiceEntry::firstOrCreate(
            [
                'subcontractor_contract_id' => $contract->id,
                'project_id' => $project->id,
                'subcontractor_user_id' => $user->id,
                'entry_date' => now()->subDays(2)->toDateString(),
                'service_type' => 'Beton Dökümü',
            ],
            [
                'work_description' => 'B blok 5. kat döşeme beton dökümü',
                'quantity' => 42,
                'unit' => 'm3',
                'worked_hours' => 18,
                'crew_size' => 12,
                'status' => 'submitted',
                'attachments' => [
                    ['name' => 'Saha Fotoğrafı 1', 'url' => '#'],
                ],
                'notes' => 'Şantiye şefi onayı bekleniyor.',
                'submitted_at' => now()->subDay(),
            ]
        );

        SubcontractorMaterialEntry::firstOrCreate(
            [
                'subcontractor_contract_id' => $contract->id,
                'project_id' => $project->id,
                'subcontractor_user_id' => $user->id,
                'delivery_date' => now()->subDays(5)->toDateString(),
                'material_name' => 'Tünel Kalıp Paneli',
            ],
            [
                'specification' => '2.5m x 3m, sıcak daldırma galvaniz',
                'quantity' => 18,
                'unit' => 'adet',
                'unit_price' => 9500,
                'status' => 'approved',
                'attachments' => [
                    ['name' => 'İrsaliye.pdf', 'url' => '#'],
                ],
                'notes' => 'Depoya teslim edildi.',
                'submitted_at' => now()->subDays(4),
                'approved_at' => now()->subDays(3),
            ]
        );
    }
}
