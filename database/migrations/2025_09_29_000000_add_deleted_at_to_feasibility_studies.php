<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('feasibility_studies')) return;
        Schema::table('feasibility_studies', function (Blueprint $table) {
            if (!Schema::hasColumn('feasibility_studies', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('feasibility_studies')) return;
        Schema::table('feasibility_studies', function (Blueprint $table) {
            if (Schema::hasColumn('feasibility_studies', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
