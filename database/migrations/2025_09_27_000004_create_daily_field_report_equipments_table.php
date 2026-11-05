<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('daily_field_report_equipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id')->index();
            $table->string('equipment_type')->nullable();
            $table->string('identifier')->nullable();
            $table->decimal('working_hours',6,2)->default(0);
            $table->decimal('idle_hours',6,2)->default(0);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('daily_field_report_equipments');
    }
};
