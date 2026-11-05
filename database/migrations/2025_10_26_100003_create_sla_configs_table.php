<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sla_configs')) {
            Schema::create('sla_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('issue_category')->comment('Sorun kategorisi (ör: elektrik, tesisat, asansör)');
            $table->integer('response_time')->comment('Yanıt süresi (saniye)');
            $table->integer('resolution_time')->comment('Çözüm süresi (saniye)');
            $table->integer('priority_weight')->default(100)->comment('Öncelik ağırlığı');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['project_id', 'issue_category']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_configs');
    }
};
