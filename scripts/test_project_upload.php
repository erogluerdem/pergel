<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Project;
use App\Http\Controllers\ProjectController;

// create temp files to simulate uploads
// 1) a small PNG image (1x1 transparent)
$tmpImage = sys_get_temp_dir() . '/test-cover.png';
file_put_contents($tmpImage, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg=='));

// 2) a minimal PDF file
$tmpPdf = sys_get_temp_dir() . '/test-doc.pdf';
$pdfContent = "%PDF-1.1\n%âãÏÓ\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Count 0 >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
file_put_contents($tmpPdf, $pdfContent);

// 3) a plain text attachment (allowed)
$tmpTxt = sys_get_temp_dir() . '/test-attach.txt';
file_put_contents($tmpTxt, "Hello world");

$id = 1;
$project = Project::find($id);
if (!$project) {
    $project = Project::create(['name' => 'Upload Test Project']);
    $id = $project->id;
    echo "Created project with id: $id\n";
}

// Build request with files
$files = [
    'attachments' => [
        new UploadedFile($tmpTxt, 'test1.txt', 'text/plain', null, true),
    ],
    'cover_image' => new UploadedFile($tmpImage, 'cover.png', 'image/png', null, true),
    'other_documents' => [
        new UploadedFile($tmpPdf, 'other.pdf', 'application/pdf', null, true)
    ],
];

$data = [
    'name' => 'Upload Test ' . time(),
    'location_province' => 'İstanbul',
    'location_district' => 'Kadıköy',
];

// Create Request instance simulating POST with files
$request = Request::create('/projects/' . $id, 'POST', $data, [], $files);
$request->setMethod('PUT');

$controller = new ProjectController();
try {
    $response = $controller->update($request, $id);
    if (is_object($response)) {
        if (method_exists($response, 'getStatusCode')) echo "Response status: " . $response->getStatusCode() . "\n";
        if (method_exists($response, 'getTargetUrl')) echo "Redirect to: " . $response->getTargetUrl() . "\n";
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

// cleanup
@unlink($tmpImage);
@unlink($tmpPdf);
@unlink($tmpTxt);

echo "Done.\n";
