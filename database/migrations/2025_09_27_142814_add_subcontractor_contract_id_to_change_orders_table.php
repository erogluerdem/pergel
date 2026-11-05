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
        Schema::table('change_orders', function (Blueprint $table) {
            $table->foreignId('subcontractor_contract_id')->nullable()->after('project_id')->constrained('subcontractor_contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_orders', function (Blueprint $table) {
            $table->dropForeign(['subcontractor_contract_id']);
            $table->dropColumn('subcontractor_contract_id');
        });
    }
};
