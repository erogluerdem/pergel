<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Subcontractor;
use App\Models\SubcontractorContract;
use App\Models\ProgressClaim;
use App\Models\ProgressClaimItem;
use App\Models\ProgressClaimApproval;
use App\Models\PaymentInstruction;
use App\Models\Payment;
use App\Models\CapitalTransaction;
use App\Models\CashBankTransaction;

class SubcontractorController extends Controller
{
    public function companies()
    {
        // Dashboard verileri
        $totalApproved = Subcontractor::where('status', 'approved')->count();
        $activeCompanies = Subcontractor::where('active_projects', '>', 0)->count();
        $lowSafetyScore = Subcontractor::where('safety_score', '<', 85)->count();
        $riskyCompanies = Subcontractor::where('status', 'conditional')->count();

        // Uzmanlık alanlarına göre dağılım (basit örnek)
        $expertiseDistribution = Subcontractor::selectRaw('expertise_area, COUNT(*) as count')
            ->groupBy('expertise_area')
            ->get()
            ->pluck('count', 'expertise_area');

        // Listeleme: varsayılan olarak son eklenen taşeronlar (sayfalı)
        $companies = Subcontractor::orderBy('created_at', 'desc')->paginate(20);

        return view('subcontractor.companies', compact(
            'totalApproved',
            'activeCompanies',
            'lowSafetyScore',
            'riskyCompanies',
            'expertiseDistribution',
            'companies'
        ));
    }

