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
        if (Schema::hasTable('properties')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_code')->unique(); // Arsa kodu (örn: ARS-2024-001)
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Arsa adı
            $table->text('description')->nullable();
            
            // Lokasyon Bilgileri
            $table->string('city')->nullable(); // Şehir
            $table->string('district')->nullable(); // İlçe
            $table->string('neighborhood')->nullable(); // Mahalle
            $table->string('address')->nullable(); // Tam adres
            $table->string('latitude')->nullable(); // GPS
            $table->string('longitude')->nullable(); // GPS
            
            // Arsa Özellikleri
            $table->decimal('area', 12, 2)->nullable(); // Alan (m²)
            $table->decimal('building_area', 12, 2)->nullable(); // İnşaat alanı
            $table->string('zoning_status')->nullable(); // İmar durumu (Ticari, Konut, Karma, vb)
            $table->decimal('floor_ratio', 5, 2)->nullable(); // TAKS
            $table->decimal('coverage_ratio', 5, 2)->nullable(); // KAKS
            
            // Arsa Değerlendirmesi
            $table->decimal('land_value', 15, 2)->nullable(); // Arsa değeri
            $table->decimal('development_potential_value', 15, 2)->nullable(); // Geliştirme potansiyel değeri
            $table->text('potential_notes')->nullable(); // Potansiyel notları
            $table->enum('investment_risk_level', ['low', 'medium', 'high'])->default('medium');
            
            // Tapu Bilgileri
            $table->string('deed_number')->nullable(); // Tapu no
            $table->string('block_number')->nullable(); // Mahalle-Blok no
            $table->string('parcel_number')->nullable(); // Parsel no
            $table->date('deed_date')->nullable(); // Tapu tarihi
            $table->string('owner_name')->nullable(); // Mülk sahibi adı
            $table->text('deed_description')->nullable(); // Tapu açıklaması
            
            // Yasal Durum
            $table->enum('legal_status', ['clear', 'disputed', 'under_process', 'restricted'])->default('clear');
            $table->text('legal_notes')->nullable(); // Yasal notlar
            $table->date('legal_status_check_date')->nullable();
            
            // Belgeler & Arşiv
            $table->integer('document_count')->default(0);
            $table->date('last_document_upload')->nullable();
            
            // İş Akışı & Durum
            $table->enum('status', ['vacant', 'acquired', 'in_development', 'developed', 'inactive'])->default('vacant');
            $table->enum('workflow_status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            
            // Sorumlu Kullanıcı
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            // Tarihler
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
