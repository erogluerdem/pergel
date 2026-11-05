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
        Schema::create('site_diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('entry_date');
            $table->string('weather_condition')->default('Güneşli');
            $table->string('temperature')->nullable();
            $table->integer('worker_count')->default(0);
            $table->text('work_done_notes');
            $table->text('equipment_notes')->nullable();
            $table->text('safety_notes')->nullable();
            $table->json('photo_urls')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_diaries');
    }
};
