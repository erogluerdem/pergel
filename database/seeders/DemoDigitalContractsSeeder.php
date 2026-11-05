<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\ContractSignature;
use App\Models\Project;
use App\Models\User;

class DemoDigitalContractsSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        $adminUser = User::first();

        $contracts = [
            [
                'title' => 'Mavi Kule Kat 14 Daire 101 Ön Satış Sözleşmesi',
                'name' => 'Mavi Kule Kat 14 Daire 101 Ön Satış Sözleşmesi',
                'number' => 'DC-2026-001',
                'project_id' => $projects[0]->id,
                'company' => 'Pergel Gayrimenkul A.Ş.',
                'type' => 'Gayrimenkul Satış Sözleşmesi',
                'value' => 12500000.00,
                'paid' => 3125000.00,
                'status' => 'active',
                'effective_date' => now()->subDays(10),
                'expiration_date' => now()->addDays(355),
                'created_by' => $adminUser?->id,
                'signed_at' => now()->subDays(9),
                'signed_by' => 'Ahmet Yılmaz (Müşteri)',
                'content' => '<h3>1. TARAFLAR VE KONU</h3><p>İşbu sözleşme Pergel Gayrimenkul A.Ş. ile Ahmet Yılmaz arasında Mavi Kule 101 no\'lu bağımsız bölümün satış taahhüdünü kapsar.</p><h3>2. ÖDEME ŞARTLARI</h3><p>%25 peşinat ₺3.125.000 ödenmiş olup, kalan ₺9.375.000 tutar 12 eşit taksitle ödenecektir.</p>',
                'notes' => 'Müşteri e-imza onaylama işlemi başarıyla tamamlandı.',
                'signatures' => [
                    [
                        'signer_name' => 'Ahmet Yılmaz',
                        'signer_email' => 'ahmet.yilmaz@gmail.com',
                        'signer_role' => 'Alıcı / Müşteri',
                        'status' => 'signed',
                        'signed_at' => now()->subDays(9),
                        'token' => \Str::random(40)
                    ],
                    [
                        'signer_name' => 'Erdem Eroğlu',
                        'signer_email' => 'erdem@pergel.com',
                        'signer_role' => 'Satış Direktörü',
                        'status' => 'signed',
                        'signed_at' => now()->subDays(10),
                        'token' => \Str::random(40)
                    ]
                ]
            ],
            [
                'title' => 'Vadistanbul Ofis 301 Kurumsal Kiralama Sözleşmesi',
                'name' => 'Vadistanbul Ofis 301 Kurumsal Kiralama Sözleşmesi',
                'number' => 'DC-2026-002',
                'project_id' => $projects[1]->id ?? $projects[0]->id,
                'company' => 'Aksis Teknoloji A.Ş.',
                'type' => 'Ticari Kiralama Sözleşmesi',
                'value' => 24500000.00,
                'paid' => 6125000.00,
                'status' => 'active',
                'effective_date' => now()->subDays(5),
                'expiration_date' => now()->addDays(725),
                'created_by' => $adminUser?->id,
                'signed_at' => now()->subDays(4),
                'signed_by' => 'Mehmet Can (Genel Müdür)',
                'content' => '<h3>1. KİRALAMA ŞARTLARI</h3><p>Vadistanbul Plaza 3. Kat 301 no\'lu 210 m² ofis alanının 24 ay süreyle Aksis Teknoloji A.Ş.\'ye kiralanması hususudur.</p>',
                'notes' => 'Kira teminat mektubu teslim alındı.',
                'signatures' => [
                    [
                        'signer_name' => 'Mehmet Can',
                        'signer_email' => 'mehmet@aksisteknoloji.com',
                        'signer_role' => 'Kiracı Temsilcisi',
                        'status' => 'signed',
                        'signed_at' => now()->subDays(4),
                        'token' => \Str::random(40)
                    ]
                ]
            ],
            [
                'title' => 'Gökdağ Villaları V-08 Mimari Uygulama Sözleşmesi',
                'name' => 'Gökdağ Villaları V-08 Mimari Uygulama Sözleşmesi',
                'number' => 'DC-2026-003',
                'project_id' => $projects[2]->id ?? $projects[0]->id,
                'company' => 'Tasarım Stüdyo Ltd.',
                'type' => 'Mimari Taşeron Sözleşmesi',
                'value' => 65000000.00,
                'paid' => 0.00,
                'status' => 'draft',
                'effective_date' => now(),
                'expiration_date' => now()->addDays(180),
                'created_by' => $adminUser?->id,
                'content' => '<h3>1. İŞİN KAPSAMI</h3><p>Gökdağ Villaları V-08 villa ünitesinin tüm iç mimari, peyzaj ve akıllı ev altyapısının anahtar teslim uygulanmasıdır.</p>',
                'notes' => 'Hukuk departmanı incelemesinde.',
                'signatures' => [
                    [
                        'signer_name' => 'Selin Arslan',
                        'signer_email' => 'selin@tasarimstudio.com',
                        'signer_role' => 'Yüklenici Mimar',
                        'status' => 'pending',
                        'token' => \Str::random(40)
                    ]
                ]
            ],
            [
                'title' => 'Port Residence Penthouse PH-2501 Devir Sözleşmesi',
                'name' => 'Port Residence Penthouse PH-2501 Devir Sözleşmesi',
                'number' => 'DC-2026-004',
                'project_id' => $projects[0]->id,
                'company' => 'Global Real Estate Investment Fund',
                'type' => 'Devir & Temlik Sözleşmesi',
                'value' => 48000000.00,
                'paid' => 48000000.00,
                'status' => 'active',
                'effective_date' => now()->subDays(20),
                'expiration_date' => now()->addDays(30),
                'created_by' => $adminUser?->id,
                'signed_at' => now()->subDays(19),
                'signed_by' => 'John Smith (Yatırım Temsilcisi)',
                'content' => '<h3>1. DEVİR ŞARTLARI</h3><p>PH-2501 Penthouse dairesinin mülkiyet ve işletme haklarının tam bedel ödenerek devridir.</p>',
                'notes' => 'Ödeme %100 tamamlandı.',
                'signatures' => [
                    [
                        'signer_name' => 'John Smith',
                        'signer_email' => 'john.smith@globalfund.com',
                        'signer_role' => 'Yatırımcı',
                        'status' => 'signed',
                        'signed_at' => now()->subDays(19),
                        'token' => \Str::random(40)
                    ]
                ]
            ],
            [
                'title' => 'Çarşı Blok DK-04 Kiralama ve Ortak Alan Şartnamesi',
                'name' => 'Çarşı Blok DK-04 Kiralama ve Ortak Alan Şartnamesi',
                'number' => 'DC-2026-005',
                'project_id' => $projects[1]->id ?? $projects[0]->id,
                'company' => 'Mega Marketler Zinciri A.Ş.',
                'type' => 'Hizmet & Kiralama Sözleşmesi',
                'value' => 32000000.00,
                'paid' => 32000000.00,
                'status' => 'expired',
                'effective_date' => now()->subDays(400),
                'expiration_date' => now()->subDays(10),
                'created_by' => $adminUser?->id,
                'content' => '<h3>1. SÖZLEŞME SÜRESİ</h3><p>İşbu 1 yıllık kiralama sözleşmesi süresi dolmuş olup yenileme protokolü hazırlanmaktadır.</p>',
                'notes' => 'Sözleşme süresi doldu, yenileme bekleniyor.',
                'signatures' => []
            ]
        ];

        foreach ($contracts as $contractData) {
            $signatures = $contractData['signatures'] ?? [];
            unset($contractData['signatures']);

            $contract = Contract::updateOrCreate(
                ['number' => $contractData['number']],
                $contractData
            );

            foreach ($signatures as $sig) {
                if (!isset($sig['email']) && isset($sig['signer_email'])) {
                    $sig['email'] = $sig['signer_email'];
                }
                $contract->signatures()->create($sig);
            }
        }
    }
}
