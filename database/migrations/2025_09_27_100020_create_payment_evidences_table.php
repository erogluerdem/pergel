<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_item_id');
            $table->string('filename');
            $table->string('mime')->nullable();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('payment_evidences');
    }
};
