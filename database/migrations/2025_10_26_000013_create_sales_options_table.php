<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_options')) {
            return;
        }

        Schema::create('sales_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->date('option_date');
            $table->date('expiry_date');
            $table->decimal('option_price', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->enum('status', ['active', 'converted', 'expired', 'cancelled'])->default('active');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->decimal('conversion_probability', 5, 2)->default(50); // Percentage
            $table->text('notes')->nullable();
            $table->string('contract_reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index(['unit_id', 'status']);
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_options');
    }
};
