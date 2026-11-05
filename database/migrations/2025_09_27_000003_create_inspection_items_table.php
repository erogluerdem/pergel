<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->string('text');
            $table->string('result')->nullable();
            $table->text('note')->nullable();
            $table->string('assignee')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->json('evidence')->nullable();
            $table->timestamps();

            $table->foreign('inspection_id')->references('id')->on('inspections')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspection_items');
    }
}
