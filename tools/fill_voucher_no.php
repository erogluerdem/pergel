<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Voucher;

$updated = 0;
foreach (Voucher::whereNull('no')->orWhere('no','')->cursor() as $v) {
    $v->no = 'VCH-' . str_pad($v->id, 6, '0', STR_PAD_LEFT);
    $v->save();
    $updated++;
}
echo "Updated: {$updated}\n";
