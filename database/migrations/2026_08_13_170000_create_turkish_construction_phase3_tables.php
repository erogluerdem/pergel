<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. FleetTelemetry (IoT Araç/İş Makinesi Takibi)
        Schema::create('fleet_telemetries', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_plate');
            $table->string('vehicle_type'); // Kamyon, Kule Vinç, Ekskavatör
            $table->date('record_date');
            $table->decimal('operating_hours', 5, 2);
            $table->decimal('idle_hours', 5, 2);
            $table->decimal('fuel_consumed_liters', 8, 2);
            $table->string('operator_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. AiTimesheetAnalyzer (AI Destekli Hakediş/Puantaj Analizörü)
        Schema::create('ai_timesheet_analyzers', function (Blueprint $table) {
            $table->id();
            $table->string('subcontractor_name');
            $table->string('period'); // Örn: 2026-08
            $table->integer('claimed_mandays'); // Taşeronun beyan ettiği yevmiye
            $table->integer('actual_turnstile_mandays'); // Turnike/Kamera geçişi
            $table->decimal('discrepancy_percentage', 5, 2); // Tutarsızlık %
            $table->text('ai_risk_assessment'); // Yapay zeka yorumu
            $table->string('status')->default('inceleniyor'); // onaylandi, itiraz_var, inceleniyor
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. BudgetSimulator (Kur/Enflasyon Simülatörü)
        Schema::create('budget_simulators', function (Blueprint $table) {
            $table->id();
            $table->string('scenario_name');
            $table->decimal('usd_exchange_rate', 8, 4);
            $table->decimal('inflation_expectation', 5, 2); // % cinsinden
            $table->decimal('current_remaining_budget', 15, 2); // Mevcut kalan bütçe
            $table->decimal('simulated_projected_cost', 15, 2); // Simüle edilen yeni maliyet
            $table->decimal('variance_amount', 15, 2); // Sapma tutarı
            $table->string('risk_level'); // Dusuk, Orta, Yuksek
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. WasteManagement (Şantiye Atık/ÇED)
        Schema::create('waste_managements', function (Blueprint $table) {
            $table->id();
            $table->date('disposal_date');
            $table->string('waste_type'); // Hafriyat, Asbest, Tehlikeli Atık vs.
            $table->decimal('quantity_tons', 10, 2);
            $table->string('disposal_facility');
            $table->string('motat_receipt_no')->nullable(); // Bakanlık makbuz no
            $table->string('environmental_officer'); // Çevre görevlisi
            $table->string('status')->default('teslim_edildi');
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. EAgreement (Cari E-Mutabakat)
        Schema::create('e_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name');
            $table->string('partner_tax_no');
            $table->string('period_month');
            $table->decimal('our_balance', 15, 2); // Bizim bakiyemiz
            $table->decimal('partner_balance', 15, 2)->nullable(); // Karşı tarafın girdiği bakiye
            $table->string('mutabakat_type'); // BA/BS veya Cari Bakiye
            $table->string('status')->default('gonderildi'); // gonderildi, mutabik_kalindi, uyusmazlik
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_agreements');
        Schema::dropIfExists('waste_managements');
        Schema::dropIfExists('budget_simulators');
        Schema::dropIfExists('ai_timesheet_analyzers');
        Schema::dropIfExists('fleet_telemetries');
    }
};
