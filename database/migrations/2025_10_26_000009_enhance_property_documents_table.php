<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns to property_documents for operasyon module features
        Schema::table('property_documents', function (Blueprint $table) {
            // Add property_id relationship if not already present
            if (!Schema::hasColumn('property_documents', 'property_id')) {
                $table->foreignId('property_id')->nullable()->after('asset_id')->constrained('properties')->cascadeOnDelete();
            }
            
            // Add renewal tracking
            if (!Schema::hasColumn('property_documents', 'renewal_date')) {
                $table->date('renewal_date')->nullable()->after('expiry_date')->comment('Yenileme tarihi');
            }
            
            // Add critical flag
            if (!Schema::hasColumn('property_documents', 'is_critical')) {
                $table->boolean('is_critical')->default(false)->after('renewal_date')->comment('Kritik belge mi?');
            }
            
            // Add renewal requirement flag
            if (!Schema::hasColumn('property_documents', 'requires_renewal')) {
                $table->boolean('requires_renewal')->default(false)->after('is_critical')->comment('Yenileme gerekli mi?');
            }
            
            // Add indexing info
            if (!Schema::hasColumn('property_documents', 'indexed_by')) {
                $table->string('indexed_by')->nullable()->after('requires_renewal')->comment('İndexleyen kişi');
            }
            
            if (!Schema::hasColumn('property_documents', 'indexed_date')) {
                $table->date('indexed_date')->nullable()->after('indexed_by')->comment('İndexleme tarihi');
            }
            
            // Add uploaded by relationship
            if (!Schema::hasColumn('property_documents', 'uploaded_by_id')) {
                $table->foreignId('uploaded_by_id')->nullable()->after('indexed_date')->constrained('users')->cascadeOnDelete();
            }
            
            // Update document_type to document_category if needed
            // Note: This requires careful handling in production
        });
    }

    public function down(): void
    {
        Schema::table('property_documents', function (Blueprint $table) {
            // Remove foreign keys first
            if (Schema::hasColumn('property_documents', 'property_id')) {
                $table->dropForeign(['property_id']);
            }
            if (Schema::hasColumn('property_documents', 'uploaded_by_id')) {
                $table->dropForeign(['uploaded_by_id']);
            }
            
            // Drop columns
            $table->dropColumnIfExists([
                'property_id',
                'renewal_date',
                'is_critical',
                'requires_renewal',
                'indexed_by',
                'indexed_date',
                'uploaded_by_id'
            ]);
        });
    }
};
