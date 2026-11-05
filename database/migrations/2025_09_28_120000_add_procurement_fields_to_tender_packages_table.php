<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tender_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('tender_packages', 'procurement_type')) {
                $table->string('procurement_type')->nullable()->after('name');
            }
            if (!Schema::hasColumn('tender_packages', 'estimated_cost')) {
                $table->decimal('estimated_cost', 15, 2)->nullable()->after('procurement_type');
            }
            if (!Schema::hasColumn('tender_packages', 'currency')) {
                $table->string('currency', 3)->nullable()->after('estimated_cost');
            }
            if (!Schema::hasColumn('tender_packages', 'contract_duration_days')) {
                $table->integer('contract_duration_days')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('tender_packages', 'guarantee_percent')) {
                $table->decimal('guarantee_percent', 8, 2)->nullable()->after('contract_duration_days');
            }
            if (!Schema::hasColumn('tender_packages', 'tender_reference')) {
                $table->string('tender_reference')->nullable()->after('guarantee_percent');
            }
            if (!Schema::hasColumn('tender_packages', 'announcement_date')) {
                $table->date('announcement_date')->nullable()->after('tender_reference');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tender_packages', function (Blueprint $table) {
            if (Schema::hasColumn('tender_packages', 'announcement_date')) $table->dropColumn('announcement_date');
            if (Schema::hasColumn('tender_packages', 'tender_reference')) $table->dropColumn('tender_reference');
            if (Schema::hasColumn('tender_packages', 'guarantee_percent')) $table->dropColumn('guarantee_percent');
            if (Schema::hasColumn('tender_packages', 'contract_duration_days')) $table->dropColumn('contract_duration_days');
            if (Schema::hasColumn('tender_packages', 'currency')) $table->dropColumn('currency');
            if (Schema::hasColumn('tender_packages', 'estimated_cost')) $table->dropColumn('estimated_cost');
            if (Schema::hasColumn('tender_packages', 'procurement_type')) $table->dropColumn('procurement_type');
        });
    }
};
