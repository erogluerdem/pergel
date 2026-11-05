<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('erp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('direction')->nullable();
            $table->integer('data_count')->default(0);
            $table->string('status')->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('erp_logs');
    }
};
