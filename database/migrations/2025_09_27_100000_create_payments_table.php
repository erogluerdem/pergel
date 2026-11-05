<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('contractor')->nullable();
            $table->string('contract')->nullable();
            $table->date('date')->nullable();
            $table->decimal('requested', 18, 2)->default(0);
            $table->decimal('approved', 18, 2)->nullable();
            $table->string('status')->default('Taslak');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
