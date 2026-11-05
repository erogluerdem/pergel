<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qhse_punchlist', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('responsible')->nullable();
            $table->string('status')->default('open');
            $table->json('evidence')->nullable();
            $table->foreignId('inspection_id')->nullable()->constrained('qhse_inspections')->nullOnDelete();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qhse_punchlist');
    }
};
