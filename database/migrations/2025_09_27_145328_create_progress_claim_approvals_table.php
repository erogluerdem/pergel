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
        Schema::create('progress_claim_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_claim_id')->constrained('progress_claims')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['approved', 'rejected', 'revision_requested']);
            $table->text('comments')->nullable();
            $table->json('approved_quantities')->nullable(); // item_id => approved_quantity
            $table->timestamp('approved_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_claim_approvals');
    }
};
