<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flow_forecasts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->date('forecast_date');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('inflow_planned', 15, 2)->default(0);
            $table->decimal('inflow_actual', 15, 2)->default(0);
            $table->decimal('outflow_planned', 15, 2)->default(0);
            $table->decimal('outflow_actual', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->default(0);
            $table->string('currency', 3)->default('TRY');
            $table->decimal('variance_percentage', 5, 2)->default(0); // Sapma %
            $table->enum('status', ['draft', 'confirmed', 'revised'])->default('draft');
            $table->text('notes')->nullable();
            $table->integer('confidence_level')->default(80); // 0-100
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flow_forecasts');
    }
};
