<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('approvals')) {
            Schema::create('approvals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('requisition_id')->nullable()->constrained('requisitions')->onDelete('cascade');
                $table->string('approval_level')->default('manager'); // manager, finance, executive, final
                $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('comments')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
