<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('customers', 'overdue_balance')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->decimal('overdue_balance', 16, 2)->default(0)->after('balance');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('customers', 'overdue_balance')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('overdue_balance');
            });
        }
    }
};
