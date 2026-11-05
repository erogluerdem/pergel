<?php

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the framework (we need Eloquent configured). We'll include autoload and then the application bootstrap
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QhseTemplate;

QhseTemplate::create([
    'key' => 'tpl-concrete-prepour',
    'title' => 'Beton Dökümü Öncesi Kontrol Listesi',
    'discipline' => 'construction',
    'items' => json_encode([
        ['id'=>'t1','text'=>'Kalıp yerleşimi ve bracing doğru mu?'],
        ['id'=>'t2','text'=>'Donatı yerleşimi proje şartlarına uygun mu?']
    ])
]);

QhseTemplate::create([
    'key' => 'tpl-welding',
    'title' => 'Kaynak Kontrolü Formu',
    'discipline' => 'mechanical',
    'items' => json_encode([
        ['id'=>'s1','text'=>'Kaynak prosesi onaylı mı?'],
        ['id'=>'s2','text'=>'Görsel kusur kontrolü yapıldı mı?']
    ])
]);

echo "Seed tamamlandı\n";
