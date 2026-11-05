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
        Schema::create('equipment_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->date('maintenance_date'); // Bakım tarihi
            $table->enum('type', ['Periyodik', 'Arıza', 'Acil']); // Bakım tipi
            $table->string('title'); // Başlık
            $table->text('description')->nullable(); // Açıklama
            $table->decimal('cost', 15, 2)->nullable(); // Maliyet
            $table->integer('working_hours_before')->nullable(); // Bakım öncesi çalışma saati
            $table->integer('working_hours_after')->nullable(); // Bakım sonrası çalışma saati
            $table->string('performed_by')->nullable(); // Bakımı yapan kişi/firma
            $table->text('parts_used')->nullable(); // Kullanılan parçalar (JSON veya text)
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->index(['equipment_id', 'maintenance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenances');
    }
};
