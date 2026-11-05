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
        Schema::table('incident_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('incident_reports', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('incident_reports', 'evidence_path')) {
                $table->string('evidence_path')->nullable();
            }
            if (!Schema::hasColumn('incident_reports', 'action_responsible')) {
                $table->string('action_responsible')->nullable();
            }
            if (!Schema::hasColumn('incident_reports', 'action_deadline')) {
                $table->date('action_deadline')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn(['location', 'evidence_path', 'action_responsible', 'action_deadline']);
        });
    }
};
