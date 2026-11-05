<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tasks', 'deadline')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->timestamp('deadline')->nullable()->after('assigned_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tasks', 'deadline')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('deadline');
            });
        }
    }
};
