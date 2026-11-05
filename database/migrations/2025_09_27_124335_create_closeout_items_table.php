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
        if (!Schema::hasTable('closeout_items')) {
            Schema::create('closeout_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('closeout_id')->constrained()->onDelete('cascade');
                $table->string('category');
                $table->string('item');
                $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
                $table->json('documents')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closeout_items');
    }
};
