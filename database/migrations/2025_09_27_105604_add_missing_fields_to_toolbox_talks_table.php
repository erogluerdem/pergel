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
        Schema::table('toolbox_talks', function (Blueprint $table) {
            $table->json('signature_data')->nullable()->after('participants');
            $table->unsignedBigInteger('conducted_by')->nullable()->after('signature_data');
            $table->timestamp('conducted_at')->nullable()->after('conducted_by');
        });
    }

    public function down(): void
    {
        Schema::table('toolbox_talks', function (Blueprint $table) {
            $table->dropColumn(['signature_data', 'conducted_by', 'conducted_at']);
        });
    }
};
