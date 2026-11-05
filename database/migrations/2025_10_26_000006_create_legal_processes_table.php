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
        if (Schema::hasTable('legal_processes')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('legal_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('process_name'); // İşlem adı (örn: Tapu Devri, Imar Onayı, vb)
            $table->text('description')->nullable();
            
            // İşlem Türü
            $table->enum('process_type', [
                'deed_transfer',      // Tapu devri
                'zoning_approval',    // İmar onayı
                'permit_acquisition', // İzin alımı
                'survey',             // Ölçüm
                'dispute_resolution', // Anlaşmazlık çözümü
                'other'               // Diğer
            ])->default('other');
            
            // Bilgiler
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Tahmini bitiş tarihi
            $table->date('actual_completion_date')->nullable(); // Fiili bitiş tarihi
            $table->integer('duration_days')->nullable(); // Gün cinsinden süre
            
            // Sorumlu & Takipçiler
            $table->foreignId('responsible_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('followed_by_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            // Ek Bilgiler
            $table->string('authority')->nullable(); // Sorumlu kurum (Tapu Müdürlüğü, Belediye, vb)
            $table->string('reference_number')->nullable(); // Referans/Dosya No
            $table->decimal('cost', 12, 2)->nullable(); // Tahmini maliyet
            $table->decimal('actual_cost', 12, 2)->nullable(); // Fiili maliyet
            
            // Durum
            $table->enum('status', ['not_started', 'in_progress', 'pending_approval', 'completed', 'delayed', 'blocked'])->default('not_started');
            $table->text('status_notes')->nullable();
            $table->date('last_status_update')->nullable();
            
            // Gecikme Takibi
            $table->boolean('is_delayed')->default(false);
            $table->integer('delay_days')->nullable();
            $table->text('delay_reason')->nullable();
            
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
        Schema::dropIfExists('legal_processes');
    }
};
