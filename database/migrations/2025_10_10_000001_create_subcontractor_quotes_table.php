<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('subcontractor_quotes')) {
            Schema::create('subcontractor_quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcontractor_id');
            $table->string('requester_name')->nullable();
            $table->string('requester_email')->nullable();
            $table->string('requester_phone')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();

            $table->foreign('subcontractor_id')->references('id')->on('subcontractors')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('subcontractor_quotes');
    }
};
