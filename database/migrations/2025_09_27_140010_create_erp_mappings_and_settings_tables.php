<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('erp_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('platform_code');
            $table->string('erp_account');
            $table->timestamps();
        });

        Schema::create('erp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('erp_mappings');
        Schema::dropIfExists('erp_settings');
    }
};
