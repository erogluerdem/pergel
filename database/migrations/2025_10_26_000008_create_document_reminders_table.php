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
        if (Schema::hasTable('document_reminders')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('document_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_document_id')->constrained()->cascadeOnDelete();
            $table->string('reminder_title');
            $table->text('description')->nullable();
            
            // Hatırlatma Tarihleri
            $table->date('renewal_due_date'); // Yenileme yapılması gereken tarih
            $table->enum('reminder_frequency', [
                'one_time',      // Tek seferlik
                'weekly',        // Haftalık
                'monthly',       // Aylık
                'quarterly',     // Üç aylık
                'yearly'         // Yıllık
            ])->default('one_time');
            
            // Bildirim Detayları
            $table->enum('notification_status', ['pending', 'sent', 'cancelled'])->default('pending');
            $table->date('last_notification_sent')->nullable();
            $table->date('next_notification_date')->nullable();
            
            // Alıcılar
            $table->json('notification_recipients')->nullable(); // JSON: user_ids
            $table->string('notification_channels')->nullable(); // email, sms, in_app
            
            // İlişkili Belge Yenileme
            $table->string('renewal_document_type')->nullable(); // Yenilenmesi gereken belge türü
            $table->decimal('estimated_renewal_cost', 12, 2)->nullable(); // Tahmini yenileme maliyeti
            
            // Durum
            $table->boolean('is_active')->default(true);
            $table->boolean('renewal_completed')->default(false);
            $table->date('renewal_completed_date')->nullable();
            
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
        Schema::dropIfExists('document_reminders');
    }
};
