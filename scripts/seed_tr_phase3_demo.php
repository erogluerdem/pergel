<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\FleetTelemetry;
use App\Models\AiTimesheetAnalyzer;
use App\Models\BudgetSimulator;
use App\Models\WasteManagement;
use App\Models\EAgreement;

echo "Seeding Phase 3 Demo Data...\n";

// 1. FleetTelemetry
FleetTelemetry::truncate();
FleetTelemetry::create([
    'vehicle_plate' => '34 ABC 123',
    'vehicle_type' => 'Hafriyat Kamyonu',
    'record_date' => '2026-08-13',
    'operating_hours' => 8.5,
    'idle_hours' => 2.1,
    'fuel_consumed_liters' => 65.4,
    'operator_name' => 'Kemal Usta'
]);

// 2. AiTimesheetAnalyzer
AiTimesheetAnalyzer::truncate();
AiTimesheetAnalyzer::create([
    'subcontractor_name' => 'Yıldırım Elektrik Taşeronu',
    'period' => '2026-07',
    'claimed_mandays' => 450,
    'actual_turnstile_mandays' => 380,
    'discrepancy_percentage' => 15.55,
    'ai_risk_assessment' => 'Yüksek risk. Turnike kayıtlarında pazar günleri giriş olmamasına rağmen hakedişte fazla mesai gösterilmiş.',
    'status' => 'itiraz_var'
]);

// 3. BudgetSimulator
BudgetSimulator::truncate();
BudgetSimulator::create([
    'scenario_name' => 'Kötü Senaryo (Döviz Şoku %20 Artış)',
    'usd_exchange_rate' => 42.50,
    'inflation_expectation' => 55.0,
    'current_remaining_budget' => 50000000.00,
    'simulated_projected_cost' => 62500000.00,
    'variance_amount' => -12500000.00,
    'risk_level' => 'Yuksek'
]);

// 4. WasteManagement
WasteManagement::truncate();
WasteManagement::create([
    'disposal_date' => '2026-08-10',
    'waste_type' => 'Tehlikeli Atık (Asbest İzolasyon)',
    'quantity_tons' => 4.5,
    'disposal_facility' => 'İSTAÇ Odayeri Bertaraf Tesisi',
    'motat_receipt_no' => 'MTT-847291',
    'environmental_officer' => 'Ayşe Çevre Müh.',
    'status' => 'teslim_edildi'
]);

// 5. EAgreement
EAgreement::truncate();
EAgreement::create([
    'partner_name' => 'Koçtaş Yapı Marketleri A.Ş.',
    'partner_tax_no' => '5700085421',
    'period_month' => '2026-07',
    'our_balance' => 450000.00,
    'partner_balance' => 450000.00,
    'mutabakat_type' => 'Cari Bakiye',
    'status' => 'mutabik_kalindi'
]);

echo "Done.\n";
