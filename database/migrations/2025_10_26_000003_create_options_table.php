<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('opportunity_id')->nullable()->constrained()->onDelete('set null');
            $table->date('option_date'); // Opsiyon koyma tarihi
            $table->date('expiry_date'); // Opsiyon bitme tarihi
            $table->integer('duration_days')->default(3); // Varsayılan 3 gün
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'converted_to_project', 'expired', 'cancelled'])->default('active');
            $table->timestamps();
            
            // Hızlı sorgu için indeks
            $table->index(['asset_id', 'customer_id']);
            $table->index(['status']);
            $table->index(['expiry_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
};
