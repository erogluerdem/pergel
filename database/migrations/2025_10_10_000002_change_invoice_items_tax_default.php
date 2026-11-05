<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('invoice_items')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                // only change default; do not modify existing data
                $table->decimal('tax_rate', 5, 2)->default(20.00)->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('invoice_items')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                $table->decimal('tax_rate', 5, 2)->default(18.00)->change();
            });
        }
    }
};
