<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contract_templates')) {
            Schema::create('contract_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->longText('template_content');
                $table->enum('status', ['active', 'draft', 'archived'])->default('draft');
                $table->boolean('require_signature')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
                $table->foreignId('template_id')->nullable()->constrained('contract_templates')->onDelete('set null');
                $table->longText('contract_content');
                $table->enum('status', ['draft', 'sent', 'signed', 'executed', 'cancelled'])->default('draft');
                $table->string('pdf_path')->nullable();
                $table->timestamp('signed_at')->nullable();
                $table->string('signed_by')->nullable();
                $table->json('signature_data')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['invoice_id', 'status']);
            });
        }

        if (!Schema::hasTable('contract_signatures')) {
            Schema::create('contract_signatures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('email');
                $table->enum('status', ['pending', 'signed', 'declined'])->default('pending');
                $table->timestamp('signed_at')->nullable();
                $table->json('signature_data')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
                $table->unique(['contract_id', 'user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_signatures');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_templates');
    }
};

