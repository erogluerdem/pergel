<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('subcategory')->nullable()->after('category');
            $table->date('purchase_date')->nullable()->after('installation_date');
            $table->decimal('purchase_price', 15, 2)->nullable()->after('purchase_date');
            $table->decimal('current_value', 15, 2)->nullable()->after('purchase_price');
            $table->decimal('depreciation_rate', 5, 2)->nullable()->after('current_value');
            $table->string('depreciation_method')->nullable()->after('depreciation_rate');
            $table->integer('useful_life_years')->nullable()->after('depreciation_method');
            $table->integer('useful_life_months')->nullable()->after('useful_life_years');
            $table->decimal('salvage_value', 15, 2)->nullable()->after('useful_life_months');
            $table->string('department')->nullable()->after('location');
            $table->unsignedBigInteger('assigned_to')->nullable()->after('department');
            $table->string('condition')->nullable()->after('status');
            $table->date('warranty_start_date')->nullable()->after('condition');
            $table->date('warranty_end_date')->nullable()->after('warranty_start_date');
            $table->string('maintenance_schedule')->nullable()->after('next_maintenance_date');
            $table->string('supplier_name')->nullable()->after('maintenance_schedule');
            $table->string('supplier_contact')->nullable()->after('supplier_name');
            $table->string('invoice_number')->nullable()->after('supplier_contact');
            $table->string('insurance_policy')->nullable()->after('invoice_number');
            $table->decimal('insurance_value', 15, 2)->nullable()->after('insurance_policy');
            $table->json('tags')->nullable()->after('insurance_value');
            $table->json('custom_fields')->nullable()->after('tags');
            $table->string('image_path')->nullable()->after('custom_fields');
            $table->string('qr_code')->nullable()->after('image_path');
            $table->string('barcode')->nullable()->after('qr_code');
            $table->unsignedBigInteger('created_by')->nullable()->after('barcode');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->softDeletes()->after('updated_at');

            // Foreign keys
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['status', 'category']);
            $table->index(['department', 'status']);
            $table->index(['next_maintenance_date']);
            $table->index(['warranty_end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropColumn([
                'subcategory',
                'purchase_date',
                'purchase_price',
                'current_value',
                'depreciation_rate',
                'depreciation_method',
                'useful_life_years',
                'useful_life_months',
                'salvage_value',
                'department',
                'assigned_to',
                'condition',
                'warranty_start_date',
                'warranty_end_date',
                'maintenance_schedule',
                'supplier_name',
                'supplier_contact',
                'invoice_number',
                'insurance_policy',
                'insurance_value',
                'tags',
                'custom_fields',
                'image_path',
                'qr_code',
                'barcode',
                'created_by',
                'updated_by',
                'deleted_at'
            ]);
        });
    }
};
