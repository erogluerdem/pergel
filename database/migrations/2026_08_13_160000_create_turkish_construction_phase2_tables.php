<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. E-Fatura / E-İrsaliye
        Schema::create('e_waybills', function (Blueprint $table) {
            $table->id();
            $table->string('waybill_number')->unique();
            $table->date('issue_date');
            $table->string('supplier_name');
            $table->string('supplier_vkn_tckn');
            $table->string('material_description');
            $table->decimal('quantity', 15, 2);
            $table->string('unit');
            $table->string('status')->default('bekliyor'); // bekliyor, onaylandi, reddedildi, faturaya_donustu
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. YDK (Yapı Denetim) Takibi
        Schema::create('building_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('ydk_company_name');
            $table->string('document_type'); // Seviye Tespit Tutanağı, YDK Hakedişi, Ruhsat Harcı
            $table->decimal('progress_percentage', 5, 2)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('beklemede'); // beklemede, odendi, onaylandi
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Taşeron Yasal Kesintiler
        Schema::create('subcontractor_deductions', function (Blueprint $table) {
            $table->id();
            $table->string('subcontractor_name');
            $table->string('hakedis_period');
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('teminat_kesintisi_rate', 5, 2)->default(5.00);
            $table->decimal('teminat_amount', 15, 2);
            $table->decimal('damga_vergisi_rate', 8, 5)->default(0.00948);
            $table->decimal('damga_vergisi_amount', 15, 2);
            $table->decimal('stopaj_rate', 5, 2)->default(0.00);
            $table->decimal('stopaj_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Fiyat Farkı (Yİ-ÜFE)
        Schema::create('price_difference_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('contract_name');
            $table->string('base_index_month'); // Temel Endeks Ayı (Pn)
            $table->decimal('base_index_value', 10, 2);
            $table->string('current_index_month'); // Güncel Endeks Ayı (Po)
            $table->decimal('current_index_value', 10, 2);
            $table->decimal('hakedis_amount', 15, 2); // Kapsamdaki Tutar
            $table->decimal('price_difference_amount', 15, 2); // Hesaplanan Fiyat Farkı (F)
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Yasal Şantiye Günlük Defteri
        Schema::create('site_daily_logs', function (Blueprint $table) {
            $table->id();
            $table->date('log_date');
            $table->string('weather_condition'); // Açık, Yağmurlu, Karlı vs.
            $table->integer('temperature');
            $table->integer('total_workers'); // Kendi personeli
            $table->integer('subcontractor_workers'); // Taşeron mevcudu
            $table->text('manufacturing_details'); // O gün yapılan imalatlar
            $table->string('site_manager_name');
            $table->boolean('is_signed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. KDV İade ve Teşvik
        Schema::create('vat_returns', function (Blueprint $table) {
            $table->id();
            $table->string('period'); // Örn: 2026-08
            $table->string('project_type'); // Kentsel Dönüşüm, 150m2 altı konut vs.
            $table->decimal('total_kdv1_sales', 15, 2)->default(0);
            $table->decimal('total_kdv10_sales', 15, 2)->default(0);
            $table->decimal('total_kdv20_purchases', 15, 2)->default(0);
            $table->decimal('calculated_return_amount', 15, 2); // Yüklenilen - Hesaplanan
            $table->string('ymm_report_status')->default('hazirlaniyor'); // hazirlaniyor, sunuldu, onaylandi
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. SPK Gayrimenkul Değerleme
        Schema::create('real_estate_valuations', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name'); // Daire, Arsa, Dükkan
            $table->string('valuation_company');
            $table->string('spk_license_number')->nullable();
            $table->date('report_date');
            $table->decimal('previous_value', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2);
            $table->string('report_file_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('real_estate_valuations');
        Schema::dropIfExists('vat_returns');
        Schema::dropIfExists('site_daily_logs');
        Schema::dropIfExists('price_difference_calculations');
        Schema::dropIfExists('subcontractor_deductions');
        Schema::dropIfExists('building_inspections');
        Schema::dropIfExists('e_waybills');
    }
};
