<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('daily_field_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->date('report_date');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->text('notes')->nullable();
            $table->json('weather')->nullable();
            $table->string('status')->default('draft'); // draft, submitted
            $table->timestamps();
            $table->unique(['project_id','report_date']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('daily_field_reports');
    }
};
