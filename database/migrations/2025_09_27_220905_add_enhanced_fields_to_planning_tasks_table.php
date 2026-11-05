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
        if (Schema::hasTable('planning_tasks')) {
            Schema::table('planning_tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('planning_tasks', 'dependencies')) {
                    $table->json('dependencies')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('planning_tasks', 'resources')) {
                    $table->json('resources')->nullable();
                }
                if (!Schema::hasColumn('planning_tasks', 'budget_wbs')) {
                    $table->string('budget_wbs')->nullable();
                }
                if (!Schema::hasColumn('planning_tasks', 'documents')) {
                    $table->json('documents')->nullable();
                }
                if (!Schema::hasColumn('planning_tasks', 'critical_path')) {
                    $table->boolean('critical_path')->default(false);
                }
                if (!Schema::hasColumn('planning_tasks', 'is_milestone')) {
                    $table->boolean('is_milestone')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planning_tasks', function (Blueprint $table) {
            $table->dropColumn(['dependencies', 'resources', 'budget_wbs', 'documents', 'critical_path']);
        });
    }
};
