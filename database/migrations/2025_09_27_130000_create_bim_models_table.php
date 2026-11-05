<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBimModelsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bim_models')) {
            Schema::create('bim_models', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id')->nullable();
                $table->string('name');
                $table->string('discipline')->nullable();
                $table->string('file_type')->nullable();
                $table->string('version')->nullable();
                $table->text('version_notes')->nullable();
                $table->enum('status',['processing','ready','error'])->default('processing');
                $table->string('file_path')->nullable();
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bim_clash_tests')) {
            Schema::create('bim_clash_tests', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->json('models_a')->nullable();
                $table->json('models_b')->nullable();
                $table->enum('status',['pending','running','done','failed'])->default('pending');
                $table->json('result')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bim_clash_tests')) Schema::dropIfExists('bim_clash_tests');
        if (Schema::hasTable('bim_models')) Schema::dropIfExists('bim_models');
    }
}
