<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractor_service_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcontractor_user_id')->constrained()->cascadeOnDelete();
            $table->date('entry_date');
            $table->string('service_type');
            $table->string('work_description');
            $table->decimal('quantity', 12, 2)->nullable();
            $table->string('unit')->nullable();
            $table->decimal('worked_hours', 8, 2)->nullable();
            $table->unsignedInteger('crew_size')->nullable();
            $table->string('status')->default('draft');
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'entry_date'], 'svc_entries_project_date_idx');
            $table->index(['subcontractor_contract_id', 'status'], 'svc_entries_contract_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractor_service_entries');
    }
};
