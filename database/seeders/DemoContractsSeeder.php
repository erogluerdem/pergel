<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Project;
use App\Models\ContractTemplate;
use App\Models\ContractClause;
use App\Models\User;
use Carbon\Carbon;

class DemoContractsSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::first();
        $projects = Project::all();
        $p1 = $projects->first();

        // 1. Seed Templates if empty
        if (ContractTemplate::count() == 0) {
            ContractTemplate::create([
                'title' => 'Taşeron İmalat Sözleşmesi (Standart Tip)',
                'body' => "MADDE 1 - TARAFLAR\nİşbu sözleşme Yüklenici Firma ile Taşeron Firma arasında akdedilmiştir.\n\nMADDE 2 - İŞİN KONUSU\nŞantiye sahasında gerçekleştirilecek betonarme, kalıp ve demir işçiliği imalatları kapsar.\n\nMADDE 3 - İŞİN SÜRESİ VE CEZALAR\nİş teslim süresi ihlal edildiğinde günlük %0.5 gecikme cezası tatbik edilir.",
                'created_by' => $adminUser?->id,
                'status' => 'active'
            ]);

            ContractTemplate::create([
                'title' => 'Malzeme Tedarik & Satın Alma Sözleşmesi',
                'body' => "MADDE 1 - TEDARİK ŞARTLARI\nTedarikçi firma teknik şartnamede belirtilen kalite standartlarındaki malzemeleri temin etmekle yükümlüdür.\n\nMADDE 2 - ÖDEME PLANI\nMalzemenin şantiyeye teslimi ve kalite kontrolden geçmesi müteakip 30 gün vadeli çek ile ödenir.",
                'created_by' => $adminUser?->id,
                'status' => 'active'
            ]);

            ContractTemplate::create([
                'title' => 'Mühendislik & Müşavirlik Hizmet Sözleşmesi',
                'body' => "MADDE 1 - HİZMET KAPSAMI\nProjenin statik kontrolü, ruhsat süreçleri ve saha denetim hizmetleri kapsanmaktadır.",
                'created_by' => $adminUser?->id,
                'status' => 'active'
            ]);
        }

        // 2. Seed Clauses if empty
        if (ContractClause::count() == 0) {
            ContractClause::create([
                'title' => 'İş Sağlığı ve Güvenliği (İSG) Cezai Şartı',
                'content' => 'Şantiye sahasında baret, yelek ve emniyet kemeri takmayan taşeron personeli tespit edildiğinde her bir kural ihlali için 5.000 TL cezai işlem uygulanır.',
                'category' => 'İSG & Emniyet',
                'status' => 'active',
                'created_by' => $adminUser?->id
            ]);

            ContractClause::create([
                'title' => 'Teminat Mektubu ve Kesinti Şartı',
                'content' => 'Sözleşme bedelinin %10 oranında kesin teminat mektubu verilecektir. Her hakedişten %5 nakit teminat kesintisi yapılır.',
                'category' => 'Finans & Teminat',
                'status' => 'active',
                'created_by' => $adminUser?->id
            ]);

            ContractClause::create([
                'title' => 'Gizlilik ve KVKK Protokolü',
                'content' => 'Taraflar proje süreçlerine ait teknik verileri ve müşteri bilgilerini 3. şahıslarla paylaşamaz.',
                'category' => 'Hukuki & Gizlilik',
                'status' => 'active',
                'created_by' => $adminUser?->id
            ]);
        }

        // 3. Seed Realistic Demo Contracts
        $demoContracts = [
            [
                'name' => 'Kaba İnşaat Betonarme & Kalıp Taşeronluk Sözleşmesi',
                'number' => 'SZ-2025-001',
                'company' => 'Kaya İnşaat ve Beton Yapı A.Ş.',
                'type' => 'Taşeron',
                'value' => 12500000.00,
                'paid' => 4500000.00,
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->addMonths(9),
                'notes' => '1. Etap blokların kaba inşaat kalıp ve demir imalatlarını kapsar.',
            ],
            [
                'name' => 'Elektrik & Zayıf Akım Tesisatı Tedarik ve Montaj Sözleşmesi',
                'number' => 'SZ-2025-002',
                'company' => 'ElektroTek Mühendislik Ltd. Şti.',
                'type' => 'Tedarik & İmalat',
                'value' => 6800000.00,
                'paid' => 2000000.00,
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(1),
                'end_date' => Carbon::now()->addMonths(8),
                'notes' => 'Kule panoları, trafo grubu ve zayıf akım kablolaması dahildir.',
            ],
            [
                'name' => 'Alüminyum Giydirme Cephe & Cam Sistemleri Sözleşmesi',
                'number' => 'SZ-2025-003',
                'company' => 'YapıGlass Alüminyum Sistemleri',
                'type' => 'Taşeron',
                'value' => 8400000.00,
                'paid' => 1500000.00,
                'status' => 'pending',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addMonths(7),
                'notes' => 'Isı yalıtımlı temperli silikon cephe sistemleri montajı.',
            ],
            [
                'name' => 'Hazır Beton ve Agrega Tedarik Sözleşmesi',
                'number' => 'SZ-2025-004',
                'company' => 'Oyak Beton Sanayi ve Ticaret A.Ş.',
                'type' => 'Tedarik',
                'value' => 18200000.00,
                'paid' => 9800000.00,
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(5),
                'end_date' => Carbon::now()->addMonths(5),
                'notes' => 'C35 ve C40 sınıfı hazır beton sevkiyat protokolü.',
            ],
            [
                'name' => 'Harita & Geoteknik Zemin Etüt Hizmet Sözleşmesi',
                'number' => 'SZ-2025-005',
                'company' => 'ZeminTek Mühendislik Müşavirlik',
                'type' => 'Hizmet',
                'value' => 950000.00,
                'paid' => 950000.00,
                'status' => 'completed',
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->subMonths(1),
                'notes' => 'Sondaj ve kazık test raporları eksiksiz teslim alındı.',
            ],
        ];

        foreach ($demoContracts as $data) {
            Contract::firstOrCreate(
                ['number' => $data['number']],
                array_merge($data, [
                    'project_id' => $p1?->id,
                    'created_by' => $adminUser?->id,
                ])
            );
        }
    }
}
