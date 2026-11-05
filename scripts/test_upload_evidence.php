<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile as LaravelUploadedFile;
use App\Http\Controllers\QhseController;

// prepare a small temp file to act as "photo"
$tmpDir = __DIR__ . '/../storage/app/public/qhse';
if(!is_dir($tmpDir)) mkdir($tmpDir,0755,true);
$path = $tmpDir . '/test_upload_' . time() . '.jpg';
file_put_contents($path, str_repeat('0', 1024)); // 1KB dummy file

// create an UploadedFile instance (last parameter true = test)
$uploaded = new LaravelUploadedFile($path, basename($path), null, null, true);

// create a request and set the file
$req = Request::create('/api/qhse/media','POST');
$req->files->set('file', $uploaded);

$controller = new QhseController();
$res = $controller->uploadEvidence($req);

// output response body
if($res instanceof Illuminate\Http\JsonResponse){
    echo $res->getContent() . PHP_EOL;
} else {
    var_export($res);
}

// list files in storage/qhse
$files = glob(__DIR__ . '/../storage/app/public/qhse/*');
echo "Stored files:\n";
foreach($files as $f) echo basename($f) . "\n";
