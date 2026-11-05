<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('document_type')->comment('Belge tipi: tapu, mesken belgesi, imar durum, vs');
            $table->string('title')->comment('Belge başlığı');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable()->comment('Dosya yolu');
            $table->string('file_name')->nullable();
            $table->string('document_number')->nullable()->comment('Belge numarası (Tapu no, vs)');
            $table->date('issue_date')->nullable()->comment('Belge düzenlenme tarihi');
            $table->date('expiry_date')->nullable()->comment('Belge son geçerlilik tarihi');
            $table->enum('status', ['pending', 'received', 'processed', 'expired'])->default('pending')->comment('Durum');
            $table->string('issued_by')->nullable()->comment('Belgeyi düzenleyen kuruluş');
            $table->decimal('document_cost', 15, 2)->nullable()->comment('Belge maliyeti');
            $table->timestamps();
            $table->softDeletes();

            $table->index('asset_id');
            $table->index('project_id');
            $table->index('document_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_documents');
    }
};
