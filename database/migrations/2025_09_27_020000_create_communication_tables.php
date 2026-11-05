<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfis', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('rfi_no')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('open');
            $table->text('response')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::create('submittals', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('submittal_no')->nullable();
            $table->text('description')->nullable();
            $table->string('submitted_by')->nullable();
            $table->json('reviewers')->nullable();
            $table->string('status')->default('draft');
            $table->date('due_date')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::create('meeting_minutes', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('title')->nullable();
            $table->date('meeting_date')->nullable();
            $table->json('participants')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::create('action_items', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('meeting_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('title')->nullable();
            $table->string('assigned_to')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('meeting_id')->references('id')->on('meeting_minutes')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_items');
        Schema::dropIfExists('meeting_minutes');
        Schema::dropIfExists('submittals');
        Schema::dropIfExists('rfis');
    }
};
