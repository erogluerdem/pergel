<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'page_lock_failed_attempts')) {
                $table->unsignedInteger('page_lock_failed_attempts')->default(0)->after('page_locked');
            }
            if (!Schema::hasColumn('users', 'page_lock_locked_until')) {
                $table->timestamp('page_lock_locked_until')->nullable()->after('page_lock_failed_attempts');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'page_lock_locked_until')) {
                $table->dropColumn('page_lock_locked_until');
            }
            if (Schema::hasColumn('users', 'page_lock_failed_attempts')) {
                $table->dropColumn('page_lock_failed_attempts');
            }
        });
    }
};
