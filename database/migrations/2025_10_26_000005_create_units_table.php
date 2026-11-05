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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('unit_no')->unique();
            $table->string('block')->nullable();
            $table->string('floor')->nullable();
            $table->string('unit_type');
            $table->decimal('area', 10, 2);
            $table->integer('room_count')->nullable();
            $table->integer('bathroom_count')->nullable();
            $table->decimal('list_price', 15, 2);
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('max_discount_rate', 5, 2)->default(10);
            $table->string('currency')->default('TRY');
            $table->string('status')->default('available');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
