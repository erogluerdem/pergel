<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Rfi;
use App\Models\Submittal;
use App\Models\MeetingMinute;
use App\Models\ActionItem;
use App\Models\User;
use Carbon\Carbon;

class DemoProjectCommunicationSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        if ($projects->isEmpty()) return;

        $adminUser = User::first();
        $email = $adminUser ? $adminUser->email : 'admin@pergel.com';

        foreach ($projects as $p) {
            // Seed RFIs
            if (Rfi::where('project_id', $p->id)->count() == 0) {
                Rfi::create([
                    'project_id' => $p->id,
                    'rfi_no' => 'RFI-' . $p->id . '-001',
                    'subject' => 'Bodrum Kat Perde Donatı Kesit Çelişkisi ve Detay Talebi',
                    'description' => 'Mimari Pafta A-104 ile Statik S-201 arasında bodrum kat perde kalınlığı ve donatı aralığı farklı görünmektedir. Tarafımıza açıklayıcı detay kesiti iletilmesini rica ederiz.',
                    'assigned_to' => 'statik.muhendis@pergel.com',
                    'due_date' => Carbon::now()->addDays(5),
                    'status' => 'open',
                ]);

                Rfi::create([
                    'project_id' => $p->id,
                    'rfi_no' => 'RFI-' . $p->id . '-002',
                    'subject' => 'Çatı Havalandırma Şaftı Detayı ve Asma Tavan Kotu',
                    'description' => 'MEP projesinde havalandırma kanallarının geçtiği şaft alanları mimari asma tavan kotunu 15 cm aşağı düşürmektedir. Şaft kanal geçiş revizyonu talep olunur.',
                    'assigned_to' => $email,
                    'due_date' => Carbon::now()->subDays(2),
                    'status' => 'pending',
                ]);

                Rfi::create([
                    'project_id' => $p->id,
                    'rfi_no' => 'RFI-' . $p->id . '-003',
                    'subject' => 'Sığınak Kapısı Statik Anchorage Ve Sertifikasyon Onayı',
                    'description' => 'Tedarik edilen sığınak kapılarının dübel anchorage onay belgesi kontrol edilmiş ve idarece onaylanmıştır.',
                    'assigned_to' => 'saha.sefi@pergel.com',
                    'due_date' => Carbon::now()->subDays(10),
                    'status' => 'closed',
                ]);
            }

            // Seed Submittals
            if (Submittal::where('project_id', $p->id)->count() == 0) {
                Submittal::create([
                    'project_id' => $p->id,
                    'submittal_no' => 'SUB-' . $p->id . '-001',
                    'submitted_by' => 'yapi.glass@pergel.com',
                    'description' => 'Alüminyum Giydirme Cephe Profil ve Isıcam Numune Onay Dosyası',
                    'reviewers' => [$email, 'mimari.kontrolluk@pergel.com'],
                    'due_date' => Carbon::now()->addDays(7),
                    'status' => 'pending',
                ]);

                Submittal::create([
                    'project_id' => $p->id,
                    'submittal_no' => 'SUB-' . $p->id . '-002',
                    'submitted_by' => 'elektrotek@pergel.com',
                    'description' => 'Ana Dağıtım Panoları & Trafo Grubu Tip Test Raporları ve Kataloğu',
                    'reviewers' => [$email],
                    'due_date' => Carbon::now()->subDays(3),
                    'status' => 'approved',
                ]);

                Submittal::create([
                    'project_id' => $p->id,
                    'submittal_no' => 'SUB-' . $p->id . '-003',
                    'submitted_by' => 'oyak.beton@pergel.com',
                    'description' => 'C40/50 Hazır Beton Reçetesi ve Agrega Isı Belgesi',
                    'reviewers' => [$email],
                    'due_date' => Carbon::now()->subDays(12),
                    'status' => 'approved',
                ]);
            }

            // Seed Meeting Minutes & Action Items
            if (MeetingMinute::where('project_id', $p->id)->count() == 0) {
                $m1 = MeetingMinute::create([
                    'project_id' => $p->id,
                    'title' => 'Haftalık Saha İlerleme ve Koordinasyon Toplantısı #12',
                    'meeting_date' => Carbon::now()->subDays(4),
                    'participants' => [$email, 'proje.muduru@pergel.com', 'saha.sefi@pergel.com', 'taseron.kaya@pergel.com'],
                    'notes' => "1. Kaba inşaat imalatlarında blok A betonajı perşembe gününe kadar tamamlanacak.\n2. Elektrik tesisat şaft imalatlarında saha çakışmaları görüşüldü.\n3. İSG emniyet kemeri kontrolleri artırılacak.",
                ]);

                ActionItem::create([
                    'project_id' => $p->id,
                    'meeting_id' => $m1->id,
                    'title' => 'A Blok 3. Kat Kiriş Donatı Kontrolü ve Test Raporu Sunumu',
                    'assigned_to' => $email,
                    'due_date' => Carbon::now()->addDays(3),
                    'status' => 'open',
                    'notes' => 'Yapı denetim firmasına numune beton test sonuçları teslim edilecek.',
                ]);

                ActionItem::create([
                    'project_id' => $p->id,
                    'meeting_id' => $m1->id,
                    'title' => 'Kule Vinç Emniyet ve Periyodik Muayene Belgesinin Yenilenmesi',
                    'assigned_to' => 'saha.sefi@pergel.com',
                    'due_date' => Carbon::now()->subDays(1),
                    'status' => 'open',
                    'notes' => 'Makine mühendisleri odası onaylı kontrol raporu alınacak.',
                ]);

                $m2 = MeetingMinute::create([
                    'project_id' => $p->id,
                    'title' => 'İSG ve Çevre Sağlığı Aylık Değerlendirme Oturumu',
                    'meeting_date' => Carbon::now()->subDays(15),
                    'participants' => [$email, 'isg.uzmani@pergel.com'],
                    'notes' => 'Saha genelinde 0 iş kazası hedefi yakalandı. Eğitim katılım belgeleri dosyalandı.',
                ]);

                ActionItem::create([
                    'project_id' => $p->id,
                    'meeting_id' => $m2->id,
                    'title' => 'Yangın Tüpleri ve Acil Kaçış Levhalarının Kontrolü',
                    'assigned_to' => 'isg.uzmani@pergel.com',
                    'due_date' => Carbon::now()->subDays(10),
                    'status' => 'closed',
                    'notes' => 'Tüm şantiye katlarında kontrol tamamlandı.',
                ]);
            }
        }
    }
}
