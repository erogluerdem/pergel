<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\URL;

echo URL::temporarySignedRoute('tenders.package.access', now()->addDays(7), ['id'=>1]) . "\n";
