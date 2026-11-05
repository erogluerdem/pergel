<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // ramak kala, hafif, ağır, guvensiz durum
            $table->text('description')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->string('status')->default('open'); // open, closed, investigation
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incident_reports');
    }
};
