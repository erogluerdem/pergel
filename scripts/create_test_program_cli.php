<?php
// Simple script to create a test Program using the Laravel application bootstrap
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Program;

$name = 'Test Program '.date('Ymd_His');
$p = Program::create([
    'name' => $name,
    'description' => 'Otomatik test programı',
    'manager' => 'Test Kullanıcı',
    'strategic_objective' => 'Test amaç - demo',
    'status' => 'planning',
    'total_budget' => 1000000,
    'spent_to_date' => 10000,
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+1 year')),
]);

echo "Created Program: {$p->id} - {$p->name}\n";
