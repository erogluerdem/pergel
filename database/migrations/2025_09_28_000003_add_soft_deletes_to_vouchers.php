<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('voucher_lines')) {
            Schema::table('voucher_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('voucher_lines','deleted_at')) $table->softDeletes();
            });
        }
        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', function (Blueprint $table) {
                if (!Schema::hasColumn('vouchers','deleted_at')) $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('voucher_lines')) {
            Schema::table('voucher_lines', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
