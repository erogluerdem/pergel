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
        Schema::create('progress_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            
            $table->string('payment_number')->unique();
            $table->date('payment_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->text('description')->nullable();
            
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'paid', 'cancelled'])
                ->default('pending');
            
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('contract_id');
            $table->index('project_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_date');
            $table->index('due_date');
            $table->index('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_payments');
    }
};
