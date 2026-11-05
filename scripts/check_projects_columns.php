<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    $cols = DB::select("SHOW COLUMNS FROM projects");
    echo "Columns in projects:\n";
    foreach ($cols as $c) {
        echo $c->Field . " - " . $c->Type . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Also print hasColumn checks
echo "hasColumn latitude: " . (Schema::hasColumn('projects','latitude') ? 'yes' : 'no') . "\n";
echo "hasColumn longitude: " . (Schema::hasColumn('projects','longitude') ? 'yes' : 'no') . "\n";
