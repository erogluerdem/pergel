<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cost_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('code')->nullable();
            $table->string('name');
            $table->decimal('original', 20, 2)->default(0);
            $table->decimal('changes', 20, 2)->default(0);
            $table->decimal('revised', 20, 2)->default(0);
            $table->decimal('committed', 20, 2)->default(0);
            $table->decimal('actual', 20, 2)->default(0);
            $table->decimal('ttm', 20, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cost_items');
    }
};
