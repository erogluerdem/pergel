<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_bank_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('cash_bank_transactions','currency')) {
                $table->string('currency',3)->default('TRY')->after('account_id');
            }
            if (!Schema::hasColumn('cash_bank_transactions','exchange_rate')) {
                $table->decimal('exchange_rate', 18, 6)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('cash_bank_transactions','amount_tl')) {
                $table->decimal('amount_tl', 18, 2)->nullable()->after('exchange_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cash_bank_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cash_bank_transactions','amount_tl')) {
                $table->dropColumn('amount_tl');
            }
            if (Schema::hasColumn('cash_bank_transactions','exchange_rate')) {
                $table->dropColumn('exchange_rate');
            }
            if (Schema::hasColumn('cash_bank_transactions','currency')) {
                $table->dropColumn('currency');
            }
        });
    }
};
