<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $u = \App\Models\User::find(1);
    if ($u) {
        echo json_encode(['id' => $u->id, 'email' => $u->email]);
    } else {
        echo "NO_USER";
    }
} catch (\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage();
}
