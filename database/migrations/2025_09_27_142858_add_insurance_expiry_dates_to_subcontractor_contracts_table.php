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
        Schema::table('subcontractor_contracts', function (Blueprint $table) {
            $table->json('insurance_expiry_dates')->nullable()->after('documents'); // Sigorta bitiş tarihleri
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcontractor_contracts', function (Blueprint $table) {
            $table->dropColumn('insurance_expiry_dates');
        });
    }
};
