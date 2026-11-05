<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. SGK Asgari İşçilik Hesaplamaları
        Schema::create('sgk_labor_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->decimal('building_area_sqm', 12, 2);
            $table->string('structure_class')->default('III-B'); // örn: III. Sınıf B Grubu
            $table->decimal('unit_cost_per_sqm', 12, 2);
            $table->decimal('mandatory_labor_rate', 5, 2)->default(6.75); // %6.75
            $table->decimal('required_labor_amount', 14, 2);
            $table->decimal('reported_labor_amount', 14, 2)->default(0);
            $table->decimal('remaining_labor_amount', 14, 2)->default(0);
            $table->enum('risk_status', ['low', 'medium', 'high'])->default('medium');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Kat Karşılığı Sözleşmeleri
        Schema::create('land_owner_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('land_owner_name');
            $table->string('land_owner_tc_vkn')->nullable();
            $table->decimal('land_share_percentage', 5, 2)->default(50.00);
            $table->decimal('contractor_share_percentage', 5, 2)->default(50.00);
            $table->text('deed_transfer_terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Ünite Paylaşım Matrisi
        Schema::create('unit_share_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_owner_contract_id')->constrained('land_owner_contracts')->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->enum('owner_type', ['land_owner', 'contractor', 'shared'])->default('contractor');
            $table->enum('stage_requirement', ['substructure', 'superstructure', 'finishing', 'occupancy_permit'])->default('occupancy_permit');
            $table->boolean('is_deed_transferred')->default(false);
            $table->date('deed_transfer_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. EBİS Beton Kırım Kayıtları
        Schema::create('concrete_test_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('pouring_date');
            $table->string('concrete_class')->default('C30/37');
            $table->string('location_element'); // örn: 3. Kat Tabliye / Kolon
            $table->decimal('quantity_m3', 10, 2);
            $table->string('waybill_number')->nullable();
            $table->string('building_inspection_company')->nullable();
            $table->string('ebis_chip_numbers')->nullable();
            $table->decimal('day_7_strength_mpa', 8, 2)->nullable();
            $table->decimal('day_7_target_percent', 5, 2)->nullable();
            $table->decimal('day_28_strength_mpa', 8, 2)->nullable();
            $table->decimal('day_28_target_percent', 5, 2)->nullable();
            $table->enum('status', ['passed', 'warning', 'failed'])->default('passed');
            $table->string('lab_report_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. ÇŞB Poz Kütüphanesi
        Schema::create('csb_poz_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('poz_number')->index();
            $table->string('title');
            $table->string('unit'); // m2, m3, ton vb.
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->integer('year')->default(2026);
            $table->string('work_group')->nullable(); // İnşaat, Elektrik, Tesisat
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Senetler
        Schema::create('promissory_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->string('note_number');
            $table->decimal('amount', 14, 2);
            $table->date('due_date');
            $table->string('debtor_name');
            $table->string('debtor_tc_vkn')->nullable();
            $table->string('guarantor_name')->nullable();
            $table->enum('status', ['portfolio', 'collected', 'endorsed', 'protested', 'legal_action'])->default('portfolio');
            $table->string('endorsed_to_supplier')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Takas Varlıkları
        Schema::create('trade_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->enum('asset_type', ['vehicle', 'land', 'apartment', 'other'])->default('vehicle');
            $table->string('title');
            $table->decimal('valuation_amount', 14, 2);
            $table->decimal('agreed_amount', 14, 2);
            $table->enum('status', ['in_inventory', 'sold', 'transferred'])->default('in_inventory');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Taşeron Personeli ve İSG
        Schema::create('subcontractor_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_id')->constrained('subcontractors')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('tc_no')->nullable();
            $table->string('job_title')->nullable();
            $table->enum('sgk_status', ['active', 'pending', 'missing'])->default('active');
            $table->enum('entry_permit_status', ['approved', 'blocked'])->default('approved');
            $table->string('blocked_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Taşeron Personel Evrakları
        Schema::create('subcontractor_worker_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('subcontractor_workers')->onDelete('cascade');
            $table->enum('document_type', ['sgk_entry', 'isg_certificate', 'health_report_ek2', 'work_at_height', 'vocational_cert', 'other'])->default('isg_certificate');
            $table->string('file_path')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_valid')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractor_worker_documents');
        Schema::dropIfExists('subcontractor_workers');
        Schema::dropIfExists('trade_assets');
        Schema::dropIfExists('promissory_notes');
        Schema::dropIfExists('csb_poz_libraries');
        Schema::dropIfExists('concrete_test_records');
        Schema::dropIfExists('unit_share_matrices');
        Schema::dropIfExists('land_owner_contracts');
        Schema::dropIfExists('sgk_labor_calculations');
    }
};
