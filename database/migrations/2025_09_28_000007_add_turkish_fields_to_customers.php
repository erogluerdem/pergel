<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'tax_office')) {
                $table->string('tax_office')->nullable()->after('tax_number');
            }
            if (!Schema::hasColumn('customers', 'mersis')) {
                $table->string('mersis')->nullable()->after('tax_office');
            }
            if (!Schema::hasColumn('customers', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
            if (!Schema::hasColumn('customers', 'postcode')) {
                $table->string('postcode')->nullable()->after('district');
            }
            if (!Schema::hasColumn('customers', 'tc_id')) {
                $table->string('tc_id', 11)->nullable()->after('postcode');
            }
            if (!Schema::hasColumn('customers', 'bank')) {
                $table->string('bank')->nullable()->after('country');
            }
            if (!Schema::hasColumn('customers', 'iban')) {
                $table->string('iban')->nullable()->after('bank');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'iban')) $table->dropColumn('iban');
            if (Schema::hasColumn('customers', 'bank')) $table->dropColumn('bank');
            if (Schema::hasColumn('customers', 'tc_id')) $table->dropColumn('tc_id');
            if (Schema::hasColumn('customers', 'postcode')) $table->dropColumn('postcode');
            if (Schema::hasColumn('customers', 'district')) $table->dropColumn('district');
            if (Schema::hasColumn('customers', 'mersis')) $table->dropColumn('mersis');
            if (Schema::hasColumn('customers', 'tax_office')) $table->dropColumn('tax_office');
        });
    }
};
