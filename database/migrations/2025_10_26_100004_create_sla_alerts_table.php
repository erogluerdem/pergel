<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sla_alerts')) {
            Schema::create('sla_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->enum('alert_type', ['response', 'resolution', 'warning', 'critical'])->comment('Uyarı tipi');
            $table->timestamp('triggered_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('service_request_id');
            $table->index('alert_type');
            $table->index('triggered_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_alerts');
    }
};
