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
        if (Schema::hasTable('property_evaluations')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('property_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('evaluation_name'); // Değerlendirme adı
            $table->text('description')->nullable();
            
            // Değerlendirme Tarihi
            $table->date('evaluation_date');
            $table->foreignId('evaluated_by_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            // Arazi Özellikleri Değerlendirmesi
            $table->decimal('terrain_suitability', 5, 2)->nullable(); // Arazi uygunluğu (0-100)
            $table->decimal('accessibility_score', 5, 2)->nullable(); // Erişilebilirlik skoru
            $table->decimal('infrastructure_readiness', 5, 2)->nullable(); // Altyapı hazırlığı
            
            // Pazar Değerlendirmesi
            $table->decimal('market_value_per_sqm', 12, 2)->nullable(); // Pazar değeri (m² başına)
            $table->decimal('total_market_value', 15, 2)->nullable(); // Toplam pazar değeri
            $table->decimal('appreciation_potential_percent', 5, 2)->nullable(); // Değer artış potansiyeli (%)
            
            // Yatırım Analizi
            $table->decimal('roi_projection', 5, 2)->nullable(); // ROI projeksiyonu (%)
            $table->integer('payback_period_months')->nullable(); // Geri dönüş süresi (ay)
            $table->enum('investment_recommendation', ['highly_recommended', 'recommended', 'neutral', 'not_recommended'])->default('neutral');
            
            // Risk Analizi
            $table->enum('market_risk', ['low', 'medium', 'high'])->default('medium');
            $table->enum('legal_risk', ['low', 'medium', 'high'])->default('medium');
            $table->enum('development_risk', ['low', 'medium', 'high'])->default('medium');
            $table->text('risk_notes')->nullable();
            
            // Comparables (Benzer Satışlar)
            $table->integer('comparable_sales_count')->nullable();
            $table->decimal('comparable_average_price_per_sqm', 12, 2)->nullable();
            
            // Notlar & Tavsiyeler
            $table->text('strengths')->nullable(); // Güçlü yönler
            $table->text('weaknesses')->nullable(); // Zayıf yönler
            $table->text('opportunities')->nullable(); // Fırsatlar
            $table->text('threats')->nullable(); // Tehditler
            $table->text('recommendations')->nullable(); // Öneriler
            
            // Durum
            $table->boolean('is_final')->default(false);
            
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
        Schema::dropIfExists('property_evaluations');
    }
};
