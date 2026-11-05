<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_bank_transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null')->after('capital_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('cash_bank_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_id');
        });
    }
};
