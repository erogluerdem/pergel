<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if (!$user) {
    $user = new \App\Models\User([
        'name' => 'Ahmet Yılmaz',
        'email' => 'ahmet.yilmaz@pergel.com.tr',
        'department' => 'Şantiye & Proje Direktörü',
        'is_admin' => true,
    ]);
}
\Illuminate\Support\Facades\Auth::setUser($user);

$html = view('auth-lock-screen', ['user' => $user])->render();
file_put_contents(__DIR__ . '/../public/preview-lockscreen.html', $html);
echo 'Preview generated: ' . strlen($html) . ' bytes' . PHP_EOL;
