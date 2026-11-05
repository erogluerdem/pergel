<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'customer_type')) {
                $table->enum('customer_type', ['land_owner', 'contractor', 'investor', 'end_user', 'other'])->nullable()->after('name')->comment('Arsa Sahibi, Taşeron, Yatırımcı, vb.');
            }
            if (!Schema::hasColumn('customers', 'customer_segment')) {
                $table->string('customer_segment')->nullable()->after('customer_type')->comment('Müşteri Segmenti (Kurumsal, KOBİ, Bireysel vb.)');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'customer_type')) {
                $table->dropColumn('customer_type');
            }
            if (Schema::hasColumn('customers', 'customer_segment')) {
                $table->dropColumn('customer_segment');
            }
        });
    }
};
