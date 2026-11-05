<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'progress_percent')) {
                $table->decimal('progress_percent', 5, 2)->nullable()->after('budget');
            }
            if (!Schema::hasColumn('projects', 'spent')) {
                $table->decimal('spent', 20, 2)->nullable()->after('progress_percent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['progress_percent','spent']);
        });
    }
};
