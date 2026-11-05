<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('opportunities', function (Blueprint $table) {
            // Türkiye için varsayılan 18% KDV
            if (!Schema::hasColumn('opportunities', 'kdv_percent')) {
                $table->unsignedTinyInteger('kdv_percent')->default(18)->after('estimated_value');
            }
            if (!Schema::hasColumn('opportunities', 'kdv_amount')) {
                $table->decimal('kdv_amount', 20, 2)->nullable()->after('kdv_percent');
            }
            if (!Schema::hasColumn('opportunities', 'estimated_value_kdv')) {
                $table->decimal('estimated_value_kdv', 20, 2)->nullable()->after('kdv_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('opportunities', 'estimated_value_kdv')) {
                $table->dropColumn('estimated_value_kdv');
            }
            if (Schema::hasColumn('opportunities', 'kdv_amount')) {
                $table->dropColumn('kdv_amount');
            }
            if (Schema::hasColumn('opportunities', 'kdv_percent')) {
                $table->dropColumn('kdv_percent');
            }
        });
    }
};
