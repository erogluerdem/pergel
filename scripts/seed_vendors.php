<?php
// bootstrap and create two vendors for testing
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vendor;

Vendor::create(['name'=>'A İnşaat','tax_number'=>'1234567890','specialty'=>'Kaba İnşaat, İnce İşler','status'=>'approved','performance_score'=>4.5,'contact_person'=>'Ali Yılmaz','phone'=>'+90 532 000 0000','email'=>'ali@example.com','address'=>'Mah. Sok. No:1','city'=>'İstanbul','country'=>'Türkiye','iban'=>'TR000000000000000000000000']);
Vendor::create(['name'=>'B Metal','tax_number'=>'9876543210','specialty'=>'Çelik Konstrüksiyon','status'=>'conditional','performance_score'=>3.2,'contact_person'=>'Veli Çelik','phone'=>'+90 532 111 1111','email'=>'veli@example.com','address'=>'Sanayi Cad. No:2','city'=>'Ankara','country'=>'Türkiye']);

echo "seeded\n";
