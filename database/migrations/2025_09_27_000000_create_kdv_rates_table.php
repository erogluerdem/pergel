<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kdv_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('rate')->default(18)->comment('KDV yüzdesi, örn 18');
            $table->string('label')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kdv_rates');
    }
};
