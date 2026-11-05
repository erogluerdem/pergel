<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'dev.tester+1@example.local';
$u = App\Models\User::where('email', $email)->first();
if (!$u) {
    echo "User not found\n";
    exit(1);
}
try {
    $u->assignRole('user');
    echo "Assigned role 'user' to user id {$u->id}\n";
} catch (Throwable $e) {
    echo "Assign failed: {$e->getMessage()}\n";
    exit(2);
}
