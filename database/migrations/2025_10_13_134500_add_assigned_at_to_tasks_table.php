<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tasks', 'assigned_at')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_to');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tasks', 'assigned_at')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('assigned_at');
            });
        }
    }
};
