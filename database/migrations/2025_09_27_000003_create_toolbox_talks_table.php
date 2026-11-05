<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('toolbox_talks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('topic')->nullable();
            $table->unsignedBigInteger('presenter_id')->nullable();
            $table->json('participants')->nullable(); // array of user ids or names
            $table->string('attendance_photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('toolbox_talks');
    }
};
