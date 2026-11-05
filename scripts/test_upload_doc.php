<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SubcontractorController;
use Illuminate\Http\Request;

// create a small temp file
$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_doc.docx';
file_put_contents($tmp, "test doc content");

$uploaded = new UploadedFile($tmp, 'test_doc.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

$request = Request::create('/','POST', [], [], ['file' => $uploaded], [], null);
$request->merge(['title' => 'Test Doc','description' => 'Uploaded by script']);

$controller = new SubcontractorController();
$response = $controller->uploadDocument($request, 1);
print_r($response);
