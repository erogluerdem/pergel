<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('stock_card_id')->nullable()->constrained('stock_cards')->nullOnDelete();
            $table->string('description');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('quantity', 14, 2)->default(1);
            $table->string('unit')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(18.00);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
