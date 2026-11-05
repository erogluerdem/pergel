<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'subcontractor_id')) {
                $table->unsignedBigInteger('subcontractor_id')->nullable()->after('contractor_name');
                $table->foreign('subcontractor_id')->references('id')->on('subcontractors')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'subcontractor_id')) {
                $table->dropForeign([ 'subcontractor_id' ]);
                $table->dropColumn('subcontractor_id');
            }
        });
    }
};
