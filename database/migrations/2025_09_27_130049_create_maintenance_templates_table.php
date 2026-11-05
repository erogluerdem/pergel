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
        Schema::create('maintenance_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // HVAC, Elektrik, Asansör, vb.
            $table->text('description');
            $table->enum('frequency_unit', ['days', 'weeks', 'months', 'years']);
            $table->integer('frequency_value'); // Kaç gün/hafta/ay/yıl
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->text('checklist')->nullable(); // JSON formatında kontrol listesi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_templates');
    }
};
