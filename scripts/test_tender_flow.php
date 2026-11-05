<?php
// Simple test script for TenderPackage publish and submitBid logic
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// bootstrap the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

echo "Running TenderPackage flow test...\n";

// Create a package
$p = App\Models\TenderPackage::create([
    'project_id' => 1,
    'name' => 'Test Paket Script',
    'scope' => 'Demo script',
    'status' => 'draft',
    'responsible_id' => 1,
]);
$p->bidders = ['test@example.com'];
$p->save();

// Generate signed URL
$url = URL::temporarySignedRoute('tenders.package.access', now()->addDays(7), ['id'=>$p->id, 'bidder'=>base64_encode('test@example.com')]);
echo "Signed access URL: $url\n";

$controller = new App\Http\Controllers\TenderPackageController();

// 1) Submit matching bidder email
try {
    $query = ['bidder' => base64_encode('test@example.com')];
    $post = ['bidder_email' => 'test@example.com', 'price' => '12345'];
    $request = Request::create('/portfolio/tenders/package-prepare/'.$p->id.'/submit-bid', 'POST', $post, [], [], ['QUERY_STRING'=>http_build_query($query)], json_encode([]));
    // attach query param manually
    foreach ($query as $k=>$v) { $request->query->set($k,$v); }
    $response = $controller->submitBid($request, $p->id);
    echo "Matching bidder submission: OK\n";
} catch (Exception $e) {
    echo "Matching bidder submission: FAIL - ".get_class($e)." - " . $e->getMessage() . "\n";
}

// 2) Submit mismatching bidder email
try {
    $query = ['bidder' => base64_encode('test@example.com')];
    $post = ['bidder_email' => 'attacker@example.com', 'price' => '9999'];
    $request = Request::create('/portfolio/tenders/package-prepare/'.$p->id.'/submit-bid', 'POST', $post, [], [], ['QUERY_STRING'=>http_build_query($query)], json_encode([]));
    foreach ($query as $k=>$v) { $request->query->set($k,$v); }
    $response = $controller->submitBid($request, $p->id);
    echo "Mismatching bidder submission: ERROR - expected rejection but succeeded\n";
} catch (Symfony\Component\HttpKernel\Exception\HttpException $e) {
    echo "Mismatching bidder submission: Rejected as expected (".$e->getStatusCode().") - ". $e->getMessage() ."\n";
} catch (Exception $e) {
    echo "Mismatching bidder submission: FAIL - unexpected exception: ".get_class($e)." - " . $e->getMessage() . "\n";
}

// Show created bids
$bids = App\Models\TenderBid::where('tender_package_id', $p->id)->get();
echo "Bids created: " . $bids->count() . "\n";
foreach ($bids as $b) {
    echo " - ". $b->bidder_email . " | " . $b->price . " | " . ($b->documents ? implode(',', $b->documents) : '') . "\n";
}

echo "Test script finished.\n";
