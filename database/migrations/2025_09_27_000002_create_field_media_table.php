<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('field_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->unsignedBigInteger('report_id')->nullable()->index();
            $table->unsignedBigInteger('uploader_id')->nullable()->index();
            $table->string('original_name')->nullable();
            $table->string('path');
            $table->string('mime')->nullable();
            $table->bigInteger('size')->nullable();
            $table->double('gps_lat', 15, 8)->nullable();
            $table->double('gps_lon', 15, 8)->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('field_media');
    }
};
