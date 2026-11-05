<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Proje ve daire ilişkileri
            if (!Schema::hasColumn('service_requests', 'project_id')) {
                $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('service_requests', 'unit_id')) {
                // Villa tablosu varsa foreign key koy, yoksa sadece integer tut
                if (Schema::hasTable('villas')) {
                    $table->foreignId('unit_id')->nullable()->constrained('villas')->cascadeOnDelete();
                } else {
                    $table->unsignedBigInteger('unit_id')->nullable();
                }
            }
            if (!Schema::hasColumn('service_requests', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete();
            }

            // SLA ve zaman takibi
            if (!Schema::hasColumn('service_requests', 'resolution_time')) {
                $table->integer('resolution_time')->nullable()->comment('Çözüm süresi (saniye)');
            }
            if (!Schema::hasColumn('service_requests', 'sla_target')) {
                $table->integer('sla_target')->nullable()->comment('SLA hedefi (saniye)');
            }
            if (!Schema::hasColumn('service_requests', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable();
            }

            // QR kod
            if (!Schema::hasColumn('service_requests', 'qr_token')) {
                $table->string('qr_token')->nullable()->unique();
            }

            // İletişim kaydı
            if (!Schema::hasColumn('service_requests', 'communication_log')) {
                $table->json('communication_log')->nullable()->comment('İletişim geçmişi');
            }

            // İndeksler
            $table->index('project_id');
            $table->index('unit_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Foreign key'leri sil ÖNCE
            try {
                $table->dropForeign(['project_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['unit_id']);
            } catch (\Exception $e) {}
            try {
                $table->dropForeign(['customer_id']);
            } catch (\Exception $e) {}
        });
        
        Schema::table('service_requests', function (Blueprint $table) {
            // Sütunları sil - her biri için try-catch
            try {
                $table->dropColumn('project_id');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('unit_id');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('customer_id');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('resolution_time');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('sla_target');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('resolved_at');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('qr_token');
            } catch (\Exception $e) {}
            try {
                $table->dropColumn('communication_log');
            } catch (\Exception $e) {}
        });
    }
};
