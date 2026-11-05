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
        Schema::table('planning_tasks', function (Blueprint $table) {
            $table->json('dependencies')->nullable()->after('notes');
            $table->json('resources')->nullable()->after('dependencies');
            $table->string('budget_wbs')->nullable()->after('resources');
            $table->json('documents')->nullable()->after('budget_wbs');
            $table->boolean('critical_path')->default(false)->after('documents');
        });
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
