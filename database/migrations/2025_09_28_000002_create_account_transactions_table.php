<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->date('date');
            $table->string('description')->nullable();
            $table->decimal('debit',18,2)->default(0);
            $table->decimal('credit',18,2)->default(0);
            $table->decimal('balance',18,2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('account_transactions');
    }
};
