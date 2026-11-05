<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\TenderPackage;
use App\Models\Subcontractor;
use App\Models\Vendor;
use App\Models\CsbPozLibrary;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenderInvitation;
use App\Models\TenderInvitation as InvitationModel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\TenderBid;

class TenderPackageController extends Controller
{
    // Dashboard: list existing tender packages
    public function index()
    {
        $packages = TenderPackage::with(['project', 'responsible'])->orderBy('created_at','desc')->get();
        $stats = [
            'total_packages' => $packages->count(),
            'published_count' => $packages->where('status', 'published')->count(),
            'draft_count' => $packages->where('status', 'draft')->count(),
            'total_estimated' => $packages->sum('estimated_cost'),
        ];
        return view('portfolio.tenders.package-prepare', compact('packages', 'stats'));
    }

    // Show create wizard with real database records
    public function create(Request $request)
    {
        $projects = Project::orderBy('name','asc')->get();
        $subcontractors = Subcontractor::orderBy('company_name','asc')->get();
        $vendors = Vendor::orderBy('name','asc')->get();
        $csbPozs = CsbPozLibrary::orderBy('poz_number','asc')->take(100)->get();
        $managers = User::orderBy('name','asc')->get();
        $documents = Document::orderBy('name','asc')->take(50)->get();

        return view('portfolio.tenders.package-create', compact(
            'projects',
            'subcontractors',
            'vendors',
            'csbPozs',
            'managers',
            'documents'
        ));
    }

    // development preview helper
    public function preview(Request $request)
    {
        if (!config('app.debug')) abort(404);
        return $this->create($request);
    }

