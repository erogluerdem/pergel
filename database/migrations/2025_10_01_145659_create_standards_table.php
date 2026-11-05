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
        Schema::create('standards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['quality', 'safety', 'environmental', 'technical', 'process', 'compliance', 'security', 'performance'])->default('technical');
            $table->enum('status', ['active', 'draft', 'inactive', 'expired', 'pending'])->default('draft');
            $table->string('version')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('category', ['iso', 'ts', 'en', 'astm', 'din', 'bs', 'ansi', 'iec', 'ieee', 'company', 'project', 'regulatory', 'other'])->default('other');
            $table->tinyInteger('priority')->default(3)->comment('1=Kritik, 2=Yüksek, 3=Orta, 4=Düşük, 5=Çok Düşük');
            $table->enum('compliance_level', ['mandatory', 'recommended', 'optional', 'reference'])->default('recommended');
            $table->string('document_url')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'category']);
            $table->index(['type', 'priority']);
            $table->index('effective_date');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standards');
    }
};
