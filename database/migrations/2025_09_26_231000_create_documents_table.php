<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('folder_id')->nullable()->index();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->string('version')->default('V1');
            $table->string('status')->default('draft');
            $table->string('discipline')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index();
            $table->timestamp('uploaded_at')->nullable();
            $table->unsignedBigInteger('replaced_document_id')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
