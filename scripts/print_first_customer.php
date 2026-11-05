<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$c = App\Models\Customer::first();
if ($c) {
    echo $c->id . '||' . $c->name . PHP_EOL;
} else {
    echo "NO\n";
}