    /**
     * Export companies list as CSV
     */
    public function exportCompaniesCsv()
    {
        $companies = Subcontractor::orderBy('company_name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subcontractors_' . date('Ymd_His') . '.csv"',
        ];

        $columns = ['ID','Firma','Uzmanlık','Telefon','E-posta','Adres','Durum','İSG Skoru','Oluşturma Tarihi'];

        $callback = function() use ($companies, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            foreach ($companies as $c) {
                fputcsv($handle, [
                    $c->id,
                    $c->company_name,
                    $c->expertise_area,
                    $c->phone ?? '',
                    $c->email ?? '',
                    $c->address ?? '',
                    $c->status ?? '',
                    isset($c->safety_score) ? number_format($c->safety_score,2) : '',
                    optional($c->created_at)->format('Y-m-d')
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $query = Subcontractor::query();

        // Filtreleme
        if ($request->filled('search')) {
            $query->where('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('expertise_area', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('performance_min')) {
            $query->where('performance_score', '>=', $request->performance_min);
        }

        $subcontractors = $query->paginate(20);

        return view('subcontractor.index', compact('subcontractors'));
    }

    public function show($id)
    {
        $subcontractor = Subcontractor::findOrFail($id);

        // Örnek performans geçmişi (şimdilik boş, sonra eklenebilir)
        $performanceHistory = [];

        // Son sözleşmeler (hızlı önizleme)
        $recentContracts = $subcontractor->contracts()->latest()->take(6)->get();

        // Belgeler alanı (modelde 'documents' cast edilmiş olabilir)
        $documents = is_array($subcontractor->documents) ? $subcontractor->documents : [];

        return view('subcontractor.show', compact('subcontractor', 'performanceHistory', 'recentContracts', 'documents'));
    }

    public function quoteStore(Request $request, $id)
    {
        $subcontractor = Subcontractor::findOrFail($id);

        $validated = $request->validate([
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'nullable|email|max:255',
            'requester_phone' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:2000',
        ]);

        $quote = \App\Models\SubcontractorQuote::create(array_merge($validated, ['subcontractor_id' => $subcontractor->id]));

        // TODO: send notification/email to admins (optional)

        return redirect()->route('subcontractor.show', $subcontractor->id)->with('success', 'Teklif talebiniz alındı. En kısa sürede dönüş yapılacaktır.');
    }

    public function uploadDocument(Request $request, $id)
    {
        $subcontractor = Subcontractor::findOrFail($id);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpeg,jpg,png,doc,docx|max:10240',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $path = $request->file('file')->store("public/subcontractors/{$subcontractor->id}/docs");

        $docs = is_array($subcontractor->documents) ? $subcontractor->documents : [];
        $docs[] = [
            'title' => $validated['title'] ?? $request->file('file')->getClientOriginalName(),
            'description' => $validated['description'] ?? null,
            'url' => str_replace('public/', 'storage/', $path),
            'uploaded_at' => now()->toDateTimeString(),
        ];

        $subcontractor->documents = $docs;
        $subcontractor->save();

        return redirect()->route('subcontractor.show', $subcontractor->id)->with('success', 'Belge başarıyla yüklendi.');
    }

    // Diğer metodlar...
    public function contracts()
    {
        // Eğer bir taşeron seçildiyse ona göre filtrele, yoksa genel dashboard
        $subcontractorId = request()->query('subcontractor_id');

        $activeContracts = 0;
        $totalCommitment = 0;
        $totalPaid = 0;
        $pendingChangeOrders = 0; // Bu daha sonra implement edilecek
        $contracts = collect();
        $payments = collect();
        $cashBank = collect();
        $timeline = collect();

        // Listeleme için tüm taşeronlar (select box vs.)
        $subcontractors = Subcontractor::select('id', 'company_name')->orderBy('company_name')->get();

        if ($subcontractorId) {
            $sub = Subcontractor::find($subcontractorId);
            if ($sub) {
                // Sözleşmeler
                $contracts = SubcontractorContract::with('project')
                    ->where('subcontractor_id', $sub->id)
                    ->orderBy('start_date', 'desc')
                    ->get();

                $activeContracts = $contracts->where('status', 'active')->count();
                $totalCommitment = $contracts->sum('total_amount');
                $totalPaid = $contracts->sum('paid_amount');

                // Tarih filtreleri (opsiyonel)
                $dateFrom = request()->query('date_from');
                $dateTo = request()->query('date_to');

                // Ödeme hareketleri: Payment (hakediş/fatura tablosu ile ilişki)
                $paymentQuery = Payment::with(['items'])
                    ->whereIn('subcontractor_contract_id', $contracts->pluck('id')->toArray())
                    ->orderBy('date', 'desc');

                if ($dateFrom) $paymentQuery->whereDate('date', '>=', $dateFrom);
                if ($dateTo) $paymentQuery->whereDate('date', '<=', $dateTo);

                // Keep a paginated view for the payments table
                $payments = (clone $paymentQuery)->paginate(15, ['*'], 'payments_page');
                // But also fetch a reasonable set for timeline merging (limit to 200 to avoid memory issues)
                $paymentsForTimeline = (clone $paymentQuery)->limit(200)->get();

                // Daha sağlam partner eşleştirmesi (A): partner tablosunda subcontractor_id veya isim ile eşleştir
                $partnerIds = collect();
                if (Schema::hasTable('partners')) {
                    // Eğer partner tablosunda subcontractor_id kolonu varsa direkt eşleştir
                    if (Schema::hasColumn('partners', 'subcontractor_id')) {
                        $partnerIds = DB::table('partners')->where('subcontractor_id', $sub->id)->pluck('id');
                    }
                    // Eğer isim eşleşmesi gerekiyor ise (fallback) - alias sütunu olmayabilir
                    if ($partnerIds->isEmpty()) {
                        $pquery = DB::table('partners')->where('name', $sub->company_name);
                        if (Schema::hasColumn('partners', 'alias')) {
                            $pquery->orWhere('alias', $sub->company_name);
                        }
                        $partnerIds = $pquery->pluck('id');
                    }
                }

                // CapitalTransaction ve ona bağlı CashBankTransaction (ve PaymentInstruction) sorguları
                $capitalQuery = CapitalTransaction::query();
                if ($partnerIds->isNotEmpty()) {
                    $capitalQuery->whereIn('partner_id', $partnerIds->toArray());
                } else {
                    // fallback: partner name match in description or source
                    $capitalQuery->where('description', 'like', '%' . $sub->company_name . '%');
                }

                if ($dateFrom) $capitalQuery->whereDate('transaction_date', '>=', $dateFrom);
                if ($dateTo) $capitalQuery->whereDate('transaction_date', '<=', $dateTo);

                // Paginate for the capital table, but fetch a larger set for timeline merge
                $capitalTransactions = (clone $capitalQuery)->orderBy('transaction_date', 'desc')->paginate(15, ['*'], 'capital_page');
                $capitalForTimeline = (clone $capitalQuery)->orderBy('transaction_date', 'desc')->limit(200)->get();

                $cashBank = CashBankTransaction::with('account')
                    ->whereIn('capital_transaction_id', $capitalTransactions->pluck('id')->toArray())
                    ->orderBy('transaction_date', 'desc')
                    ->paginate(15, ['*'], 'cashbank_page');

                $piQuery = PaymentInstruction::query();
                if ($partnerIds->isNotEmpty()) {
                    $piQuery->whereIn('reference_id', function($q) use ($partnerIds){
                        $q->select('id')->from('partners')->whereIn('id', $partnerIds->toArray());
                    });
                } else {
                    $piQuery->where('payee', 'like', '%' . $sub->company_name . '%');
                }
                if ($dateFrom) $piQuery->whereDate('payment_date', '>=', $dateFrom);
                if ($dateTo) $piQuery->whereDate('payment_date', '<=', $dateTo);

                $paymentInstructions = (clone $piQuery)->orderBy('payment_date', 'desc')->paginate(15, ['*'], 'pi_page');
                $pisForTimeline = (clone $piQuery)->orderBy('payment_date', 'desc')->limit(200)->get();

                // Birleşik timeline (DB-level UNION): payments, capital_transactions, payment_instructions
                // Build individual queries selecting a normalized set of columns: date, type, amount, description, ref_id
                $bindings = [];

                // Payments select
                $paymentsSql = DB::table('payments')
                    ->selectRaw("COALESCE(date, created_at) as dt, 'payment' as type, COALESCE(approved, requested, 0) as amount, CONCAT('Ödeme ', IFNULL(no, ''), ' - ', IFNULL(contract, '')) as description, id as ref_id")
                    ->whereIn('subcontractor_contract_id', $contracts->pluck('id')->toArray());
                if (!empty($dateFrom)) $paymentsSql->whereDate('date', '>=', $dateFrom);
                if (!empty($dateTo)) $paymentsSql->whereDate('date', '<=', $dateTo);

                $paymentsQuery = $paymentsSql->toSql();
                $paymentsBindings = $paymentsSql->getBindings();

                // Capital transactions select
                $capitalSql = DB::table('capital_transactions')
                    ->selectRaw("COALESCE(transaction_date, created_at) as dt, 'capital' as type, COALESCE(amount,0) as amount, description as description, id as ref_id");

                if ($partnerIds->isNotEmpty()) {
                    $capitalSql->whereIn('partner_id', $partnerIds->toArray());
                } else {
                    $capitalSql->where('description', 'like', '%' . $sub->company_name . '%');
                }
                if (!empty($dateFrom)) $capitalSql->whereDate('transaction_date', '>=', $dateFrom);
                if (!empty($dateTo)) $capitalSql->whereDate('transaction_date', '<=', $dateTo);

                $capitalQuery = $capitalSql->toSql();
                $capitalBindings = $capitalSql->getBindings();

                // Payment instructions select
                $piSql = DB::table('payment_instructions')
                    ->selectRaw("COALESCE(payment_date, created_at) as dt, 'instruction' as type, COALESCE(amount,0) as amount, CONCAT('Talimat - ', IFNULL(payee, '')) as description, id as ref_id");
                if ($partnerIds->isNotEmpty()) {
                    // if reference_id maps to partners, join/where exists
                    $piSql->whereIn('reference_id', function($q) use ($partnerIds){
                        $q->select('id')->from('partners')->whereIn('id', $partnerIds->toArray());
                    });
                } else {
                    $piSql->where('payee', 'like', '%' . $sub->company_name . '%');
                }
                if (!empty($dateFrom)) $piSql->whereDate('payment_date', '>=', $dateFrom);
                if (!empty($dateTo)) $piSql->whereDate('payment_date', '<=', $dateTo);

                $piQuery = $piSql->toSql();
                $piBindings = $piSql->getBindings();

                // Create raw UNION SQL
                $unionSql = "({$paymentsQuery}) UNION ({$capitalQuery}) UNION ({$piQuery}) ORDER BY dt DESC";
                $unionBindings = array_merge($paymentsBindings, $capitalBindings, $piBindings);

                // Count total
                $countSql = "SELECT COUNT(*) as total FROM ({$unionSql}) as unioned";
                $totalRow = DB::selectOne($countSql, $unionBindings);
                $total = $totalRow->total ?? 0;

                // Pagination parameters
                $perPage = 15;
                $page = max(1, (int) request()->query('timeline_page', 1));
                $offset = ($page - 1) * $perPage;

                $pagedSql = $unionSql . " LIMIT {$perPage} OFFSET {$offset}";
                $rows = DB::select($pagedSql, $unionBindings);

                // Normalize rows into a collection-like paginator
                $items = collect($rows)->map(function($r){
                    return [
                        'date' => $r->dt,
                        'type' => $r->type,
                        'amount' => $r->amount,
                        'description' => $r->description,
                        'ref_id' => $r->ref_id,
                    ];
                });

                $timeline = new LengthAwarePaginator($items, $total, $perPage, $page, [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]);
            }
        } else {
            // Genel dashboard verileri
            $activeContracts = SubcontractorContract::where('status', 'active')->count();
            $totalCommitment = SubcontractorContract::where('status', 'active')->sum('total_amount');
            $totalPaid = SubcontractorContract::sum('paid_amount');
            $pendingChangeOrders = 0;
        }

        return view('subcontractor.contracts', compact(
            'activeContracts',
            'totalCommitment',
            'totalPaid',
            'pendingChangeOrders',
            'subcontractors',
            'subcontractorId',
            'contracts',
            'payments',
            'cashBank',
            'timeline'
        ));
    }

    public function contractIndex(Request $request)
    {
        $query = SubcontractorContract::with(['subcontractor', 'project']);

        // Filtreleme
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->search . '%')
                  ->orWhere('contract_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('subcontractor', function($sq) use ($request) {
                      $sq->where('company_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        $contracts = $query->paginate(20);

        return view('subcontractor.contract-index', compact('contracts'));
    }

    public function contractShow($id)
    {
        $contract = SubcontractorContract::with(['subcontractor', 'project'])->findOrFail($id);

        // Hakediş geçmişi
        $paymentHistory = $contract->payments()->with('items')->latest()->get();

        // Change orders
        $changeOrders = $contract->changeOrders()->latest()->get();

        // Sigorta uyarıları
        $expiringInsurances = $contract->expiringInsurances;

        return view('subcontractor.contract-show', compact('contract', 'paymentHistory', 'changeOrders', 'expiringInsurances'));
    }

    public function newProgress()
    {
        // Kullanıcının erişebileceği projeleri al (şimdilik tüm projeler, auth eklenebilir)
        $projects = \App\Models\Project::select('id', 'name')->get();

        // Kullanıcının yetkili olduğu taşeron sözleşmeleri (şimdilik tüm sözleşmeler)
        $contracts = \App\Models\SubcontractorContract::with('subcontractor', 'project')
            ->select('id', 'subcontractor_id', 'project_id', 'contract_number')
            ->get();

        return view('subcontractor.progress.new', compact('projects', 'contracts'));
    }

    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'subcontractor_contract_id' => 'required|exists:subcontractor_contracts,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'claim_number' => 'required|string',
            'status' => 'required|in:draft,submitted',
            'notes' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:jpeg,jpg,png,pdf,doc,docx|max:10240',
        ]);

        // ProgressClaim oluştur
        $claim = ProgressClaim::create([
            'subcontractor_contract_id' => $validated['subcontractor_contract_id'],
            'project_id' => $validated['project_id'],
            'claim_number' => $validated['claim_number'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Items ekle
        $submittedItems = $request->input('items', []);
        $createdItems = [];

        if (!empty($submittedItems) && is_array($submittedItems)) {
            foreach ($submittedItems as $idx => $rawItem) {
                $currQty = (float) ($rawItem['current_quantity'] ?? $rawItem['quantity'] ?? 0);
                $prevQty = (float) ($rawItem['previous_quantity'] ?? 0);
                $unitPrice = (float) ($rawItem['unit_price'] ?? 0);
                $currAmount = $currQty * $unitPrice;
                $itemData = [
                    'progress_claim_id' => $claim->id,
                    'item_code' => $rawItem['item_code'] ?? ('160.01.' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT)),
                    'description' => $rawItem['description'] ?? $rawItem['work_item'] ?? ('İş Kalemi #' . ($idx + 1)),
                    'unit' => $rawItem['unit'] ?? 'adet',
                    'contract_quantity' => (float) ($rawItem['contract_quantity'] ?? $currQty),
                    'previous_quantity' => $prevQty,
                    'current_quantity' => $currQty,
                    'cumulative_quantity' => $prevQty + $currQty,
                    'unit_price' => $unitPrice,
                    'current_amount' => $currAmount,
                ];
                ProgressClaimItem::create($itemData);
                $createdItems[] = $itemData;
            }
        } else {
            $currentQuantities = $request->input('current_quantity', []);
            $mockItems = [
                [
                    'progress_claim_id' => $claim->id,
                    'item_code' => '160.01.001',
                    'description' => 'C30 Beton',
                    'unit' => 'm³',
                    'contract_quantity' => 1200,
                    'previous_quantity' => 450,
                    'current_quantity' => $currentQuantities[0] ?? 0,
                    'cumulative_quantity' => 450 + ($currentQuantities[0] ?? 0),
                    'unit_price' => 2000.00,
                    'current_amount' => ($currentQuantities[0] ?? 0) * 2000.00,
                ],
                [
                    'progress_claim_id' => $claim->id,
                    'item_code' => '160.02.005',
                    'description' => 'Nervürlü Demir',
                    'unit' => 'ton',
                    'contract_quantity' => 85,
                    'previous_quantity' => 30,
                    'current_quantity' => $currentQuantities[1] ?? 0,
                    'cumulative_quantity' => 30 + ($currentQuantities[1] ?? 0),
                    'unit_price' => 25000.00,
                    'current_amount' => ($currentQuantities[1] ?? 0) * 25000.00,
                ],
            ];

            foreach ($mockItems as $item) {
                ProgressClaimItem::create($item);
                $createdItems[] = $item;
            }
        }

        // Finansal hesaplamalar
        $totalAmount = array_sum(array_column($createdItems, 'current_amount'));
        $withholding = $totalAmount * 0.10; // %10
        $advanceDeduction = $claim->advance_deduction ?? 0;
        $subtotal = $totalAmount - $withholding - $advanceDeduction;
        $vat = $totalAmount * 0.20; // %20
        $netAmount = $totalAmount + $vat - $withholding - $advanceDeduction;

        $claim->update([
            'total_amount' => $totalAmount,
            'withholding_amount' => $withholding,
            'advance_deduction' => $advanceDeduction,
            'vat_amount' => $vat,
            'net_amount' => $netAmount,
        ]);

        // Dosyaları yükle
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('progress_claims/' . $claim->id, 'public');
                // Document model ile ilişkilendir (varsa)
            }
        }

        if ($validated['status'] === 'submitted') {
            $claim->update(['submitted_at' => now()]);
            // Bildirim gönder (opsiyonel)
        }

        return redirect()->route('subcontractor.progress.new')->with('success',
            $validated['status'] === 'submitted'
                ? 'Hakediş talebi başarıyla gönderildi ve onay sürecine alındı.'
                : 'Hakediş taslağı başarıyla kaydedildi.'
        );
    }

    // Admin: show create form (already exists)
    public function edit($id)
    {
        $subcontractor = Subcontractor::findOrFail($id);
        return view('subcontractor.edit', compact('subcontractor'));
    }

    public function update(Request $request, $id)
    {
        $sub = Subcontractor::findOrFail($id);
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'expertise_area' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'status' => 'nullable|string|max:50',
            'performance_score' => 'nullable|numeric|min:0|max:100',
            'safety_score' => 'nullable|numeric|min:0|max:100',
            'logo' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
        ]);
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store("public/subcontractors/{$sub->id}/logo");
            $validated['logo'] = str_replace('public/', 'storage/', $path);
        }

        $sub->update($validated);
        return redirect()->route('subcontractor.companies')->with('success', 'Taşeron güncellendi.');
    }

