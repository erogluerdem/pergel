<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->string('number')->nullable()->index();
            $table->string('title');
            $table->string('claimant')->nullable();
            $table->string('type')->nullable();
            $table->decimal('amount_requested', 15, 2)->nullable();
            $table->integer('days_requested')->nullable();
            $table->string('status')->default('İncelemede');
            $table->unsignedBigInteger('responsible_user_id')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}
