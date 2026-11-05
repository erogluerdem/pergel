<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->string('symbol');
                $table->decimal('exchange_rate', 10, 6);
                $table->timestamp('rate_updated_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('currency_exchanges')) {
            Schema::create('currency_exchanges', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
                $table->string('from_currency', 3);
                $table->string('to_currency', 3)->default('TRY');
                $table->decimal('from_amount', 15, 2);
                $table->decimal('to_amount', 15, 2);
                $table->decimal('exchange_rate', 10, 6);
                $table->decimal('commission_percentage', 5, 2)->default(0);
                $table->decimal('commission_amount', 15, 2)->default(0);
                $table->timestamp('exchange_date');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['invoice_id', 'created_at']);
            });
        }

        if (!Schema::hasColumn('invoices', 'currency')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('currency', 3)->default('TRY')->after('amount');
                $table->decimal('exchange_rate', 10, 6)->default(1)->after('currency');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('invoices', 'currency')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn(['currency', 'exchange_rate']);
            });
        }

        Schema::dropIfExists('currency_exchanges');
        Schema::dropIfExists('currencies');
    }
};
