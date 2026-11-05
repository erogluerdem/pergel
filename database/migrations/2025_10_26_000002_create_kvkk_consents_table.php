<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kvkk_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('sms_code')->nullable(); // 6 haneli SMS kodu
            $table->timestamp('sms_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('consent_type', ['sms', 'email', 'both'])->default('sms');
            $table->enum('consent_status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->timestamp('consent_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->integer('verification_attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Hızlı sorgu için indeks
            $table->index(['customer_id', 'consent_status']);
            $table->index(['phone']);
            $table->index(['email']);
            $table->index(['expiry_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kvkk_consents');
    }
};
