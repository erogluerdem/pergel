<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tender_packages', function (Blueprint $table) {
            $table->json('bidders')->nullable()->after('boq');
            $table->timestamp('published_at')->nullable()->after('bidders');
        });
    }

    public function down()
    {
        Schema::table('tender_packages', function (Blueprint $table) {
            $table->dropColumn(['bidders','published_at']);
        });
    }
};
