<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetadataToBimModels extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bim_models')) {
            Schema::table('bim_models', function (Blueprint $table) {
                if (!Schema::hasColumn('bim_models', 'metadata')) {
                    $table->json('metadata')->nullable()->after('file_path');
                }
                if (!Schema::hasColumn('bim_models', 'processed_at')) {
                    $table->timestamp('processed_at')->nullable()->after('metadata');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bim_models')) {
            Schema::table('bim_models', function (Blueprint $table) {
                if (Schema::hasColumn('bim_models', 'metadata')) $table->dropColumn('metadata');
                if (Schema::hasColumn('bim_models', 'processed_at')) $table->dropColumn('processed_at');
            });
        }
    }
}
