<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunchlistTable extends Migration
{
    public function up()
    {
        Schema::create('punchlist', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->json('evidence')->nullable();
            $table->string('responsible')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('status')->default('open');
            $table->unsignedBigInteger('inspection_item_id')->nullable();
            $table->timestamps();

            $table->foreign('inspection_item_id')->references('id')->on('inspection_items')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('punchlist');
    }
}