    public function destroy($id)
    {
        $sub = Subcontractor::findOrFail($id);
        $sub->delete();
        return redirect()->route('subcontractor.companies')->with('success', 'Taşeron silindi.');
    }

    // Admin: list soft-deleted subcontractors
    public function trashedIndex()
    {
        $trashed = Subcontractor::onlyTrashed()->paginate(20);
        return view('subcontractor.trashed', compact('trashed'));
    }

    public function restore($id)
    {
        $sub = Subcontractor::onlyTrashed()->where('id', $id)->firstOrFail();
        $sub->restore();
        return redirect()->route('subcontractor.trashed')->with('success', 'Taşeron geri alındı.');
    }

    public function forceDelete($id)
    {
        $sub = Subcontractor::onlyTrashed()->where('id', $id)->firstOrFail();
        $sub->forceDelete();
        return redirect()->route('subcontractor.trashed')->with('success', 'Taşeron kalıcı olarak silindi.');
    }

    public function progressApproval(Request $request)
    {
        // Kullanıcının onayını bekleyen hakedişleri al
        // Şimdilik tüm submitted hakedişleri alalım (auth eklenebilir)
        $query = ProgressClaim::with(['project', 'subcontractorContract.subcontractor'])
            ->where('status', 'submitted');

        // Filtreleme
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('subcontractor_id')) {
            $query->whereHas('subcontractorContract', function($q) use ($request) {
                $q->where('subcontractor_id', $request->subcontractor_id);
            });
        }

