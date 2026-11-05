<?php

namespace Database\Seeders;

use App\Models\Closeout;
use App\Models\CloseoutItem;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\WarrantyClaim;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoCloseoutSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        $vendors = Vendor::all();
        $equipments = Equipment::all();

        $closeoutTemplates = [
            [
                'status' => 'completed',
                'completion_percentage' => 100,
                'started_at' => now()->subMonths(6),
                'completed_at' => now()->subWeeks(2),
                'items' => [
                    ['category' => '"As-Built" Çizimler', 'item' => 'Mimari ve Statik Son Durum Projeleri (DWG & PDF)', 'status' => 'completed', 'documents' => ['mimari_as_built_v2.pdf', 'statik_aplikasyon_final.dwg'], 'notes' => 'Tüm mimari revizyonlar işlendi ve onaylandı.'],
                    ['category' => '"As-Built" Çizimler', 'item' => 'Mekanik ve Elektrik Tesisat As-Built Projeleri', 'status' => 'completed', 'documents' => ['hvac_as_built.pdf', 'tek_hat_semasi.pdf'], 'notes' => 'Saha test ve devreye alma ölçümleri eklendi.'],
                    ['category' => 'Garanti Belgeleri', 'item' => 'VRF İklimlendirme & Chiller Grubu Garanti Sertifikaları', 'status' => 'completed', 'documents' => ['daikin_garanti_belgesi.pdf'], 'notes' => '2 Yıl Yetkili Servis Garantisi başlatıldı.'],
                    ['category' => 'Garanti Belgeleri', 'item' => 'Asansör & Yürüyen Merdiven Garanti Belgeleri', 'status' => 'completed', 'documents' => ['schindler_garanti.pdf'], 'notes' => 'Yeşil etiket alındı ve fabrika garantisi devredildi.'],
                    ['category' => 'Kullanım Kılavuzları', 'item' => 'O&M (İşletme ve Bakım) Kılavuzları & Kullanım Manueli', 'status' => 'completed', 'documents' => ['bina_isletme_kilavuzu.pdf'], 'notes' => 'İşletme ekibine oryantasyon eğitimi verildi.'],
                    ['category' => 'Resmi Belgeler', 'item' => 'Yapı Kullanma İzin Belgesi (İskan Ruhsatı)', 'status' => 'completed', 'documents' => ['iskan_ruhsati_onayli.pdf'], 'notes' => 'Belediye onaylı aslı idareye teslim edildi.'],
                    ['category' => 'Resmi Belgeler', 'item' => 'SGK İlişiksizlik Belgesi & İtfaiye Uygunluk Raporu', 'status' => 'completed', 'documents' => ['sgk_ilisiksizlik.pdf', 'itfaiye_raporu.pdf'], 'notes' => 'Tüm idari dosyalar tamamlandı.'],
                    ['category' => 'Yedek Parça Listesi', 'item' => 'İşverene Teslim Edilen Seramik & Armatür Yedek Stokları', 'status' => 'completed', 'documents' => ['yedek_parca_teslim_tutanagi.pdf'], 'notes' => 'Saha ana ambarına 5 koli teslim edildi.'],
                ],
                'claims' => [
                    ['subject' => 'A-Blok 3. Kat VRF İç Ünite Drenaj Sızıntısı', 'notification_date' => now()->subDays(10), 'status' => 'closed', 'description' => '304 nolu ofiste VRF iç ünite drenaj tavasında tıkanıklık oluştu.'],
                    ['subject' => 'Otopark Sensörlü Aydınlatma Armatür Arızası', 'notification_date' => now()->subDays(5), 'status' => 'intervened', 'description' => 'B2 katı 14 adet LED armatür sensör kartı değişimi yapılıyor.'],
                ],
            ],
            [
                'status' => 'in_progress',
                'completion_percentage' => 65,
                'started_at' => now()->subMonths(2),
                'completed_at' => null,
                'items' => [
                    ['category' => '"As-Built" Çizimler', 'item' => 'Mimari ve Statik Son Durum Projeleri (DWG & PDF)', 'status' => 'completed', 'documents' => ['mimari_as_built_draft.pdf'], 'notes' => 'Mimar onayı bekleniyor.'],
                    ['category' => '"As-Built" Çizimler', 'item' => 'Mekanik ve Elektrik Tesisat As-Built Projeleri', 'status' => 'pending', 'documents' => [], 'notes' => 'Saha revizyon çizimleri devam ediyor.'],
                    ['category' => 'Garanti Belgeleri', 'item' => 'Jeneratör & Trafo Garanti Sertifikaları', 'status' => 'completed', 'documents' => ['aksa_garanti.pdf'], 'notes' => 'İlk çalıştırma testi yapıldı.'],
                    ['category' => 'Kullanım Kılavuzları', 'item' => 'Yangın İhbar & Otomasyon Kılavuzları', 'status' => 'pending', 'documents' => [], 'notes' => 'Tedarikçiden dijital doküman bekleniyor.'],
                    ['category' => 'Resmi Belgeler', 'item' => 'Şantiye Geçici Kabul Tutanağı', 'status' => 'completed', 'documents' => ['gecici_kabul_tutanagi.pdf'], 'notes' => 'Eksik listesi (Punch List) ile kabul edildi.'],
                    ['category' => 'Taşeron İletişim Listesi', 'item' => '7/24 Garanti Süreci Taşeron Rehberi', 'status' => 'completed', 'documents' => ['taseron_garanti_rehberi.xlsx'], 'notes' => 'Tüm irtibat numaraları güncellendi.'],
                ],
                'claims' => [
                    ['subject' => 'Ana Giriş Otomatik Döner Kapı Sensör Ayarı', 'notification_date' => now()->subDays(3), 'status' => 'assigned', 'description' => 'Rüzgarlı havalarda emniyet fotoseli devreye giriyor.'],
                ],
            ],
            [
                'status' => 'draft',
                'completion_percentage' => 25,
                'started_at' => now()->subWeeks(2),
                'completed_at' => null,
                'items' => [
                    ['category' => '"As-Built" Çizimler', 'item' => 'Saha Ölçüm & As-Built Çizim Dosyaları', 'status' => 'pending', 'documents' => [], 'notes' => 'Harita mühendisi ölçüm yapıyor.'],
                    ['category' => 'Garanti Belgeleri', 'item' => 'Çatı Su Yalıtımı Membran Garanti Belgesi', 'status' => 'pending', 'documents' => [], 'notes' => 'Su testi sonrası tanzim edilecek.'],
                    ['category' => 'Resmi Belgeler', 'item' => 'İtfaiye Yangın Tesisatı Uygunluk Raporu', 'status' => 'completed', 'documents' => ['itfaiye_basvuru_evraklari.pdf'], 'notes' => 'İnceleme günü bekleniyor.'],
                ],
                'claims' => [],
            ],
        ];

        $claimCounter = 101;

        foreach ($projects as $index => $project) {
            if (Closeout::where('project_id', $project->id)->exists()) {
                continue;
            }

            $template = $closeoutTemplates[$index % count($closeoutTemplates)];

            DB::transaction(function () use ($project, $template, $vendors, $equipments, &$claimCounter) {
                $closeout = Closeout::create([
                    'project_id' => $project->id,
                    'status' => $template['status'],
                    'completion_percentage' => $template['completion_percentage'],
                    'started_at' => $template['started_at'],
                    'completed_at' => $template['completed_at'],
                ]);

                foreach ($template['items'] as $itemData) {
                    CloseoutItem::create([
                        'closeout_id' => $closeout->id,
                        'category' => $itemData['category'],
                        'item' => $itemData['item'],
                        'status' => $itemData['status'],
                        'documents' => $itemData['documents'],
                        'notes' => $itemData['notes'],
                    ]);
                }

                foreach ($template['claims'] as $claimData) {
                    WarrantyClaim::create([
                        'closeout_id' => $closeout->id,
                        'claim_no' => 'WC-' . date('Y') . '-' . sprintf('%04d', $claimCounter++),
                        'subject' => $claimData['subject'],
                        'notification_date' => $claimData['notification_date'],
                        'equipment_id' => $equipments->isNotEmpty() ? $equipments->random()->id : null,
                        'contractor_id' => $vendors->isNotEmpty() ? $vendors->random()->id : null,
                        'status' => $claimData['status'],
                        'description' => $claimData['description'],
                    ]);
                }
            });
        }
    }
}
