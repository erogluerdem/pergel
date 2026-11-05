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
        if (!Schema::hasTable('progress_payment_details')) {
            Schema::create('progress_payment_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('progress_payment_id')->constrained('progress_payments')->onDelete('cascade');
                $table->text('description');
                $table->decimal('quantity', 12, 2)->default(1);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('amount', 15, 2)->default(0);
                $table->decimal('percentage', 5, 2)->nullable();
                $table->string('contract_term_reference')->nullable();
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
        Schema::dropIfExists('progress_payment_details');
    }
};
