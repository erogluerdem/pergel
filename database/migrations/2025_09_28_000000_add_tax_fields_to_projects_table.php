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
            if (!Schema::hasColumn('projects', 'tax_number')) {
                $table->string('tax_number')->nullable()->after('status');
            }
            if (!Schema::hasColumn('projects', 'tax_office')) {
                $table->string('tax_office')->nullable()->after('tax_number');
            }
            if (!Schema::hasColumn('projects', 'kdv_rate')) {
                $table->decimal('kdv_rate', 5, 2)->nullable()->after('budget');
            }
            if (!Schema::hasColumn('projects', 'kdv_amount')) {
                $table->decimal('kdv_amount', 12, 2)->nullable()->after('kdv_rate');
            }
            if (!Schema::hasColumn('projects', 'invoice_uuid')) {
                $table->string('invoice_uuid', 36)->nullable()->after('kdv_amount')->index();
            }
            if (!Schema::hasColumn('projects', 'other_taxes')) {
                $table->json('other_taxes')->nullable()->after('invoice_uuid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'other_taxes')) {
                $table->dropColumn('other_taxes');
            }
            if (Schema::hasColumn('projects', 'invoice_uuid')) {
                $table->dropColumn('invoice_uuid');
            }
            if (Schema::hasColumn('projects', 'kdv_amount')) {
                $table->dropColumn('kdv_amount');
            }
            if (Schema::hasColumn('projects', 'kdv_rate')) {
                $table->dropColumn('kdv_rate');
            }
            if (Schema::hasColumn('projects', 'tax_office')) {
                $table->dropColumn('tax_office');
            }
            if (Schema::hasColumn('projects', 'tax_number')) {
                $table->dropColumn('tax_number');
            }
        });
    }
};
