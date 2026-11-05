<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'province')) {
                $table->string('province')->nullable()->after('cadde_sokak');
            }
            if (!Schema::hasColumn('projects', 'district')) {
                $table->string('district')->nullable()->after('province');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'district')) {
                $table->dropColumn('district');
            }
            if (Schema::hasColumn('projects', 'province')) {
                $table->dropColumn('province');
            }
        });
    }
};
