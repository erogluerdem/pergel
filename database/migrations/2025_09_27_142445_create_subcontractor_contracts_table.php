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
        Schema::create('subcontractor_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique(); // Sözleşme numarası
            $table->string('contract_name'); // Sözleşme adı
            $table->foreignId('subcontractor_id')->constrained('subcontractors')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->enum('contract_type', ['unit_price', 'lump_sum']); // Sözleşme tipi
            $table->decimal('total_amount', 15, 2); // Toplam sözleşme bedeli
            $table->decimal('paid_amount', 15, 2)->default(0); // Ödenen tutar
            $table->enum('status', ['draft', 'active', 'completed', 'terminated'])->default('draft');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->json('documents')->nullable(); // Sözleşme dosyaları
            $table->json('specifications')->nullable(); // Keşif özeti, pozlar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcontractor_contracts');
    }
};
