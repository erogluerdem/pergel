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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'attachments')) {
                $table->json('attachments')->nullable()->after('location');
            }
            if (!Schema::hasColumn('projects', 'cover_image')) {
                // place cover_image after attachments if possible
                $table->string('cover_image')->nullable()->after('attachments');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
            if (Schema::hasColumn('projects', 'attachments')) {
                $table->dropColumn('attachments');
            }
        });
    }
};
