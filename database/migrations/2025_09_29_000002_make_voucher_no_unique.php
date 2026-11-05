<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('vouchers') && Schema::hasColumn('vouchers','no')) {
            Schema::table('vouchers', function (Blueprint $table) {
                // create unique index if not exists
                try {
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $indexes = array_map(function($idx){ return $idx->getName(); }, $sm->listTableIndexes('vouchers'));
                } catch (\Exception $e) {
                    $indexes = [];
                }
                if (!in_array('vouchers_no_unique', $indexes)) {
                    $table->unique('no', 'vouchers_no_unique');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('vouchers') && Schema::hasColumn('vouchers','no')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->dropUnique('vouchers_no_unique');
            });
        }
    }
};
