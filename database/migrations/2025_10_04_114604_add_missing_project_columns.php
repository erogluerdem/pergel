<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'location_province')) {
                $table->string('location_province')->nullable()->after('attachments');
            }
            if (!Schema::hasColumn('projects', 'location_district')) {
                $table->string('location_district')->nullable()->after('location_province');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'location_district')) {
                $table->dropColumn('location_district');
            }
            if (Schema::hasColumn('projects', 'location_province')) {
                $table->dropColumn('location_province');
            }
        });
    }
};
