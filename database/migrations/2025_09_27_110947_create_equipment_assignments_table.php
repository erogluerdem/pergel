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
        Schema::create('equipment_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('project_id');
            $table->date('start_date'); // Başlangıç tarihi
            $table->date('end_date')->nullable(); // Bitiş tarihi (null ise hala devam ediyor)
            $table->string('operator')->nullable(); // Operatör
            $table->text('purpose')->nullable(); // Amaç/kullanım
            $table->integer('estimated_hours')->nullable(); // Tahmini çalışma saati
            $table->integer('actual_hours')->nullable(); // Gerçekleşen çalışma saati
            $table->text('notes')->nullable(); // Notlar
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->index(['equipment_id', 'start_date']);
            $table->index(['project_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_assignments');
    }
};
