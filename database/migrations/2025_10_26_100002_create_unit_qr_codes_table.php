<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_qr_codes', function (Blueprint $table) {
            $table->id();
            if (Schema::hasTable('villas')) {
                $table->foreignId('unit_id')->constrained('villas')->cascadeOnDelete();
            } else {
                $table->unsignedBigInteger('unit_id');
            }
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('qr_token')->unique()->index();
            $table->string('qr_code_path');
            $table->integer('scans_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->string('qr_label')->nullable()->comment('QR kod etiketi (örn: "Daire 101 - Hızlı Destek")');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('project_id');
            $table->index('unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_qr_codes');
    }
};
