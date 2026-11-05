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
        Schema::table('bim_models', function (Blueprint $table) {
            $table->bigInteger('file_size')->nullable()->after('file_path');
            $table->json('metadata')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bim_models', function (Blueprint $table) {
            $table->dropColumn('file_size');
        });
    }
};
