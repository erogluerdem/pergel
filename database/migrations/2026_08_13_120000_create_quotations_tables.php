<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('requisition_id')->nullable();
                $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
                $table->string('quote_number')->unique();
                $table->date('quote_date');
                $table->date('valid_until')->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->string('currency')->default('TRY');
                $table->string('status')->default('pending'); // pending, approved, rejected, active
                $table->text('notes')->nullable();
                $table->string('file_path')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quotation_items')) {
            Schema::create('quotation_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
                $table->unsignedBigInteger('requisition_item_id')->nullable();
                $table->string('description');
                $table->decimal('quantity', 15, 2)->default(1);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('total_price', 15, 2)->default(0);
                $table->integer('delivery_days')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
