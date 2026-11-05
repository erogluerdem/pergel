<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->string('pos')->nullable();
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('contract_qty', 18, 2)->default(0);
            $table->decimal('prev_qty', 18, 2)->default(0);
            $table->decimal('this_qty', 18, 2)->default(0);
            $table->decimal('total_qty', 18, 2)->default(0);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('this_amount', 18, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('payment_items');
    }
};
