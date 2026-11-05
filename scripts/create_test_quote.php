<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SubcontractorQuote;

$q = SubcontractorQuote::create([
    'subcontractor_id' => 10,
    'requester_name' => 'Test User',
    'requester_email' => 'test@example.com',
    'requester_phone' => '+905551234567',
    'message' => 'Deneme talebi via script',
]);

print_r($q->toArray());
