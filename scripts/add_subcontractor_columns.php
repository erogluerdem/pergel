<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

$table = 'subcontractors';
if (!Schema::hasTable($table)) {
    echo "Table $table does not exist\n";
    exit(1);
}

if (!Schema::hasColumn($table, 'logo')) {
    Schema::table($table, function (Blueprint $t) {
        $t->string('logo')->nullable()->after('email');
    });
    echo "Added logo column\n";
} else { echo "logo column exists\n"; }

if (!Schema::hasColumn($table, 'performance_score')) {
    Schema::table($table, function (Blueprint $t) {
        $t->decimal('performance_score', 5, 2)->default(0)->after('expertise_area');
    });
    echo "Added performance_score column\n";
} else { echo "performance_score exists\n"; }

if (!Schema::hasColumn($table, 'safety_score')) {
    Schema::table($table, function (Blueprint $t) {
        $t->decimal('safety_score', 5, 2)->default(0)->after('performance_score');
    });
    echo "Added safety_score column\n";
} else { echo "safety_score exists\n"; }

if (!Schema::hasColumn($table, 'deleted_at')) {
    Schema::table($table, function (Blueprint $t) {
        $t->softDeletes();
    });
    echo "Added deleted_at (soft deletes)\n";
} else { echo "deleted_at exists\n"; }

echo "Done\n";