        $pendingClaims = $query->latest()->paginate(10);

        // Filtreleme için veriler
        $projects = \App\Models\Project::select('id', 'name')->get();
        $subcontractors = \App\Models\Subcontractor::select('id', 'company_name')->get();

        return view('subcontractor.progress.approval', compact('pendingClaims', 'projects', 'subcontractors'));
    }

    public function progressApprovalDetail($id)
    {
        $claim = ProgressClaim::with(['project', 'subcontractorContract.subcontractor', 'items', 'approvals.user'])
            ->findOrFail($id);

        return view('subcontractor.progress.approval_detail', compact('claim'));
    }

    public function processApproval(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:approved,rejected,revision_requested',
            'comments' => 'required_if:action,rejected,revision_requested|string',
            'approved_quantities' => 'nullable|array',
            'approved_quantities.*' => 'numeric|min:0',
        ]);

        $claim = ProgressClaim::findOrFail($id);

        // Approval kaydı oluştur
        $approval = ProgressClaimApproval::create([
            'progress_claim_id' => $claim->id,
            'user_id' => auth()->id() ?? 1, // Şimdilik mock
            'action' => $validated['action'],
            'comments' => $validated['comments'] ?? null,
            'approved_quantities' => $validated['approved_quantities'] ?? null,
            'approved_at' => now(),
        ]);

        // Claim status'ını güncelle
        if ($validated['action'] === 'approved') {
            $claim->update(['status' => 'approved', 'approved_at' => now()]);
        } elseif ($validated['action'] === 'rejected') {
            $claim->update(['status' => 'rejected']);
        } elseif ($validated['action'] === 'revision_requested') {
            $claim->update(['status' => 'revision_requested']);
        }

        // Eğer onaylandıysa item miktarlarını güncelle
        if ($validated['action'] === 'approved' && $validated['approved_quantities']) {
            foreach ($validated['approved_quantities'] as $itemId => $approvedQty) {
                $item = $claim->items()->find($itemId);
                if ($item) {
                    $item->update([
                        'current_quantity' => $approvedQty,
                        'cumulative_quantity' => $item->previous_quantity + $approvedQty,
                        'current_amount' => $approvedQty * $item->unit_price,
                    ]);
                }
            }

            // Finansal hesaplamaları yeniden yap
            $totalAmount = $claim->items()->sum('current_amount');
            $withholding = $totalAmount * 0.10;
            $advanceDeduction = $claim->advance_deduction ?? 0;
            $subtotal = $totalAmount - $withholding - $advanceDeduction;
            $vat = $totalAmount * 0.20;
            $netAmount = $totalAmount + $vat - $withholding - $advanceDeduction;

            $claim->update([
                'total_amount' => $totalAmount,
                'withholding_amount' => $withholding,
                'advance_deduction' => $advanceDeduction,
                'vat_amount' => $vat,
                'net_amount' => $netAmount,
            ]);
        }

        return redirect()->route('subcontractor.progress.approval')
            ->with('success', 'Hakediş ' . ($validated['action'] === 'approved' ? 'onaylandı' : ($validated['action'] === 'rejected' ? 'reddedildi' : 'revizyon istendi')));
    }

    public function progressPayment()
    {
        // Özet kartları için veriler
        $pendingInstructions = \App\Models\PaymentInstruction::where('status', 'pending')->get();

        $totalPending = $pendingInstructions->sum('amount');

        $thisWeekDue = $pendingInstructions->filter(function($item) {
            return $item->due_date->isBetween(now(), now()->addWeek());
        })->sum('amount');

        $overdue = $pendingInstructions->filter(function($item) {
            return $item->due_date->isPast();
        })->count();

        // Ödeme bekleyenler listesi
        $pendingPayments = $pendingInstructions->map(function($instruction) {
            return [
                'id' => $instruction->id,
                'payee' => $instruction->payee,
                'reference_no' => $instruction->reference_type === 'progress_claim' ? 'HK-' . str_pad($instruction->reference_id, 3, '0', STR_PAD_LEFT) : 'FAT-' . $instruction->reference_id,
                'due_date' => $instruction->due_date->format('d.m.Y'),
                'amount' => number_format($instruction->amount, 2, ',', '.') . ' TL',
                'project' => $instruction->project_name,
                'priority' => $instruction->priority === 'urgent' ? '❗️ Acil' : 'Normal'
            ];
        });

        return view('subcontractor.progress.payment', compact(
            'totalPending',
            'thisWeekDue',
            'overdue',
            'pendingPayments'
        ));
    }

    public function payments(Request $request)
    {
        // Dashboard verileri
        $currentMonth = now()->format('Y-m');
        $monthExpr = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', payment_date)"
            : "DATE_FORMAT(payment_date, '%Y-%m')";
        $thisMonthPaid = PaymentInstruction::where('status', 'processed')
            ->whereRaw("{$monthExpr} = ?", [$currentMonth])
            ->sum('amount');

        $pendingAmount = PaymentInstruction::where('status', 'pending')
            ->sum('amount');

        $activeSubcontractors = PaymentInstruction::where('status', 'processed')
            ->distinct('payee')
            ->count('payee');

        // Ödeme kayıtları - filtreleme ile
        $query = PaymentInstruction::with(['creator', 'processor'])
            ->where('status', 'processed')
            ->orderBy('payment_date', 'desc');

        // Filtreleme
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }

        // ...existing code...
        if ($request->filled('project')) {
            $query->where('project_name', 'like', '%' . $request->project . '%');
        }

        if ($request->filled('subcontractor')) {
            $query->where('payee', 'like', '%' . $request->subcontractor . '%');
        }

        $payments = $query->paginate(20);

        // Grafik verileri
        $monthlyPayments = PaymentInstruction::selectRaw("{$monthExpr} as month, SUM(amount) as total")
            ->where('status', 'processed')
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topSubcontractors = PaymentInstruction::selectRaw('payee, SUM(amount) as total')
            ->where('status', 'processed')
            ->groupBy('payee')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return view('subcontractor.payments', compact(
            'thisMonthPaid',
            'pendingAmount',
            'activeSubcontractors',
            'payments',
            'monthlyPayments',
            'topSubcontractors'
        ));
    }

    public function showPayment($id)
    {
        $payment = PaymentInstruction::with(['creator', 'processor'])->findOrFail($id);

        // İlişkili kayıtları al
        $reference = $payment->getReference();
        $subcontractor = Subcontractor::where('company_name', $payment->payee)->first();
        $contract = null;
        if ($reference && $reference->subcontractor_contract_id) {
            $contract = SubcontractorContract::find($reference->subcontractor_contract_id);
        }

        // Denetim izi (şimdilik basit, sonra genişletilebilir)
        $auditLog = [
            [
                'action' => 'Ödeme talimatı oluşturuldu',
                'user' => $payment->creator ? $payment->creator->name : 'Sistem',
                'timestamp' => $payment->created_at
            ]
        ];

        if ($payment->processor) {
            $auditLog[] = [
                'action' => 'Ödeme işlendi',
                'user' => $payment->processor->name,
                'timestamp' => $payment->updated_at
            ];
        }

        return view('subcontractor.payment-detail', compact(
            'payment',
            'reference',
            'subcontractor',
            'contract',
            'auditLog'
        ));
    }

    /**
     * Show create subcontractor form
     */
    public function create()
    {
        return view('subcontractor.create');
    }

    /**
     * Store new subcontractor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'expertise_area' => 'required|string|max:255',
            'status' => 'nullable|in:pending,approved,conditional,blacklisted',
        ]);

        $sub = Subcontractor::create([
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'expertise_area' => $validated['expertise_area'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        return redirect()->route('subcontractor.companies')
            ->with('success', 'Yeni taşeron başarıyla eklendi.');
    }
}
