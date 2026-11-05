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
        if (!Schema::hasTable('risks')) {
            Schema::create('risks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('program_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('title');
                $table->enum('impact', ['low', 'medium', 'high'])->default('medium');
                $table->enum('likelihood', ['low', 'medium', 'high'])->default('medium');
                $table->enum('status', ['open', 'mitigated', 'closed'])->default('open');
                $table->text('mitigation')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
