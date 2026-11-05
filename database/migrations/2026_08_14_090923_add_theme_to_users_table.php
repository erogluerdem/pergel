<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Kullanıcı tema tercihi: theme (string) ve dark_mode (boolean)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tema adı: santiye | corporate | nature | industrial | health
            $table->string('theme', 30)->default('santiye')->after('remember_token');
            // Karanlık mod tercihi
            $table->boolean('dark_mode')->default(false)->after('theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme', 'dark_mode']);
        });
    }
};
