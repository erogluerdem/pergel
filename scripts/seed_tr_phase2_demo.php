<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EWaybill;
use App\Models\BuildingInspection;
use App\Models\SubcontractorDeduction;
use App\Models\PriceDifferenceCalculation;
use App\Models\SiteDailyLog;
use App\Models\VatReturn;
use App\Models\RealEstateValuation;

echo "Seeding Phase 2 Demo Data...\n";

// 1. EWaybill
EWaybill::truncate();
EWaybill::create([
    'waybill_number' => 'IRS-2026-001',
    'issue_date' => '2026-08-10',
    'supplier_name' => 'Akçansa Çimento A.Ş.',
    'supplier_vkn_tckn' => '1234567890',
    'material_description' => 'CEM I 42.5 R Dökme Çimento',
    'quantity' => 150.00,
    'unit' => 'Ton',
    'status' => 'onaylandi'
]);

// 2. BuildingInspection
BuildingInspection::truncate();
BuildingInspection::create([
    'project_name' => 'Vadi İstanbul Projesi',
    'ydk_company_name' => 'Güven Yapı Denetim Ltd. Şti.',
    'document_type' => 'Seviye Tespit Tutanağı (%20)',
    'progress_percentage' => 20.00,
    'amount' => 45000.00,
    'due_date' => '2026-08-20',
    'status' => 'beklemede'
]);

// 3. SubcontractorDeduction
SubcontractorDeduction::truncate();
SubcontractorDeduction::create([
    'subcontractor_name' => 'Kardeşler Kalıp Beton',
    'hakedis_period' => '2026 Temmuz Hakedişi',
    'gross_amount' => 200000.00,
    'teminat_kesintisi_rate' => 5.00,
    'teminat_amount' => 10000.00,
    'damga_vergisi_rate' => 0.00948,
    'damga_vergisi_amount' => 1896.00,
    'stopaj_rate' => 3.00,
    'stopaj_amount' => 6000.00,
    'net_amount' => 182104.00
]);

// 4. PriceDifferenceCalculation
PriceDifferenceCalculation::truncate();
PriceDifferenceCalculation::create([
    'contract_name' => 'İzmir Şehir Hastanesi Kaba İnşaat İşi',
    'base_index_month' => '2025-01',
    'base_index_value' => 1500.25,
    'current_index_month' => '2026-07',
    'current_index_value' => 2850.40,
    'hakedis_amount' => 1500000.00,
    'price_difference_amount' => 1350000.00
]);

// 5. SiteDailyLog
SiteDailyLog::truncate();
SiteDailyLog::create([
    'log_date' => '2026-08-13',
    'weather_condition' => 'Güneşli, Rüzgarlı',
    'temperature' => 32,
    'total_workers' => 45,
    'subcontractor_workers' => 120,
    'manufacturing_details' => 'A Blok 4. kat döşeme kalıbı çakıldı, C Blok temel betonu döküldü (350m3).',
    'site_manager_name' => 'Ahmet Yılmaz (İnş. Müh.)',
    'is_signed' => true
]);

// 6. VatReturn
VatReturn::truncate();
VatReturn::create([
    'period' => '2026-2. Çeyrek',
    'project_type' => 'Kentsel Dönüşüm (%1 KDV)',
    'total_kdv1_sales' => 10000000.00,
    'total_kdv10_sales' => 0,
    'total_kdv20_purchases' => 12000000.00,
    'calculated_return_amount' => 2300000.00,
    'ymm_report_status' => 'hazirlaniyor'
]);

// 7. RealEstateValuation
RealEstateValuation::truncate();
RealEstateValuation::create([
    'asset_name' => 'Kadıköy Moda 3+1 Daire (Takas Gelen)',
    'valuation_company' => 'Gedik Gayrimenkul Değerleme A.Ş.',
    'spk_license_number' => 'SPK-GMD-98765',
    'report_date' => '2026-08-01',
    'previous_value' => 15000000.00,
    'current_value' => 18500000.00,
    'report_file_url' => '/uploads/reports/moda_ekspertiz.pdf'
]);

echo "Done.\n";
