<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_risks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->enum('risk_type', ['overdue_payment', 'cash_shortage', 'currency_fluctuation', 'vendor_default', 'budget_overrun'])->default('overdue_payment');
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->integer('days_overdue')->nullable();
            $table->decimal('risk_amount', 15, 2)->default(0);
            $table->decimal('impact_score', 5, 2)->default(0); // 0-100
            $table->decimal('probability_score', 5, 2)->default(0); // 0-100
            $table->integer('risk_score')->default(0); // impact * probability / 100
            $table->text('description')->nullable();
            $table->string('mitigation_strategy')->nullable();
            $table->date('identified_date');
            $table->date('target_resolution_date')->nullable();
            $table->enum('status', ['open', 'monitoring', 'resolved'])->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_risks');
    }
};
