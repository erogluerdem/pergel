<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_targets')) {
            return;
        }

        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->decimal('target_amount', 15, 2);
            $table->decimal('target_units', 8, 2)->default(0);
            $table->decimal('achieved_amount', 15, 2)->default(0);
            $table->decimal('achieved_units', 8, 2)->default(0);
            $table->enum('status', ['planned', 'in_progress', 'achieved', 'failed', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->decimal('bonus_percentage', 5, 2)->default(0); // Bonus if target achieved
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'period_start']);
            $table->index(['project_id', 'status']);
            $table->index('period_end');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
