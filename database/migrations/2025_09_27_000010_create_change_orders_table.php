<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('change_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->string('no')->nullable()->index();
            $table->string('subject');
            $table->text('reason')->nullable();
            $table->string('status')->default('İncelemede');
            $table->decimal('cost', 20, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_orders');
    }
};
