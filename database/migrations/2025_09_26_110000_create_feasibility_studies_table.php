<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feasibility_studies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('opportunity_id')->nullable();
            $table->string('project_type')->nullable();
            $table->decimal('total_area', 14,2)->nullable();
            $table->string('region')->nullable();
            $table->decimal('unit_sale_price', 14,2)->nullable(); // TL/m2
            $table->decimal('other_revenue', 14,2)->default(0);
            $table->decimal('land_cost', 20,2)->default(0);
            $table->decimal('construction_cost_per_m2', 14,2)->default(0);
            $table->decimal('soft_costs_percent',5,2)->default(10);
            $table->integer('construction_months')->default(12);
            $table->integer('sales_months')->default(12);
            $table->decimal('equity',20,2)->default(0);
            $table->decimal('debt',20,2)->default(0);
            $table->decimal('interest_rate_percent',5,2)->default(12);
            $table->json('scenario_meta')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feasibility_studies');
    }
};
