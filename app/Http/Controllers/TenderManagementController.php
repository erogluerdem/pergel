<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TenderPackage;
use App\Models\TenderBid;
use App\Models\TenderMapping;
use App\Models\TenderBidItem;

class TenderManagementController extends Controller
{
    // Dashboard: list packages with summary
    public function index(Request $request)
    {
        $q = TenderPackage::with(['bids.items','responsible','project'])
            ->withCount(['bids as offers_count' => function($q){ }])
            ->withCount('bidders');

        if ($request->filled('search')) {
            $q->where('name','like','%'.$request->get('search').'%');
        }
        if ($request->filled('status')) {
            $q->where('status',$request->get('status'));
        }
        $packages = $q->orderBy('created_at','desc')->get();

        foreach ($packages as $pkg) {
            $min = null; $minBidder = null;
            if ($pkg->relationLoaded('bids')) {
                foreach ($pkg->bids as $b) {
                    if ($b->relationLoaded('items')) {
                        foreach ($b->items as $it) {
                            $price = null;
                            if (isset($it->unit_price)) $price = $it->unit_price;
                            elseif (isset($it->price)) $price = $it->price;
                            if ($price !== null && is_numeric($price)) {
                                if ($min === null || $price < $min) {
                                    $min = $price;
                                    $minBidder = $b->bidder_email ?? ($b->bidder_name ?? null);
                                }
                            }
                        }
                    }
                }
            }
            $pkg->offers_min = $min;
            $pkg->offers_min_bidder = $minBidder;
        }

        $stats = [
            'total_packages' => $packages->count(),
            'published_count' => $packages->where('status', 'published')->count(),
            'draft_count' => $packages->where('status', 'draft')->count(),
            'closed_count' => $packages->where('status', 'closed')->count(),
            'total_offers' => $packages->sum('offers_count'),
        ];

        return view('portfolio.tenders.bid-management', compact('packages', 'stats'));
    }

    // Leveling screen for a package
    public function leveling(Request $request, $id)
    {
        $p = TenderPackage::with('project')->findOrFail($id);
        $bids = TenderBid::where('tender_package_id',$p->id)->with('items')->get();

        if ($request->hasFile('offers_csv')) {
            $contents = file_get_contents($request->file('offers_csv')->getRealPath());
            $lines = array_map('str_getcsv', explode("\n", $contents));
            foreach ($lines as $ln) {
                if (count($ln) < 3) continue;
                $bb = $bids->firstWhere('bidder_email',$ln[0]);
                if ($bb) {
                    $bb->items->push((object)['poz_no'=>$ln[1],'unit_price'=>floatval($ln[2])]);
                }
            }
        }

        $boq = $p->boq ?? [];
        return view('portfolio.tenders.bid-leveling', compact('p','bids','boq'));
    }

    // Normalize: attempt to match BOQ rows to each bid's items using simple heuristics
    public function normalize(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $bids = TenderBid::where('tender_package_id',$p->id)->with('items')->get();
        $boq = $p->boq ?? [];

        $results = [];
        foreach ($boq as $row) {
            $poz = trim((string)($row['no'] ?? ''));
            $desc = strtolower(trim((string)($row['description'] ?? '')));
            $best = [];
            foreach ($bids as $b) {
                $item = $b->items->firstWhere('poz_no', $poz);
                if (!$item) {
                    $bestItem = null; $bestScore = 0;
                    foreach ($b->items as $it) {
                        $itdesc = strtolower((string)$it->description);
                        similar_text($desc, $itdesc, $perc);
                        if ($perc > $bestScore) { $bestScore = $perc; $bestItem = $it; }
                    }
                    $item = $bestItem;
                }
                $results[$poz]['rows'][] = ['bidder'=>$b->bidder_email,'item'=>$item,'score'=>$item?($item->poz_no == $poz?100:$bestScore):0];
            }
        }

        return response()->json(['matches'=>$results]);
    }

    // Save mappings posted from the UI (ajax)
    public function saveMappings(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $data = $request->get('mappings', []);
        $saved = [];
        foreach ($data as $m) {
            if (empty($m['tender_bid_id']) || empty($m['poz_no'])) continue;
            $map = TenderMapping::updateOrCreate([
                'tender_package_id' => $p->id,
                'tender_bid_id' => $m['tender_bid_id'],
                'poz_no' => $m['poz_no'],
            ], [
                'tender_bid_item_id' => $m['tender_bid_item_id'] ?? null,
                'bidder_email' => $m['bidder_email'] ?? null,
                'score' => isset($m['score']) ? floatval($m['score']) : null,
                'accepted' => isset($m['accepted']) ? boolval($m['accepted']) : true,
            ]);
            $saved[] = $map->id;
        }
        return response()->json(['saved'=>$saved]);
    }
}
