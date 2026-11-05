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
        Schema::table('permits', function (Blueprint $table) {
            // permit_type -> type olarak değiştir
            $table->renameColumn('permit_type', 'type');
        });
        Schema::table('permits', function (Blueprint $table) {
            if (!Schema::hasColumn('permits', 'permit_type')) {
                $table->string('permit_type')->nullable()->after('type');
            }
            // Yeni alanlar
            $table->text('description')->nullable()->after('type');
            $table->string('responsible_person')->nullable()->after('location');
            $table->text('risk_assessment')->nullable()->after('checklist');
            $table->text('special_conditions')->nullable()->after('risk_assessment');
            $table->unsignedBigInteger('requested_by')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('requested_by');
        });
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->renameColumn('type', 'permit_type');
            $table->dropColumn(['description', 'responsible_person', 'risk_assessment', 'special_conditions', 'requested_by', 'approved_at']);
        });
    }
};
