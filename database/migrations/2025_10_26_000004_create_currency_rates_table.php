<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3); // TRY, USD, EUR, etc.
            $table->string('to_currency', 3);
            $table->decimal('rate', 15, 8);
            $table->date('effective_date');
            $table->enum('source', ['manual', 'api', 'bank'])->default('manual');
            $table->decimal('rate_bid', 15, 8)->nullable(); // Alış kuru
            $table->decimal('rate_ask', 15, 8)->nullable(); // Satış kuru
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['from_currency', 'to_currency', 'effective_date']);
            $table->index(['from_currency', 'to_currency', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
