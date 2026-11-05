<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\ServiceRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $project = Project::first();

        // Clear existing demo assets if re-seeding
        Asset::query()->forceDelete();

        // 1. Assets with Geolocation (Latitude / Longitude for Interactive Map)
        $a1 = Asset::create([
            'asset_code' => 'AST-VRF-001',
            'name' => 'VRF Merkezi İklimlendirme & Chiller Santrali',
            'description' => 'A-Blok çatı katı ana iklimlendirme ve soğutma grubu santrali.',
            'category' => 'HVAC & İklimlendirme',
            'brand' => 'Daikin',
            'model' => 'VRV-IV X 40HP',
            'serial_number' => 'DK-994821-X',
            'purchase_date' => now()->subMonths(18),
            'purchase_price' => 850000.00,
            'current_value' => 760000.00,
            'location' => 'A-Blok Çatı Katı Teknik Alan',
            'latitude' => 41.008230,
            'longitude' => 28.978410,
            'floor_number' => 5,
            'status' => 'active',
            'condition' => 'good',
            'last_maintenance_date' => now()->subMonths(1),
            'next_maintenance_date' => now()->addDays(15),
        ]);

        $a2 = Asset::create([
            'asset_code' => 'AST-GEN-002',
            'name' => 'Standby Dizel Jeneratör Seti 550 kVA',
            'description' => 'Şantiye ve tesis acil durum kesintisiz güç jeneratörü.',
            'category' => 'Elektrik & Enerji',
            'brand' => 'Aksa',
            'model' => 'APD-550C',
            'serial_number' => 'AKS-44312',
            'purchase_date' => now()->subMonths(24),
            'purchase_price' => 1250000.00,
            'current_value' => 1100000.00,
            'location' => 'Zemin Kat Jeneratör Odası',
            'latitude' => 41.009450,
            'longitude' => 28.979200,
            'floor_number' => -1,
            'status' => 'active',
            'condition' => 'excellent',
            'last_maintenance_date' => now()->subMonths(2),
            'next_maintenance_date' => now()->subDays(3), // Overdue!
        ]);

        $a3 = Asset::create([
            'asset_code' => 'AST-ELE-003',
            'name' => '10 Kişilik MRL Sedye & Yolcu Asansörü',
            'description' => 'B-Blok yüksek hızlı frekans kontrollü yolcu asansör grubu.',
            'category' => 'Dikey Taşıma & Asansör',
            'brand' => 'Otis',
            'model' => 'GeN2 Flex',
            'serial_number' => 'OTS-88391',
            'purchase_date' => now()->subMonths(12),
            'purchase_price' => 950000.00,
            'current_value' => 890000.00,
            'location' => 'B-Blok Asansör Koyu',
            'latitude' => 41.007100,
            'longitude' => 28.976800,
            'floor_number' => 0,
            'status' => 'active',
            'condition' => 'good',
            'last_maintenance_date' => now()->subMonths(1),
            'next_maintenance_date' => now()->addDays(8),
        ]);

        // 2. Maintenances
        Maintenance::create([
            'asset_id' => $a1->id,
            'type' => 'preventive',
            'title' => 'VRF Santral Üç Aylık Filtre & Gaz Basınç Kontrolü',
            'description' => 'Kompresör basınç değerleri, evaporatör temizliği ve soğutucu gaz seviyesi ölçümü.',
            'priority' => 'high',
            'status' => 'pending',
            'assigned_to' => $admin?->id,
            'scheduled_date' => now()->addDays(5),
            'cost' => 12500.00,
        ]);

        Maintenance::create([
            'asset_id' => $a2->id,
            'type' => 'corrective',
            'title' => 'Jeneratör Yağ Değişimi & Akü Şarj Panosu Bakımı',
            'description' => 'Aşırı yük altında otomatik transfer panosu geçiş gecikmesi kontrol edildi.',
            'priority' => 'high',
            'status' => 'in_progress',
            'assigned_to' => $admin?->id,
            'scheduled_date' => now()->subDays(2),
            'cost' => 18400.00,
        ]);

        Maintenance::create([
            'asset_id' => $a3->id,
            'type' => 'preventive',
            'title' => 'Asansör Aylık Periyodik Halat & Kuyu Bakımı',
            'description' => 'TMMOB Asansör kontrol yönetmeliğine uygun periyodik güvenlik testi.',
            'priority' => 'medium',
            'status' => 'completed',
            'assigned_to' => $admin?->id,
            'scheduled_date' => now()->subDays(10),
            'completed_date' => now()->subDays(9),
            'cost' => 6500.00,
        ]);

        // 3. Service Requests
        ServiceRequest::create([
            'subject' => 'A-Blok 3. Kat Ofis İklimlendirme Soğutmuyor',
            'location' => 'A-Blok Kat: 3 Ofis 304',
            'description' => 'VRF iç ünite hava üflüyor fakat soğutma yapmıyor. Sıcaklık 27°C civarında.',
            'status' => 'pending',
            'assigned_to' => $admin?->id,
            'project_id' => $project?->id,
            'sla_target' => 86400, // 24 hours
        ]);

        ServiceRequest::create([
            'subject' => 'B-Blok Zemin Kat Otopark Aydınlatma Sensör Arızası',
            'location' => 'B-Blok Kapalı Otopark P-1',
            'description' => 'P-1 seviyesindeki hareket sensörlü LED armatürler sürekli açık kalıyor.',
            'status' => 'in_progress',
            'assigned_to' => $admin?->id,
            'project_id' => $project?->id,
            'sla_target' => 43200, // 12 hours
        ]);

        ServiceRequest::create([
            'subject' => 'Yangın Hidrant Basınç Düşüşü Bildirimi',
            'location' => 'Şantiye Çevre Güvenlik Hattı',
            'description' => 'Yangın pompası haftalık otomatik testinde 4 bar basınç kaybı tespit edildi.',
            'status' => 'completed',
            'assigned_to' => $admin?->id,
            'project_id' => $project?->id,
            'sla_target' => 14400, // 4 hours
            'resolved_at' => now()->subHours(2),
            'resolution_time' => 7200,
        ]);
    }
}
