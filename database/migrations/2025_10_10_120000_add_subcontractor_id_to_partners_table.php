<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('partners') && ! Schema::hasColumn('partners', 'subcontractor_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->unsignedBigInteger('subcontractor_id')->nullable()->after('id')->index();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('partners') && Schema::hasColumn('partners', 'subcontractor_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropColumn('subcontractor_id');
            });
        }
    }
};
