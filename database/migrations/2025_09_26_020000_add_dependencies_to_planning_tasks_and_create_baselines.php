<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('planning_tasks')) {
            if (!Schema::hasColumn('planning_tasks', 'dependencies')) {
                Schema::table('planning_tasks', function (Blueprint $table) {
                    $table->json('dependencies')->nullable()->after('notes');
                });
            }
        }

        if (!Schema::hasTable('planning_baselines')) {
            Schema::create('planning_baselines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id')->nullable();
                $table->string('name')->nullable();
                $table->text('notes')->nullable();
                $table->json('snapshot')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('planning_tasks') && Schema::hasColumn('planning_tasks', 'dependencies')) {
            Schema::table('planning_tasks', function (Blueprint $table) {
                $table->dropColumn('dependencies');
            });
        }

        Schema::dropIfExists('planning_baselines');
    }
};
