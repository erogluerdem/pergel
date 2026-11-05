<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('transfer'); // transfer | eft
            $table->string('from_account');
            $table->string('to_account')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('bank')->nullable();
            $table->decimal('amount', 20, 2)->default(0);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending | completed
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
