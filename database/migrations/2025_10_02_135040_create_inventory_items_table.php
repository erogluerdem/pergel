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
        if (!Schema::hasTable('inventory_items')) {
            Schema::create('inventory_items', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('title')->nullable();
                $table->string('sku')->nullable();
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->decimal('unit_cost', 15, 2)->default(0);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->integer('quantity')->default(0);
                $table->integer('stock')->default(0);
                $table->integer('min_quantity')->default(0);
                $table->string('unit')->nullable();
                $table->string('supplier')->nullable();
                $table->string('location')->nullable();
                $table->string('status')->default('active');
                $table->json('images')->nullable();
                $table->timestamp('last_restocked')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
