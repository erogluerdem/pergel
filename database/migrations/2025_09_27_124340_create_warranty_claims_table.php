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
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closeout_id')->constrained()->onDelete('cascade');
            $table->string('claim_no')->unique();
            $table->string('subject');
            $table->date('notification_date');
            $table->foreignId('equipment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('contractor_id')->nullable()->constrained('vendors')->onDelete('set null');
            $table->enum('status', ['open', 'assigned', 'intervened', 'approved', 'closed'])->default('open');
            $table->string('evidence_photo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
