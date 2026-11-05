<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Voucher;

try {
    $v = Voucher::find(102);
    if(!$v) { echo "Voucher 102 not found\n"; exit; }
    $v->no = 'TEST-001';
    $v->save();
    echo "updated\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