    // Store submitted package
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string',
            'scope' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'procurement_type' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric',
            'currency' => 'nullable|string',
            'contract_duration_days' => 'nullable|integer',
            'guarantee_percent' => 'nullable|numeric',
            'tender_reference' => 'nullable|string',
            'announcement_date' => 'nullable|date',
            'responsible_id' => 'nullable|exists:users,id',
            'documents' => 'nullable|array',
            'boq_csv' => 'nullable|file|mimes:csv,txt',
            'bidders_text' => 'nullable|string',
            'selected_subcontractors' => 'nullable|array',
        ]);

        $tp = new TenderPackage();
        $tp->project_id = $data['project_id'];
        $tp->name = $data['name'];
        $tp->scope = $data['scope'] ?? null;
        $tp->start_date = $data['start_date'] ?? null;
        $tp->end_date = $data['end_date'] ?? null;
        $tp->procurement_type = $data['procurement_type'] ?? null;
        $tp->estimated_cost = $data['estimated_cost'] ?? null;
        $tp->currency = $data['currency'] ?? null;
        $tp->contract_duration_days = $data['contract_duration_days'] ?? null;
        $tp->guarantee_percent = $data['guarantee_percent'] ?? null;
        $tp->tender_reference = $data['tender_reference'] ?? null;
        $tp->announcement_date = $data['announcement_date'] ?? null;
        $tp->status = $request->get('action') === 'publish' ? 'published' : 'draft';
        $tp->responsible_id = $data['responsible_id'] ?? (auth()->id() ?? null);

        $tp->documents = $request->get('documents', []);

        // Parse bidders_text & selected_subcontractors
        $bidders = [];
        if (!empty($data['bidders_text'])) {
            $lines = preg_split('/[\r\n,;]+/', $data['bidders_text']);
            foreach ($lines as $line) {
                $trim = trim($line);
                if (filter_var($trim, FILTER_VALIDATE_EMAIL)) $bidders[] = $trim;
            }
        }

        if (!empty($data['selected_subcontractors'])) {
            $subEmails = Subcontractor::whereIn('id', $data['selected_subcontractors'])->whereNotNull('email')->pluck('email')->toArray();
            $bidders = array_unique(array_merge($bidders, $subEmails));
        }

        $tp->bidders = array_values($bidders);

        // BOQ CSV parsing (if uploaded)
        if ($request->hasFile('boq_csv')) {
            $file = $request->file('boq_csv');
            $path = $file->getRealPath();
            $boqData = [];
            if (($handle = fopen($path, 'r')) !== FALSE) {
                $header = fgetcsv($handle, 1000, ',');
                while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (count($row) >= 4) {
                        $boqData[] = [
                            'no' => $row[0],
                            'description' => $row[1],
                            'unit' => $row[2],
                            'quantity' => floatval($row[3]),
                            'estimated_unit_price' => isset($row[4]) ? floatval($row[4]) : 0,
                        ];
                    }
                }
                fclose($handle);
            }
            $tp->boq = $boqData;
        } elseif ($request->has('selected_pozs')) {
            // Pick selected CSB Pozs from SQL Library
            $selectedPozIds = $request->get('selected_pozs', []);
            $pozModels = CsbPozLibrary::whereIn('id', $selectedPozIds)->get();
            $boqData = [];
            foreach ($pozModels as $poz) {
                $boqData[] = [
                    'no' => $poz->poz_number,
                    'description' => $poz->title,
                    'unit' => $poz->unit,
                    'quantity' => 100, // default placeholder
                    'estimated_unit_price' => $poz->unit_price ?? 0,
                ];
            }
            $tp->boq = $boqData;
        }

        $tp->save();

        return redirect()->route('tenders.package.prepare')->with('success', "{$tp->name} ihale paketi başarıyla oluşturuldu.");
    }

    public function show($id)
    {
        $p = TenderPackage::with(['project', 'responsible', 'bids'])->findOrFail($id);
        return view('portfolio.tenders.package-show', compact('p'));
    }

    public function edit($id)
    {
        $p = TenderPackage::findOrFail($id);
        $projects = Project::orderBy('name', 'asc')->get();
        return view('portfolio.tenders.package-edit', compact('p', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'scope' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'procurement_type' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric',
            'currency' => 'nullable|string',
            'contract_duration_days' => 'nullable|integer',
            'guarantee_percent' => 'nullable|numeric',
            'tender_reference' => 'nullable|string',
            'announcement_date' => 'nullable|date',
            'responsible_id' => 'nullable|exists:users,id',
            'bidders_text' => 'nullable|string',
        ]);

        $p->project_id = $data['project_id'];
        $p->name = $data['name'];
        $p->scope = $data['scope'] ?? $p->scope;
        if (array_key_exists('start_date', $data)) $p->start_date = $data['start_date'];
        if (array_key_exists('end_date', $data)) $p->end_date = $data['end_date'];
        if (array_key_exists('procurement_type', $data)) $p->procurement_type = $data['procurement_type'];
        if (array_key_exists('estimated_cost', $data)) $p->estimated_cost = $data['estimated_cost'];
        if (array_key_exists('currency', $data)) $p->currency = $data['currency'];
        if (array_key_exists('contract_duration_days', $data)) $p->contract_duration_days = $data['contract_duration_days'];
        if (array_key_exists('guarantee_percent', $data)) $p->guarantee_percent = $data['guarantee_percent'];
        if (array_key_exists('tender_reference', $data)) $p->tender_reference = $data['tender_reference'];
        if (array_key_exists('announcement_date', $data)) $p->announcement_date = $data['announcement_date'];
        if (array_key_exists('responsible_id', $data)) $p->responsible_id = $data['responsible_id'];

        if ($request->has('bidders_text')) {
            $bidders = [];
            if (!empty($data['bidders_text'])) {
                $lines = preg_split('/[\r\n,;]+/', $data['bidders_text']);
                foreach ($lines as $line) {
                    $trim = trim($line);
                    if (filter_var($trim, FILTER_VALIDATE_EMAIL)) {
                        $bidders[] = $trim;
                    }
                }
            }
            $p->bidders = array_values(array_unique($bidders));
        }

        $p->save();

        return redirect()->route('tenders.package.show', $p->id)->with('success', 'İhale paketi başarıyla güncellendi.');
    }

    public function publish(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $p->status = 'published';
        $p->published_at = now();
        $p->save();

        $bidders = is_array($p->bidders) ? $p->bidders : [];
        foreach ($bidders as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
            $token = Str::random(40);
            try {
                InvitationModel::updateOrCreate(
                    ['tender_package_id' => $p->id, 'bidder_email' => $email],
                    ['token' => $token, 'expires_at' => now()->addDays(14)]
                );
                if (class_exists(TenderInvitation::class)) {
                    $signedUrl = URL::temporarySignedRoute('tenders.package.access', now()->addDays(14), [
                        'id' => $p->id,
                        'bidder' => base64_encode($email),
                    ]);
                    Mail::to($email)->send(new TenderInvitation($p, $signedUrl));
                }
            } catch (\Throwable $e) {
                // Ignore mail/invitation errors in local/test environments
            }
        }

        return redirect()->route('tenders.package.show', $p->id)->with('success', 'İhale paketi yayınlandı ve davetiyeler gönderildi.');
    }

    public function bids($id)
    {
        $p = TenderPackage::with('project')->findOrFail($id);
        $bids = TenderBid::where('tender_package_id', $p->id)->orderBy('price', 'asc')->get();
        return view('portfolio.tenders.package-bids', compact('p', 'bids'));
    }

    public function exportBidsCsv($id): StreamedResponse
    {
        $p = TenderPackage::findOrFail($id);
        $bids = TenderBid::where('tender_package_id', $p->id)->orderBy('price', 'asc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="tender_bids_' . $p->id . '.csv"',
        ];

        return response()->stream(function () use ($bids) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'E-Posta', 'Fiyat', 'Notlar', 'Tarih']);
            foreach ($bids as $b) {
                fputcsv($out, [
                    $b->id,
                    $b->bidder_email,
                    $b->price,
                    $b->notes,
                    $b->created_at,
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function downloadBidFile(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $file = $request->query('file');
        if (!$file || str_contains($file, '..')) {
            abort(404, 'Geçersiz dosya yolu.');
        }

        if (Storage::disk('public')->exists($file)) {
            return Storage::disk('public')->download($file);
        }
        if (Storage::exists($file)) {
            return Storage::download($file);
        }

        abort(404, 'Dosya bulunamadı.');
    }

    public function compareForm($id)
    {
        $p = TenderPackage::findOrFail($id);
        return view('portfolio.tenders.package-compare', compact('p'));
    }

    public function compareProcess(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $rows = [];

        $bids = TenderBid::where('tender_package_id', $p->id)->get();
        foreach ($bids as $b) {
            $rows[] = [
                'email' => $b->bidder_email,
                'price' => floatval($b->price ?? 0),
                'notes' => $b->notes ?? '',
            ];
        }

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $first = true;
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($first && isset($data[0]) && !filter_var(trim($data[0]), FILTER_VALIDATE_EMAIL)) {
                        $first = false;
                        continue;
                    }
                    $first = false;
                    if (count($data) >= 2) {
                        $rows[] = [
                            'email' => trim($data[0]),
                            'price' => floatval($data[1]),
                            'notes' => $data[2] ?? '',
                        ];
                    }
                }
                fclose($handle);
            }
        }

        usort($rows, fn($a, $b) => $a['price'] <=> $b['price']);

        return view('portfolio.tenders.package-compare-result', compact('p', 'rows'));
    }

    public function seedDemoBid(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $basePrice = floatval($p->estimated_cost ?: 250000);
        $demoPrice = round($basePrice * (0.9 + (mt_rand(0, 20) / 100)), 2);

        $bid = TenderBid::create([
            'tender_package_id' => $p->id,
            'bidder_email' => 'demo.tedarikci.' . mt_rand(10, 99) . '@ornek.com',
            'price' => $demoPrice,
            'notes' => 'Otomatik oluşturulan demo teklif kaydı.',
            'documents' => [],
        ]);

        if (is_array($p->boq)) {
            foreach ($p->boq as $item) {
                $qty = floatval($item['quantity'] ?? 1);
                $unitPrice = floatval($item['estimated_unit_price'] ?? 100) * (0.9 + (mt_rand(0, 20) / 100));
                \App\Models\TenderBidItem::create([
                    'tender_bid_id' => $bid->id,
                    'poz_no' => $item['no'] ?? null,
                    'description' => $item['description'] ?? '',
                    'unit' => $item['unit'] ?? 'Adet',
                    'quantity' => $qty,
                    'unit_price' => round($unitPrice, 2),
                    'total_price' => round($qty * $unitPrice, 2),
                ]);
            }
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'bid' => $bid]);
        }

        return redirect()->route('tenders.package.bids', $p->id)->with('success', 'Demo teklif eklendi.');
    }

    public function access(Request $request, $id)
    {
        $p = TenderPackage::with('project')->findOrFail($id);
        return view('portfolio.tenders.package-show-public', compact('p'));
    }

    public function submitBid(Request $request, $id)
    {
        $p = TenderPackage::findOrFail($id);
        $data = $request->validate([
            'bidder_email' => 'required|email|max:255',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:5000',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,dwg,zip|max:20480',
        ]);

        $uploadedDocs = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $uploadedDocs[] = $file->store('tender-bids/' . $p->id, 'public');
            }
        }

        TenderBid::create([
            'tender_package_id' => $p->id,
            'bidder_email' => $data['bidder_email'],
            'price' => $data['price'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'documents' => $uploadedDocs,
        ]);

        InvitationModel::where('tender_package_id', $p->id)
            ->where('bidder_email', $data['bidder_email'])
            ->update(['used_at' => now()]);

        return redirect()->back()->with('success', 'Teklifiniz başarıyla kaydedildi.');
    }
}
