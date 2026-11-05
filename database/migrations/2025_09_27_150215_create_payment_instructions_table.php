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
        Schema::create('payment_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_type'); // 'progress_claim' or 'invoice'
            $table->unsignedBigInteger('reference_id');
            $table->string('payee'); // Alıcı firma
            $table->decimal('amount', 18, 2);
            $table->date('due_date');
            $table->string('project_name');
            $table->string('priority')->default('normal'); // normal, urgent
            $table->string('bank_account')->nullable(); // Ödeme yapılacak banka hesabı
            $table->date('payment_date')->nullable();
            $table->string('status')->default('pending'); // pending, processed, cancelled
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->string('receipt_path')->nullable(); // Dekont dosyası yolu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_instructions');
    }
};
