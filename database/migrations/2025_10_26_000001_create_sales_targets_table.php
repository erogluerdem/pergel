<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('target_type', ['revenue', 'unit'])->default('revenue'); // 'revenue' = Ciro, 'unit' = Satış Adedi
            $table->decimal('target_amount', 20, 2); // Hedef değer
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'completed', 'abandoned'])->default('active');
            $table->timestamps();
            
            // Hızlı sorgu için indeks
            $table->index(['user_id', 'period_start', 'period_end']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_targets');
    }
};
