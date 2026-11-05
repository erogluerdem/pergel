<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$id = 7;
$lat = 37.989044;
$lng = 34.775685;
$locJson = json_encode(['lat' => $lat, 'lng' => $lng]);
try {
    $updated = DB::table('projects')->where('id', $id)->update([
        'location' => $locJson,
        'latitude' => $lat,
        'longitude' => $lng,
        'location_province' => 'Niğde',
        'location_district' => null,
        'updated_at' => DB::raw('NOW()')
    ]);
    echo "Updated rows: " . $updated . "\n";
    $row = DB::table('projects')->where('id', $id)->first();
    echo "Row: " . json_encode($row) . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
