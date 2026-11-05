<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Villas tablosu yoksa oluştur
        if (!Schema::hasTable('villas')) {
            Schema::create('villas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id')->nullable();
                $table->string('name');
                $table->decimal('target_cost', 15, 2)->default(0);
                $table->decimal('actual_cost', 15, 2)->default(0);
                $table->enum('status', ['planned', 'in_progress', 'completed'])->default('planned');
                $table->timestamps();
                $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('villas');
    }
};
