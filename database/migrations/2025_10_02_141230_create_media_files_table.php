<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime_type');
            $table->string('extension');
            $table->bigInteger('size');
            $table->json('metadata')->nullable(); // dimensions, duration, etc.
            $table->string('folder_path')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedBigInteger('uploaded_by');
            $table->integer('downloads')->default(0);
            $table->timestamp('last_accessed')->nullable();
            $table->timestamps();
            
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['mime_type', 'is_public']);
            $table->index('folder_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
