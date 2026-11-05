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
        Schema::create('progress_claim_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_claim_id')->constrained('progress_claims')->onDelete('cascade');
            $table->string('item_code');
            $table->string('description');
            $table->string('unit');
            $table->decimal('contract_quantity', 15, 2);
            $table->decimal('previous_quantity', 15, 2)->default(0);
            $table->decimal('current_quantity', 15, 2)->default(0);
            $table->decimal('cumulative_quantity', 15, 2)->default(0);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_claim_items');
    }
};
