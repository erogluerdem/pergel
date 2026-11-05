<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_id')->constrained('installments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['email', 'sms'])->default('email');
            $table->integer('days_before')->default(3); // 3 gün öncesi
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();

            $table->unique(['installment_id', 'type', 'days_before']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
    }
};
