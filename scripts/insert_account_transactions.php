<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$accs = DB::table('accounts')->whereIn('name',['Garanti Bankası - Ticari','Merkez Kasa','Akbank - USD Hesabı'])->get()->keyBy('name');
if(!isset($accs['Garanti Bankası - Ticari'])){
    echo "Accounts not found\n"; exit(1);
}

$now = Carbon::now()->toDateTimeString();
DB::table('account_transactions')->insert([
    [
        'account_id' => $accs['Garanti Bankası - Ticari']->id,
        'date' => Carbon::now()->subMonths(6)->toDateString(),
        'description' => 'Açılış bakiyesi',
        'debit' => 1250000,
        'credit' => 0,
        'balance' => 1250000,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'account_id' => $accs['Garanti Bankası - Ticari']->id,
        'date' => Carbon::now()->subMonths(1)->toDateString(),
        'description' => 'Tahsilat',
        'debit' => 50000,
        'credit' => 0,
        'balance' => 1300000,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'account_id' => $accs['Merkez Kasa']->id,
        'date' => Carbon::now()->subYear()->toDateString(),
        'description' => 'Kasa açılışı',
        'debit' => 75000,
        'credit' => 0,
        'balance' => 75000,
        'created_at' => $now,
        'updated_at' => $now,
    ],
    [
        'account_id' => $accs['Akbank - USD Hesabı']->id,
        'date' => Carbon::now()->subMonths(3)->toDateString(),
        'description' => 'Açılış',
        'debit' => 50000,
        'credit' => 0,
        'balance' => 50000,
        'created_at' => $now,
        'updated_at' => $now,
    ],
]);

echo "Inserted account transactions\n";
