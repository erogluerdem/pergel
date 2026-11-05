<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_plans', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('payment_plans', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('payment_plans', 'interval_days')) {
                $table->integer('interval_days')->default(30)->after('installment_count');
            }
            if (!Schema::hasColumn('payment_plans', 'down_payment_percentage')) {
                $table->decimal('down_payment_percentage', 5, 2)->default(0)->after('interval_days');
            }
            if (!Schema::hasColumn('payment_plans', 'is_template')) {
                $table->boolean('is_template')->default(true)->after('down_payment_percentage');
            }
        });

        // Make original columns nullable for standalone flexible payment plan templates
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable()->change();
            $table->string('plan_no')->nullable()->change();
            $table->decimal('total_amount', 15, 2)->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->integer('payment_interval_days')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'interval_days', 'down_payment_percentage', 'is_template']);
        });
    }
};
