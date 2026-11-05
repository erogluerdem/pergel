<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Şantiye Kameraları
        Schema::create('site_cameras', function (Blueprint $table) {
            $table->id();
            $table->string('camera_name');
            $table->string('location');
            $table->string('stream_url');
            $table->string('status')->default('online');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Taşeron Puanlama Karnesi
        Schema::create('subcontractor_scores', function (Blueprint $table) {
            $table->id();
            $table->string('subcontractor_name');
            $table->integer('ai_reliability_score'); // 0-100
            $table->integer('safety_score'); // 0-100
            $table->integer('speed_score'); // 0-100
            $table->integer('total_score'); // 0-100
            $table->string('grade'); // A+, A, B, vb.
            $table->text('system_comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. İnsan Kaynakları & Özlük
        Schema::create('hr_personnels', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('position'); // Proje Müdürü, Şantiye Şefi
            $table->date('hire_date');
            $table->integer('remaining_leaves'); // Kalan izin günü
            $table->decimal('salary', 15, 2);
            $table->string('status')->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Dinamik Rapor Arşivi
        Schema::create('dynamic_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_title');
            $table->string('report_type'); // Hakediş, Finans, İSG vs.
            $table->string('generated_by');
            $table->string('file_format'); // PDF, EXCEL
            $table->string('file_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_reports');
        Schema::dropIfExists('hr_personnels');
        Schema::dropIfExists('subcontractor_scores');
        Schema::dropIfExists('site_cameras');
    }
};
