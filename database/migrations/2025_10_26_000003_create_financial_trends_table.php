<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_trends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('period_type', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly');
            
            // Nakit Akım Metrikleri
            $table->decimal('total_inflow', 15, 2)->default(0);
            $table->decimal('total_outflow', 15, 2)->default(0);
            $table->decimal('net_cash_flow', 15, 2)->default(0);
            
            // Ödeme Metrikleri
            $table->integer('payments_count')->default(0);
            $table->integer('on_time_payments')->default(0);
            $table->integer('late_payments')->default(0);
            $table->decimal('payment_timeliness_ratio', 5, 2)->default(0); // %
            
            // Tahsilat Metrikleri
            $table->integer('collections_count')->default(0);
            $table->decimal('average_collection_period', 8, 2)->default(0); // gün
            $table->decimal('collection_ratio', 5, 2)->default(0); // %
            
            // Gider Metrikleri
            $table->decimal('operating_expenses', 15, 2)->default(0);
            $table->decimal('capital_expenses', 15, 2)->default(0);
            $table->decimal('expense_ratio', 5, 2)->default(0); // giderlerin gelire oranı %
            
            // Bütçe vs Gerçekleşme
            $table->decimal('budgeted_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->decimal('budget_variance', 5, 2)->default(0); // %
            
            // Trend Değişimleri
            $table->decimal('growth_rate', 5, 2)->default(0); // %
            $table->decimal('trend_direction', 5, 2)->default(0); // -100 to 100
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->index(['project_id', 'period_start', 'period_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_trends');
    }
};
