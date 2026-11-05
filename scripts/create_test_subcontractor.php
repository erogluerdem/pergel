<?php

// Küçük test scripti: Laravel uygulamasını bootstrap edip Subcontractor yaratır.
// Çalıştırmak için: php scripts/create_test_subcontractor.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $data = [
        'company_name' => 'AUTOTEST ' . date('His'),
        'contact_person' => 'Test User',
        'email' => 'autotest+' . time() . '@example.com',
        'phone' => '555000' . rand(100,999),
        'expertise_area' => 'İnşaat',
        'status' => 'pending',
    ];

    $sub = App\Models\Subcontractor::create($data);
    echo "SUCCESS: created Subcontractor id={$sub->id} name={$sub->company_name}\n";
} catch (\Throwable $e) {
    echo "ERROR: ".get_class($e)." - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
