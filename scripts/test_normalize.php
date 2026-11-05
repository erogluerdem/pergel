<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\TenderManagementController;
use App\Models\TenderBid;

$controller = new TenderManagementController();

// Call normalize directly
$req = Request::create('/portfolio/tenders/bid-management/1/normalize', 'POST');
$resp = $controller->normalize($req, 1);
echo "Normalize response:\n";
echo $resp->getContent() . "\n\n";

$data = json_decode($resp->getContent(), true);
$matches = $data['matches'] ?? [];

// Build simple mappings: accept first matched item per bidder/poz
$mappings = [];
foreach ($matches as $poz => $m) {
	foreach ($m['rows'] as $row) {
		$email = $row['bidder'];
		$bidId = TenderBid::where('tender_package_id', 1)->where('bidder_email', $email)->value('id');
		if (!$bidId) continue;
		$mappings[] = [
			'poz_no' => $poz,
			'bidder_email' => $email,
			'tender_bid_id' => $bidId,
			'tender_bid_item_id' => $row['item']['id'] ?? null,
			'score' => $row['score'] ?? 0,
			'accepted' => true,
		];
	}
}

// Call saveMappings directly
$saveReq = Request::create('/portfolio/tenders/bid-management/1/save-mappings', 'POST', ['mappings' => $mappings]);
$saveResp = $controller->saveMappings($saveReq, 1);
echo "SaveMappings response:\n";
echo $saveResp->getContent() . "\n";
