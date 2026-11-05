<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('expense_type'); // travel, meal, accommodation, transport, training, etc.
            $table->string('category'); // business, personal_reimbursement, company_paid
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('TRY');
            $table->date('expense_date');
            $table->date('submission_date');
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('project_code')->nullable();
            $table->string('department')->nullable();
            $table->json('custom_fields')->nullable();
            $table->decimal('reimbursed_amount', 10, 2)->nullable();
            $table->date('reimbursement_date')->nullable();
            $table->string('payment_method')->nullable(); // bank_transfer, cash, cheque
            $table->string('reference_no')->unique();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurring_frequency')->nullable(); // monthly, weekly, yearly
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['status', 'expense_date']);
            $table->index(['employee_id', 'expense_date']);
            $table->index(['department', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_expenses');
    }
};
