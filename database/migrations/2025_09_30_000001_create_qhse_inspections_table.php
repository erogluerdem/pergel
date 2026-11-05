<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qhse_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('discipline')->nullable();
            $table->string('status')->default('planned');
            $table->json('items')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qhse_inspections');
    }
};
