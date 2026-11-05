<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'credit_limit')) {
                $table->decimal('credit_limit', 16, 2)->default(0)->after('overdue_balance');
            }
            if (! Schema::hasColumn('customers', 'risk_score')) {
                $table->integer('risk_score')->default(0)->after('credit_limit');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'risk_score')) {
                $table->dropColumn('risk_score');
            }
            if (Schema::hasColumn('customers', 'credit_limit')) {
                $table->dropColumn('credit_limit');
            }
        });
    }
};
