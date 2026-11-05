<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Standard;
use App\Models\User;

class DemoStandardsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create([
            'name' => 'Sistem Yöneticisi',
            'email' => 'admin@pergel.com',
            'is_admin' => true,
        ]);

        $standardsData = [
            [
                'name' => 'ISO 9001:2015 Kalite Yönetim Sistemi',
                'code' => 'STD-ISO-9001',
                'description' => 'Şantiye ve inşaat projelerinde kalite yönetim süreçleri ve müşteri memnuniyeti standartları.',
                'type' => 'quality',
                'status' => 'active',
                'version' => 'v2015',
                'effective_date' => now()->subYears(2)->format('Y-m-d'),
                'expiry_date' => now()->addYears(2)->format('Y-m-d'),
                'category' => 'iso',
                'priority' => 1,
                'compliance_level' => 'mandatory',
                'document_url' => 'https://example.com/docs/iso9001.pdf',
                'tags' => ['kalite', 'iso', 'yönetim'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name' => 'TS EN 206-1 Beton Standartları & Performans Şartları',
                'code' => 'STD-TS-EN-206',
                'description' => 'Taze ve sertleşmiş beton özellikleri, basınç dayanım sınıfları (C30/37, C40/50) ve numune alma deney kılavuzu.',
                'type' => 'technical',
                'status' => 'active',
                'version' => 'v2021',
                'effective_date' => now()->subYear()->format('Y-m-d'),
                'expiry_date' => now()->addYears(3)->format('Y-m-d'),
                'category' => 'en',
                'priority' => 1,
                'compliance_level' => 'mandatory',
                'document_url' => 'https://example.com/docs/tsen206.pdf',
                'tags' => ['beton', 'şantiye', 'deney', 'statik'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name' => 'ISO 45001:2018 İş Sağlığı ve Güvenliği Yönetimi',
                'code' => 'STD-ISO-45001',
                'description' => 'Şantiye alanlarında iş kazası önleme, kişisel koruyucu donanım (KKD) kullanımı ve risk analizi projenin zorunlu şartnamesidir.',
                'type' => 'safety',
                'status' => 'active',
                'version' => 'v2018',
                'effective_date' => now()->subYears(3)->format('Y-m-d'),
                'expiry_date' => now()->addYear()->format('Y-m-d'),
                'category' => 'iso',
                'priority' => 1,
                'compliance_level' => 'mandatory',
                'document_url' => 'https://example.com/docs/iso45001.pdf',
                'tags' => ['isg', 'güvenlik', 'şantiye'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name' => 'TS 500 Betonarme Yapıların Tasarım ve Yapım Kuralları',
                'code' => 'STD-TS-500',
                'description' => 'Türkiye bina deprem yönetmeliği uyarınca taşıyıcı sistem demir donatı bağlama, paspayı ve boyuna donatı pas payı limitleri.',
                'type' => 'technical',
                'status' => 'active',
                'version' => 'v2000',
                'effective_date' => now()->subYears(5)->format('Y-m-d'),
                'expiry_date' => null,
                'category' => 'ts',
                'priority' => 2,
                'compliance_level' => 'mandatory',
                'document_url' => 'https://example.com/docs/ts500.pdf',
                'tags' => ['betonarme', 'statik', 'deprem'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name' => 'ISO 14001:2015 Çevre Yönetim Sistemi',
                'code' => 'STD-ISO-14001',
                'description' => 'Şantiye atık yönetimi, hafriyat bertarafı ve gürültü/toz emisyon kontrol protokolleri.',
                'type' => 'environmental',
                'status' => 'active',
                'version' => 'v2015',
                'effective_date' => now()->subYears(2)->format('Y-m-d'),
                'expiry_date' => now()->addMonths(11)->format('Y-m-d'),
                'category' => 'iso',
                'priority' => 3,
                'compliance_level' => 'recommended',
                'document_url' => 'https://example.com/docs/iso14001.pdf',
                'tags' => ['çevre', 'atık', 'hafriyat'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name' => 'ASTM C39 Silindir Beton Kırım Deney Protokolü',
                'code' => 'STD-ASTM-C39',
                'description' => '7 ve 28 günlük küp ve silindir beton numunelerinin laboratuvar kırım deney şartları ve raporlama prosedürü.',
                'type' => 'performance',
                'status' => 'draft',
                'version' => 'v2023-rev',
                'effective_date' => now()->format('Y-m-d'),
                'expiry_date' => now()->addYears(2)->format('Y-m-d'),
                'category' => 'astm',
                'priority' => 2,
                'compliance_level' => 'recommended',
                'document_url' => 'https://example.com/docs/astmc39.pdf',
                'tags' => ['astm', 'kırım', 'lab'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]
        ];

        foreach ($standardsData as $data) {
            Standard::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
