<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Voucher;

try {
    $v = Voucher::create(['type'=>'collection','date'=>now()->toDateString(),'no'=>'TEST-001','account'=>'Kasa','amount'=>10,'created_by'=>1]);
    echo "created " . $v->id . "\n";
    $v2 = Voucher::create(['type'=>'payment','date'=>now()->toDateString(),'no'=>'TEST-002','account'=>'Banka','amount'=>20,'created_by'=>1]);
    echo "created " . $v2->id . "\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
