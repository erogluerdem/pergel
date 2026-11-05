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
}
