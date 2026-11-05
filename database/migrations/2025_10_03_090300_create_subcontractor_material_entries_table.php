<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractor_material_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontractor_contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcontractor_user_id')->constrained()->cascadeOnDelete();
            $table->date('delivery_date');
            $table->string('material_name');
            $table->string('specification')->nullable();
            $table->decimal('quantity', 12, 2);
            $table->string('unit')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->string('status')->default('draft');
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'delivery_date'], 'mat_entries_project_date_idx');
            $table->index(['subcontractor_contract_id', 'status'], 'mat_entries_contract_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractor_material_entries');
    }
};
