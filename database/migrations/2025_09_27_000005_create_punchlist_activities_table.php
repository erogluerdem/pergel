<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunchlistActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('punchlist_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('punch_id');
            $table->string('actor')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('punch_id')->references('id')->on('punchlist')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('punchlist_activities');
    }
}
