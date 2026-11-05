<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulationsTable extends Migration
{
    public function up()
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 4D or 5D
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('status')->default('pending');
            $table->json('config')->nullable();
            $table->json('result')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('project_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('simulations');
    }
}
