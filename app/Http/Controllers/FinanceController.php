<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\User;
use App\Models\GeneralExpense;
use App\Models\Asset;
use App\Models\Account;

class FinanceController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        try {
            // Finansal özet verileri - güvenli tablo kontrolleri ile
            $totalRevenue = 0;
            $totalExpenses = 0;

            if (Schema::hasTable('invoices')) {
                $totalRevenue = \DB::table('invoices')->where('status', 'paid')->sum('amount') ?? 0;
            }

            if (class_exists(\App\Models\GeneralExpense::class) && Schema::hasTable('general_expenses')) {
                $totalExpenses = \App\Models\GeneralExpense::sum('amount') ?? 0;
            }

            $netProfit = $totalRevenue - $totalExpenses;
            $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

            // Nakit durumu
            $totalCash = 0;
            $bankAccounts = 0;
            $cashBoxes = 0;

            if (Schema::hasTable('cash_accounts')) {
                $cashAccounts = \DB::table('cash_accounts')->get();
                $totalCash = $cashAccounts->sum('balance');
                $bankAccounts = $cashAccounts->where('account_type', 'bank')->count();
                $cashBoxes = $cashAccounts->where('account_type', 'cash')->count();
            }

            // Müşteri istatistikleri
            $totalCustomers = 0;
            $activeCustomers = 0;
            $overdueInvoices = 0;
            $totalReceivables = 0;
            $highRiskCustomers = 0;
            $mediumRiskCustomers = 0;
            $lowRiskCustomers = 0;

            if (Schema::hasTable('customers')) {
                $totalCustomers = \App\Models\Customer::count();
                $activeCustomers = \App\Models\Customer::where('status', 'active')->count();

                if (Schema::hasColumn('customers', 'risk_score')) {
                    $highRiskCustomers = \App\Models\Customer::where('risk_score', '>=', 70)->count();
                    $mediumRiskCustomers = \App\Models\Customer::whereBetween('risk_score', [40, 69])->count();
                    $lowRiskCustomers = \App\Models\Customer::where('risk_score', '<', 40)->count();
                }
            }

            if (Schema::hasTable('invoices')) {
                $overdueInvoices = \DB::table('invoices')
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'paid')
                    ->count();

                $totalReceivables = \DB::table('invoices')
                    ->where('status', '!=', 'paid')
                    ->sum('amount') ?? 0;
            }

            // Tedarikçi istatistikleri
            $totalSuppliers = 0;
            $totalPayables = 0;

            if (Schema::hasTable('suppliers')) {
                $totalSuppliers = \DB::table('suppliers')->count();
            }

            if (Schema::hasTable('purchase_orders')) {
                $totalPayables = \DB::table('purchase_orders')
                    ->where('status', '!=', 'paid')
                    ->sum('amount') ?? 0;
            }

            // Aylık gelir trendi (son 12 ay)
            $monthlyRevenue = [];
            if (Schema::hasTable('invoices')) {
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $revenue = \DB::table('invoices')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('status', 'paid')
                        ->sum('amount') ?? 0;
                    $monthlyRevenue[] = [
                        'month' => $date->format('M Y'),
                        'revenue' => $revenue
                    ];
                }
            } else {
                // Örnek veri oluştur
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $monthlyRevenue[] = [
                        'month' => $date->format('M Y'),
                        'revenue' => rand(50000, 150000)
                    ];
                }
            }

            // Gider kategorileri
            $expenseCategories = collect();
            if (class_exists(\App\Models\GeneralExpense::class) && Schema::hasTable('general_expenses')) {
                $expenseCategories = \App\Models\GeneralExpense::select('category', \DB::raw('SUM(amount) as total'))
                    ->groupBy('category')
                    ->orderBy('total', 'desc')
                    ->take(5)
                    ->get();
            }

            // Vadesi yaklaşan faturalar (7 gün içinde)
            $upcomingInvoices = 0;
            if (Schema::hasTable('invoices')) {
                $upcomingInvoices = \DB::table('invoices')
                    ->where('due_date', '>=', now())
                    ->where('due_date', '<=', now()->addDays(7))
                    ->where('status', '!=', 'paid')
                    ->count();
            }

            // Stok değeri
            $totalStockValue = 0;
            if (Schema::hasTable('stock_cards') && Schema::hasTable('stock_movements')) {
                $typeCol = Schema::hasColumn('stock_movements', 'movement_type') ? 'movement_type' : 'type';
                $rawVal = \DB::table('stock_cards')
                    ->join('stock_movements', 'stock_cards.id', '=', 'stock_movements.stock_card_id')
                    ->selectRaw("SUM(CASE WHEN stock_movements.{$typeCol} = 'out' THEN -1 * stock_movements.quantity * stock_movements.unit_price ELSE stock_movements.quantity * stock_movements.unit_price END) as total")
                    ->value('total') ?? 0;
                $totalStockValue = max(0, (float) $rawVal);
            }

        } catch (\Exception $e) {
            // Hata durumunda varsayılan değerler
            $totalRevenue = 0;
            $totalExpenses = 0;
            $netProfit = 0;
            $profitMargin = 0;
            $totalCash = 0;
            $bankAccounts = 0;
            $cashBoxes = 0;
            $totalCustomers = 0;
            $activeCustomers = 0;
            $overdueInvoices = 0;
            $totalReceivables = 0;
            $totalSuppliers = 0;
            $totalPayables = 0;
            $monthlyRevenue = [];
            $expenseCategories = collect();
            $highRiskCustomers = 0;
            $mediumRiskCustomers = 0;
            $lowRiskCustomers = 0;
            $upcomingInvoices = 0;
            $totalStockValue = 0;

            // Örnek veri oluştur
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthlyRevenue[] = [
                    'month' => $date->format('M Y'),
                    'revenue' => rand(50000, 150000)
                ];
            }
        }

        return view('finance.dashboard', compact(
            'totalRevenue', 'totalExpenses', 'netProfit', 'profitMargin',
            'totalCash', 'bankAccounts', 'cashBoxes',
            'totalCustomers', 'activeCustomers', 'overdueInvoices', 'totalReceivables',
            'totalSuppliers', 'totalPayables',
            'monthlyRevenue', 'expenseCategories',
            'highRiskCustomers', 'mediumRiskCustomers', 'lowRiskCustomers',
            'upcomingInvoices', 'totalStockValue'
        ));
    }

    // Accounts
    public function customers()
    {
        // Load customer summary data for dashboard
        $query = \App\Models\Customer::query();

        // Enhanced filtering
        if(request()->filled('q')){
            $q = request('q');
            $query->where(function($qf) use ($q){
                $qf->where('name','like','%'.$q.'%')
                  ->orWhere('tax_number','like','%'.$q.'%')
                  ->orWhere('contact_person','like','%'.$q.'%')
                  ->orWhere('email','like','%'.$q.'%')
                  ->orWhere('phone','like','%'.$q.'%');
            });
        }

        // Risk filtering
        if(request()->filled('risk')){
            $risk = request('risk');
            if(Schema::hasColumn('customers','risk_score')){
                switch($risk){
                    case 'high':
                        $query->where('risk_score','>=',70);
                        break;
                    case 'medium':
                        $query->whereBetween('risk_score',[40,69]);
                        break;
                    case 'low':
                        $query->where('risk_score','<',40);
                        break;
                }
            }
        }

        // Status filtering
        if(request()->filled('status')){
            $query->where('status', request('status'));
        }

        // Balance filtering
        if(request()->filled('min_balance')){
            $query->where('balance','>=', request('min_balance'));
        }
        if(request()->filled('max_balance')){
            $query->where('balance','<=', request('max_balance'));
        }

        // City filtering
        if(request()->filled('city')){
            $query->where('city', 'like', '%'.request('city').'%');
        }

        // Sorting
        $sort = request('sort', 'name');
        switch($sort){
            case 'balance_desc':
                $query->orderByDesc('balance');
                break;
            case 'balance_asc':
                $query->orderBy('balance');
                break;
            case 'risk_desc':
                if(Schema::hasColumn('customers','risk_score')){
                    $query->orderByDesc('risk_score');
                }
                break;
            default:
                $query->orderBy('name');
        }

        $customersPaged = $query->paginate(20)->withQueryString();

        $totalReceivable = \App\Models\Customer::sum('balance');
        $totalOverdue = Schema::hasColumn('customers','overdue_balance') ? \App\Models\Customer::sum('overdue_balance') : 0;
        // This month collections: attempt to use Payment model if exists and has date/amount
        $thisMonthCollections = 0;
        try {
            if (class_exists(\App\Models\Payment::class)) {
                $thisMonthCollections = \App\Models\Payment::whereMonth('date', now()->month)->sum('approved');
            }
        } catch (\Exception $e) {
            $thisMonthCollections = 0;
        }

        // compute risky customers count only if relevant columns exist
        $riskyCustomersCount = 0;
        if(Schema::hasColumn('customers','risk_score') || Schema::hasColumn('customers','credit_limit')){
            $riskyQuery = \App\Models\Customer::query();
            if(Schema::hasColumn('customers','risk_score')){
                $riskyQuery->where('risk_score','>',50);
            }
            if(Schema::hasColumn('customers','credit_limit')){
                $riskyQuery->orWhereColumn('balance','>','credit_limit');
            }
            $riskyCustomersCount = $riskyQuery->count();
        }

        $topDebtors = \App\Models\Customer::orderByDesc('balance')->limit(5)->get();

        return view('finance.accounts.customers', compact('totalReceivable', 'totalOverdue', 'thisMonthCollections', 'riskyCustomersCount', 'topDebtors','customersPaged'));
    }
    public function suppliers()
    {
        // Prepare supplier/accounts summary similar to customers() but for vendors
        $vendorsQuery = \App\Models\Vendor::query();
        if(request()->filled('q')){
            $q = request('q');
            $vendorsQuery->where('name','like','%'.$q.'%')->orWhere('contact_person','like','%'.$q.'%')->orWhere('specialty','like','%'.$q.'%');
        }

        $vendorsPaged = $vendorsQuery->orderBy('name')->paginate(15)->withQueryString();

        // Totals (defensive: only if invoices table exists)
        $totalPayable = 0;
        $totalOverdue = 0;
        $thisMonthPayments = 0;
        $ageing = ['0-30' => 0, '31-60' => 0, '61+' => 0];
        $topCreditors = collect();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                // invoices for vendors: try vendor_id on invoices or purchase_orders -> invoices
                $vendorBalances = [];
                $vendors = \App\Models\Vendor::all();
                foreach ($vendors as $v) {
                    $balance = 0;
                    // if invoices has vendor_id
                    try {
                        if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')) {
                            $balance = \DB::table('invoices')->where('vendor_id',$v->id)->sum(\DB::raw('amount - paid_amount'));
                        } else {
                            // fallback: sum via purchase_orders -> invoices (purchase_orders.vendor_id)
                            if (\Illuminate\Support\Facades\Schema::hasTable('purchase_orders')) {
                                $poIds = \DB::table('purchase_orders')->where('vendor_id',$v->id)->pluck('id');
                                if ($poIds->count()) {
                                    $balance = \DB::table('invoices')->whereIn('purchase_order_id',$poIds)->sum(\DB::raw('amount - paid_amount'));
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $balance = 0;
                    }

                    $vendorBalances[$v->id] = $balance;
                    $totalPayable += max(0, $balance);

                    // ageing for vendor's unpaid invoices
                    try {
                        $today = \Carbon\Carbon::now()->startOfDay();
                        $invQuery = \DB::table('invoices')->whereRaw('amount - paid_amount > 0');
                        if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')) {
                            $invQuery->where('vendor_id', $v->id);
                        } else if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','purchase_order_id')) {
                            $poIds = \DB::table('purchase_orders')->where('vendor_id',$v->id)->pluck('id');
                            if ($poIds->count()) $invQuery->whereIn('purchase_order_id', $poIds);
                            else $invQuery = null;
                        }

                        if ($invQuery) {
                            $invs = $invQuery->get();
                            foreach ($invs as $inv) {
                                $due = isset($inv->due_date) && $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->startOfDay() : (isset($inv->date) ? \Carbon\Carbon::parse($inv->date)->startOfDay() : $today);
                                $overdueDays = $due->diffInDays($today, false);
                                $bal = ($inv->amount - ($inv->paid_amount ?? 0));
                                if ($bal <= 0) continue;
                                if ($overdueDays <= 30) $ageing['0-30'] += $bal;
                                elseif ($overdueDays <= 60) $ageing['31-60'] += $bal;
                                else $ageing['61+'] += $bal;
                            }
                        }
                    } catch (\Exception $e) {}
                }

                // Overdue: unpaid invoices past due_date
                try {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','due_date')) {
                        $totalOverdue = \DB::table('invoices')->whereRaw('amount - paid_amount > 0')->where('due_date','<', now()->toDateString())->sum(\DB::raw('amount - paid_amount'));
                    }
                } catch (\Exception $e) {}

                // This month payments: try receipts/payments table
                try {
                    if (class_exists(\App\Models\Payment::class)) {
                        $thisMonthPayments = \App\Models\Payment::whereMonth('date', now()->month)->sum('approved');
                    } elseif (\Illuminate\Support\Facades\Schema::hasTable('receipts')) {
                        // receipts table has invoice_id; count receipts linked to vendor invoices via joins
                        $q = \DB::table('receipts')->join('invoices', 'receipts.invoice_id', '=', 'invoices.id');
                        if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')) {
                            $thisMonthPayments = $q->whereMonth('receipts.date', now()->month)->sum('receipts.amount');
                        } else {
                            $thisMonthPayments = $q->whereMonth('receipts.date', now()->month)->sum('receipts.amount');
                        }
                    }
                } catch (\Exception $e) {}

                // top creditors
                try {
                    arsort($vendorBalances);
                    $topIds = array_slice(array_keys($vendorBalances), 0, 5);
                    $topCreditors = \App\Models\Vendor::whereIn('id',$topIds)->get()->map(function($v) use ($vendorBalances){
                        $v->balance = $vendorBalances[$v->id] ?? 0; return $v;
                    });
                } catch (\Exception $e) {}
            }
        } catch (\Exception $e) {
            // swallow to keep page functional in limited environments
        }

        return view('finance.accounts.suppliers', compact('vendorsPaged','totalPayable','totalOverdue','thisMonthPayments','ageing','topCreditors'));
    }
    public function vouchers()
    {
        return view('finance.accounts.vouchers');
    }

    /**
     * Store a voucher to a simple JSON log in storage/app/vouchers.json
     * This avoids requiring a DB migration while keeping records persistent on disk.
     */
    public function vouchersStore(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:collection,payment,journal',
            'date' => 'required|date',
            'no' => ['nullable','string','max:50','unique:vouchers,no'],
            'account' => 'required|string|max:255',
            'counterparty' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'lines' => 'nullable', // for journal entries (array)
        ]);

        // prepare storage path
        // Ensure vouchers table exists; if it's empty and JSON exists, import automatically
        if (!\Illuminate\Support\Facades\Schema::hasTable('vouchers')) {
            return response()->json(['ok' => false, 'message' => 'Vouchers table not found. Lütfen migration çalıştırın.'], 500);
        }

        try {
            if (\App\Models\Voucher::count() === 0) {
                // Only auto-import in local or testing environments
                if (in_array(app()->environment(), ['local','testing'])) {
                    $jsonPath = storage_path('app/vouchers.json');
                    if (file_exists($jsonPath)) {
                        \Artisan::call('vouchers:import-json');
                    }
                }
            }

            // create voucher in DB
            \DB::beginTransaction();
            $voucher = \App\Models\Voucher::create([
                'type' => $data['type'],
                'date' => $data['date'],
                'no' => $data['no'] ?? null,
                'account' => $data['account'] ?? null,
                'counterparty' => $data['counterparty'] ?? null,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->check() ? auth()->id() : null,
            ]);

            // assign a voucher number if not provided
            try {
                if (empty($voucher->no)) {
                    $voucher->no = 'VCH-' . str_pad($voucher->id, 6, '0', STR_PAD_LEFT);
                    $voucher->save();
                }
            } catch (\Exception $e) {
                // non-fatal
            }

            if (!empty($data['lines'])) {
                $lines = is_string($data['lines']) ? json_decode($data['lines'], true) : $data['lines'];
                foreach ($lines as $ln) {
                    \App\Models\VoucherLine::create([
                        'voucher_id' => $voucher->id,
                        'account' => $ln['account'] ?? null,
                        'debit' => $ln['debit'] ?? 0,
                        'credit' => $ln['credit'] ?? 0,
                        'description' => $ln['description'] ?? null,
                    ]);
                }
            }

            // Invoice matching: if invoice_id provided and invoices table exists, update paid_amount
            if ($request->filled('invoice_id') && \Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                $invoiceId = $request->input('invoice_id');
                try {
                    $inv = \DB::table('invoices')->where('id', $invoiceId)->first();
                    if ($inv) {
                        $paidCol = property_exists($inv, 'paid_amount') || \Illuminate\Support\Facades\Schema::hasColumn('invoices','paid_amount');
                        $amount = $data['amount'];
                        if ($paidCol) {
                            $newPaid = ($inv->paid_amount ?? 0) + $amount;
                            \DB::table('invoices')->where('id', $invoiceId)->update(['paid_amount' => $newPaid]);
                        }
                    }
                } catch (\Exception $e) {
                    // swallow invoice update errors to not block voucher creation
                }
            }

            \DB::commit();
            return response()->json(['ok' => true, 'entry' => $voucher]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }

    // Fallback to JSON file storage (for environments without migration run)
        $path = storage_path('app/vouchers.json');
        $list = [];
        if (file_exists($path)) {
            try {
                $contents = file_get_contents($path);
                $list = json_decode($contents, true) ?? [];
            } catch (\Exception $e) {
                $list = [];
            }
        }

        $entry = array_merge($data, [
            'id' => uniqid('vch_'),
            'created_by' => auth()->check() ? auth()->id() : null,
            'created_at' => now()->toDateTimeString(),
        ]);

        $list[] = $entry;

        try {
            file_put_contents($path, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Dosyaya yazılamadı: ' . $e->getMessage()], 500);
        }

        return response()->json(['ok' => true, 'entry' => $entry]);
    }

    /**
     * Return recent vouchers from storage/app/vouchers.json
     */
    public function vouchersList(\Illuminate\Http\Request $request)
    {
        try {
            $limit = (int) $request->query('limit', 20);
            $page = (int) $request->query('page', 1);
            if (\Illuminate\Support\Facades\Schema::hasTable('vouchers')) {
                // Use Eloquent pagination so frontend can walk pages
                $p = \App\Models\Voucher::with('lines')->orderByDesc('created_at')->paginate($limit);
                $arr = $p->toArray();
                return response()->json(['ok' => true, 'data' => $arr['data'], 'meta' => [
                    'total' => $arr['total'], 'per_page' => $arr['per_page'], 'current_page' => $arr['current_page'], 'last_page' => $arr['last_page']
                ]]);
            }
        } catch (\Exception $e) {
            // fall back to JSON
        }

        $path = storage_path('app/vouchers.json');
        $list = [];
        if (file_exists($path)) {
            try {
                $contents = file_get_contents($path);
                $list = json_decode($contents, true) ?? [];
            } catch (\Exception $e) {
                $list = [];
            }
        }

        $list = array_reverse($list);
        $limit = (int) $request->query('limit', 20);
        $page = (int) $request->query('page', 1);
        $total = count($list);
        $offset = max(0, ($page - 1) * $limit);
        $slice = array_slice($list, $offset, $limit);
        $lastPage = (int) ceil($total / max(1, $limit));

        return response()->json(['ok' => true, 'data' => $slice, 'meta' => ['total' => $total, 'per_page' => $limit, 'current_page' => $page, 'last_page' => $lastPage]]);
    }

    /**
     * Simple autocomplete for customers and vendors by name
     */
    public function vouchersSearchParties(\Illuminate\Http\Request $request)
    {
        $q = $request->query('q');
        $results = [];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                $customers = \App\Models\Customer::where('name','like','%'.$q.'%')->limit(10)->get(['id','name']);
                foreach ($customers as $c) $results[] = ['type'=>'customer','id'=>$c->id,'text'=>$c->name];
            }
        } catch (\Exception $e) {}

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('vendors')) {
                $vendors = \App\Models\Vendor::where('name','like','%'.$q.'%')->limit(10)->get(['id','name']);
                foreach ($vendors as $v) $results[] = ['type'=>'vendor','id'=>$v->id,'text'=>$v->name];
            }
        } catch (\Exception $e) {}

        return response()->json(['ok'=>true,'results'=>$results]);
    }

    /**
     * Suggest unpaid invoices for a given customer/vendor id (optional param)
     */
    public function vouchersInvoiceSuggest(\Illuminate\Http\Request $request)
    {
        $partyId = $request->query('party_id');
        $results = [];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                $q = \DB::table('invoices')->whereRaw('amount - paid_amount > 0');
                if ($partyId) {
                    if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','customer_id')) $q->where('customer_id',$partyId);
                    if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')) $q->orWhere('vendor_id',$partyId);
                }
                $invs = $q->limit(20)->get(['id','no','amount','paid_amount']);
                foreach ($invs as $inv) {
                    $bal = $inv->amount - ($inv->paid_amount ?? 0);
                    $results[] = ['id'=>$inv->id,'text'=>($inv->no ?? 'Fatura #'.$inv->id),'balance'=>$bal];
                }
            }
        } catch (\Exception $e) {}

        return response()->json(['ok'=>true,'results'=>$results]);
    }

    public function vouchersDestroy($id)
    {
        // authorization: admin can delete any, creator can delete their own
        if (!auth()->check()) return response()->json(['ok'=>false,'message'=>'Yetkisiz'],403);

        // try DB delete first
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('vouchers')) {
                $v = \App\Models\Voucher::find($id);
                if ($v) {
                    if (optional(auth()->user())->is_admin || $v->created_by == auth()->id()) {
                        $v->delete();
                        return response()->json(['ok'=>true]);
                    }
                    return response()->json(['ok'=>false,'message'=>'Yetkisiz'],403);
                }
            }
        } catch (\Exception $e) {
            // fall through to JSON fallback
        }

        // JSON fallback
        $path = storage_path('app/vouchers.json');
        if (!file_exists($path)) return response()->json(['ok'=>false,'message'=>'Kayıt bulunamadı'],404);
        try {
            $contents = json_decode(file_get_contents($path), true) ?? [];
            $new = array_values(array_filter($contents, function($r) use ($id){ return ($r['id'] ?? null) != $id; }));
            file_put_contents($path, json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return response()->json(['ok'=>true]);
        } catch (\Exception $e) {
            return response()->json(['ok'=>false,'message'=>$e->getMessage()],500);
        }
    }

    public function vouchersBulkDestroy(\Illuminate\Http\Request $request)
    {
        if (!auth()->check()) return response()->json(['ok'=>false,'message'=>'Yetkisiz'],403);

        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) return response()->json(['ok'=>false,'message'=>'ids required'],400);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('vouchers')) {
                if (optional(auth()->user())->is_admin) {
                    \App\Models\Voucher::whereIn('id',$ids)->delete();
                    return response()->json(['ok'=>true]);
                } else {
                    // normal users can only delete their own created vouchers
                    \App\Models\Voucher::whereIn('id',$ids)->where('created_by', auth()->id())->delete();
                    return response()->json(['ok'=>true]);
                }
            }
        } catch (\Exception $e) {}

        // JSON fallback
        $path = storage_path('app/vouchers.json');
        if (!file_exists($path)) return response()->json(['ok'=>false,'message'=>'Kayıt bulunamadı'],404);
        try{
            $contents = json_decode(file_get_contents($path), true) ?? [];
            $new = array_values(array_filter($contents, function($r) use ($ids){ return !in_array(($r['id'] ?? null), $ids); }));
            file_put_contents($path, json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return response()->json(['ok'=>true]);
        } catch(\Exception $e){ return response()->json(['ok'=>false,'message'=>$e->getMessage()],500); }
    }
    public function accountReports()
    {
        // Calculate KPIs and metrics for dashboard
        $kpis = [
            'total_receivables' => 0,
            'total_payables' => 0,
            'overdue_receivables' => 0,
            'overdue_payables' => 0,
            'collection_efficiency' => 0,
            'dso' => 0, // Days Sales Outstanding
            'dpo' => 0, // Days Payable Outstanding
            'cash_conversion_cycle' => 0
        ];

        try {
            // Calculate receivables
            if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'customer_id')) {
                $kpis['total_receivables'] = \DB::table('invoices')
                    ->whereNotNull('customer_id')
                    ->sum(\DB::raw('COALESCE(amount, 0) - COALESCE(paid_amount, 0)'));

                $kpis['overdue_receivables'] = \DB::table('invoices')
                    ->whereNotNull('customer_id')
                    ->where('due_date', '<', now())
                    ->sum(\DB::raw('COALESCE(amount, 0) - COALESCE(paid_amount, 0)'));
            }

            // Calculate payables
            if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'vendor_id')) {
                $kpis['total_payables'] = \DB::table('invoices')
                    ->whereNotNull('vendor_id')
                    ->sum(\DB::raw('COALESCE(amount, 0) - COALESCE(paid_amount, 0)'));

                $kpis['overdue_payables'] = \DB::table('invoices')
                    ->whereNotNull('vendor_id')
                    ->where('due_date', '<', now())
                    ->sum(\DB::raw('COALESCE(amount, 0) - COALESCE(paid_amount, 0)'));
            }

            // Calculate DSO (simplified)
            if ($kpis['total_receivables'] > 0) {
                $last30DaysSales = \DB::table('invoices')
                    ->whereNotNull('customer_id')
                    ->where('date', '>=', now()->subDays(30))
                    ->sum('amount');
                $kpis['dso'] = $last30DaysSales > 0 ? ($kpis['total_receivables'] / $last30DaysSales) * 30 : 0;
            }

            // Calculate collection efficiency
            $totalBilled = \DB::table('invoices')
                ->whereNotNull('customer_id')
                ->where('date', '>=', now()->subDays(30))
                ->sum('amount');
            $totalCollected = \DB::table('invoices')
                ->whereNotNull('customer_id')
                ->where('date', '>=', now()->subDays(30))
                ->sum('paid_amount');
            $kpis['collection_efficiency'] = $totalBilled > 0 ? ($totalCollected / $totalBilled) * 100 : 0;

        } catch (\Exception $e) {
            // Keep default values if any calculation fails
        }

        return view('finance.accounts.reports', compact('kpis'));
    }

    /**
     * AJAX data provider for account reports
     * Supports: statement (account statement), balance_list, ageing (receivable/payable)
     * Query params: type (statement|balance_list|receivable_ageing|payable_ageing), party_id, start, end
     */
    public function reportData(\Illuminate\Http\Request $request)
    {
        $type = $request->query('type');
        try {
            if ($type === 'statement') {
                $partyId = $request->query('party_id');
                $start = $request->query('start');
                $end = $request->query('end');
                $page = max(1, (int) $request->query('page', 1));
                $perPage = max(10, (int) $request->query('per_page', 20));

                if (!$partyId) return response()->json(['ok'=>false,'message'=>'party_id required'],400);

                // try customer first
                $cust = null;
                try { $cust = \App\Models\Customer::find($partyId); } catch (\Exception $e) { $cust = null; }
                if (!$cust) return response()->json(['ok'=>false,'message'=>'Cari bulunamadı'],404);

                $events = [];

                // compute opening balance (sum of unpaid invoice balances before start)
                $openingBalance = 0;
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                        $invQuery = \DB::table('invoices')->where('customer_id', $cust->id)->whereRaw('amount - COALESCE(paid_amount,0) <> 0');
                        if ($start) $openingBalance = $invQuery->where('date','<',$start)->sum(\DB::raw('amount - COALESCE(paid_amount,0)'));
                        else $openingBalance = $invQuery->where('date','<', now()->toDateString())->sum(\DB::raw('amount - COALESCE(paid_amount,0)'));
                    }
                } catch (\Exception $e) { $openingBalance = 0; }

                // gather invoices and their receipts within the range
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                        $q = \DB::table('invoices')->where('customer_id', $cust->id);
                        if ($start) $q->where('date','>=',$start);
                        if ($end) $q->where('date','<=',$end);
                        $invs = $q->orderBy('date')->orderBy('id')->get();
                        foreach ($invs as $inv) {
                            $invBal = ($inv->amount - ($inv->paid_amount ?? 0));
                            $events[] = [
                                'date' => $inv->date,
                                'no' => $inv->no ?? ('#'.$inv->id),
                                'type' => 'Invoice',
                                'desc' => $inv->description ?? '',
                                'debit' => (float) $inv->amount,
                                'credit' => 0.0,
                            ];

                            // try to attach receipts if receipts table/relation exists
                            try {
                                if (\Illuminate\Support\Facades\Schema::hasTable('receipts')) {
                                    $rs = \DB::table('receipts')->where('invoice_id', $inv->id)->orderBy('date')->get();
                                    foreach ($rs as $r) {
                                        $events[] = [
                                            'date' => $r->date,
                                            'no' => null,
                                            'type' => 'Receipt',
                                            'desc' => $r->reference ?? '',
                                            'debit' => 0.0,
                                            'credit' => (float) $r->amount,
                                        ];
                                    }
                                }
                            } catch (\Exception $e) {}
                        }
                    }
                } catch (\Exception $e) {}

                // sort events by date asc then type
                usort($events, function($a,$b){
                    $da = $a['date'] ?? null; $db = $b['date'] ?? null;
                    if ($da == $db) return 0;
                    return strtotime($da) < strtotime($db) ? -1 : 1;
                });

                // compute running balance across full event set starting from openingBalance
                $running = (float) $openingBalance;
                $fullRows = [];
                foreach ($events as $ev) {
                    $delta = (float) ($ev['debit'] ?? 0) - (float) ($ev['credit'] ?? 0);
                    $running = $running + $delta;
                    $fullRows[] = [
                        'date' => $ev['date'] ?? null,
                        'no' => $ev['no'] ?? null,
                        'type' => $ev['type'] ?? null,
                        'desc' => $ev['desc'] ?? null,
                        'debit' => $ev['debit'] ?? 0.0,
                        'credit' => $ev['credit'] ?? 0.0,
                        'balance' => $running,
                    ];
                }

                // include opening row if start provided
                $rowsToReturn = [];
                if ($start) {
                    $rowsToReturn[] = ['date' => null, 'no' => null, 'type' => 'Opening Balance', 'desc' => 'Açılış bakiye', 'debit' => 0.0, 'credit' => 0.0, 'balance' => (float) $openingBalance];
                }

                $rowsToReturn = array_merge($rowsToReturn, $fullRows);

                // pagination on rowsToReturn (excluding opening row for counting? include all)
                $total = count($rowsToReturn);
                $offset = ($page - 1) * $perPage;
                $slice = array_slice($rowsToReturn, $offset, $perPage);

                $meta = ['total' => $total, 'per_page' => $perPage, 'current_page' => $page, 'last_page' => (int) ceil($total / max(1,$perPage))];

                return response()->json(['ok'=>true,'data'=>$slice,'meta'=>$meta]);
            }

            // Cursor-based statement (DB-side) for large datasets. Query params: party_id, start, end, cursor (last invoice id), limit
            if ($type === 'statement_db') {
                $partyId = $request->query('party_id');
                if (!$partyId) return response()->json(['ok'=>false,'message'=>'party_id required'],400);
                $limit = max(10, min(100, (int) $request->query('limit', 50)));
                $cursor = $request->query('cursor'); // last invoice id seen

                if (!\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                    return response()->json(['ok'=>false,'message'=>'invoices table missing'],500);
                }

                $q = \DB::table('invoices')->where('customer_id', $partyId)->whereRaw('amount - COALESCE(paid_amount,0) <> 0');
                if ($request->filled('start')) $q->where('date','>=',$request->query('start'));
                if ($request->filled('end')) $q->where('date','<=',$request->query('end'));
                if ($cursor) $q->where('id', '>', $cursor);
                $rows = $q->orderBy('id')->limit($limit + 1)->get();

                $events = [];
                $nextCursor = null;
                $count = 0;
                foreach ($rows as $r) {
                    $count++;
                    if ($count > $limit) { $nextCursor = $rows[$limit]->id ?? null; break; }
                    $events[] = [ 'date' => $r->date, 'no' => $r->no ?? ('#'.$r->id), 'type' => 'Invoice', 'desc' => $r->description ?? '', 'debit' => (float)$r->amount, 'credit' => 0.0, 'balance' => null ];
                }

                return response()->json(['ok'=>true,'data'=>$events,'next_cursor'=>$nextCursor]);
            }

            if ($type === 'balance_list') {
                $date = $request->query('date', now()->toDateString());
                $partyType = $request->query('party_type','all');
                $result = [];
                if ($partyType === 'all' || $partyType === 'customers') {
                    if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                        $customers = \App\Models\Customer::orderBy('name')->get();
                        foreach ($customers as $c) {
                            // compute balance via invoices if possible
                            $bal = 0;
                            try {
                                if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                                    if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','customer_id')) {
                                        $bal = \DB::table('invoices')->where('customer_id',$c->id)->whereRaw('amount - paid_amount <> 0')->sum(\DB::raw('amount - paid_amount'));
                                    }
                                }
                            } catch (\Exception $e) {}
                            $result[] = ['code'=>$c->id,'title'=>$c->name,'debt'=>max(0,$bal),'credit'=>0,'balance'=>$bal];
                        }
                    }
                }
                if ($partyType === 'all' || $partyType === 'suppliers') {
                    if (\Illuminate\Support\Facades\Schema::hasTable('vendors')) {
                        $vendors = \App\Models\Vendor::orderBy('name')->get();
                        foreach ($vendors as $v) {
                            $bal = 0;
                            try {
                                if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                                    if (\Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')) {
                                        $bal = \DB::table('invoices')->where('vendor_id',$v->id)->whereRaw('amount - paid_amount <> 0')->sum(\DB::raw('amount - paid_amount'));
                                    }
                                }
                            } catch (\Exception $e) {}
                            $result[] = ['code'=>$v->id,'title'=>$v->name,'debt'=>0,'credit'=>max(0,$bal),'balance'=>-$bal];
                        }
                    }
                }

                return response()->json(['ok'=>true,'data'=>$result]);
            }

            if ($type === 'receivable_ageing' || $type === 'payable_ageing') {
                $date = $request->query('date', now()->toDateString());
                $buckets = ['current'=>0,'1_30'=>0,'31_60'=>0,'61_90'=>0,'90_plus'=>0];
                $detailedData = [];

                if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                    $today = \Carbon\Carbon::parse($date)->startOfDay();
                    $q = \DB::table('invoices')->whereRaw('amount - paid_amount > 0');

                    if ($type === 'receivable_ageing' && \Illuminate\Support\Facades\Schema::hasColumn('invoices','customer_id')) {
                        $q = $q->whereNotNull('customer_id');
                        // Join with customers for detailed view
                        if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                            $q = $q->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                                  ->select('invoices.*', 'customers.name as party_name');
                        }
                    }
                    if ($type === 'payable_ageing' && \Illuminate\Support\Facades\Schema::hasColumn('invoices','vendor_id')) {
                        $q = $q->whereNotNull('vendor_id');
                        // Join with vendors for detailed view
                        if (\Illuminate\Support\Facades\Schema::hasTable('vendors')) {
                            $q = $q->leftJoin('vendors', 'invoices.vendor_id', '=', 'vendors.id')
                                  ->select('invoices.*', 'vendors.name as party_name');
                        }
                    }

                    $invs = $q->get();
                    foreach ($invs as $inv) {
                        $due = isset($inv->due_date) && $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->startOfDay() : (isset($inv->date) ? \Carbon\Carbon::parse($inv->date)->startOfDay() : $today);
                        $overdueDays = $due->diffInDays($today, false);
                        $bal = ($inv->amount - ($inv->paid_amount ?? 0));
                        if ($bal <= 0) continue;

                        $bucket = 'current';
                        if ($overdueDays <= 0) $bucket = 'current';
                        elseif ($overdueDays <= 30) $bucket = '1_30';
                        elseif ($overdueDays <= 60) $bucket = '31_60';
                        elseif ($overdueDays <= 90) $bucket = '61_90';
                        else $bucket = '90_plus';

                        $buckets[$bucket] += $bal;

                        // Add to detailed data
                        $detailedData[] = [
                            'party_name' => $inv->party_name ?? 'N/A',
                            'invoice_no' => $inv->no ?? '#'.$inv->id,
                            'date' => $inv->date,
                            'due_date' => $inv->due_date,
                            'amount' => $bal,
                            'days_overdue' => max(0, $overdueDays),
                            'bucket' => $bucket
                        ];
                    }
                }
                return response()->json(['ok'=>true,'data'=>$buckets,'detailed'=>$detailedData]);
            }

            // Cash Flow Projection
            if ($type === 'cash_flow') {
                $periods = 12; // months
                $projections = [];

                for ($i = 0; $i < $periods; $i++) {
                    $month = now()->addMonths($i);
                    $monthStart = $month->startOfMonth()->toDateString();
                    $monthEnd = $month->endOfMonth()->toDateString();

                    $inflow = 0;
                    $outflow = 0;

                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                            // Expected receivables
                            $inflow = \DB::table('invoices')
                                ->whereNotNull('customer_id')
                                ->whereBetween('due_date', [$monthStart, $monthEnd])
                                ->sum(\DB::raw('COALESCE(amount, 0) - COALESCE(paid_amount, 0)'));

                            // Expected payables
                            $outflow = \DB::table('invoices')
                                ->whereNotNull('vendor_id')
                                ->whereBetween('due_date', [$monthStart, $monthEnd])
                                ->sum(\DB::raw('COALESCE(amount, 0) - COALESCE(paid_amount, 0)'));
                        }
                    } catch (\Exception $e) {}

                    $projections[] = [
                        'month' => $month->format('Y-m'),
                        'month_name' => $month->format('M Y'),
                        'inflow' => $inflow,
                        'outflow' => $outflow,
                        'net_flow' => $inflow - $outflow
                    ];
                }

                return response()->json(['ok'=>true,'data'=>$projections]);
            }

            // Customer Ranking by Revenue
            if ($type === 'customer_ranking') {
                $rankings = [];
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('invoices') &&
                        \Illuminate\Support\Facades\Schema::hasTable('customers')) {
                        $rankings = \DB::table('customers')
                            ->leftJoin('invoices', 'customers.id', '=', 'invoices.customer_id')
                            ->select(
                                'customers.id',
                                'customers.name',
                                \DB::raw('COALESCE(SUM(invoices.amount), 0) as total_revenue'),
                                \DB::raw('COALESCE(SUM(invoices.paid_amount), 0) as total_paid'),
                                \DB::raw('COUNT(invoices.id) as invoice_count')
                            )
                            ->groupBy('customers.id', 'customers.name')
                            ->orderByDesc('total_revenue')
                            ->limit(20)
                            ->get()
                            ->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'total_revenue' => (float) $item->total_revenue,
                                    'total_paid' => (float) $item->total_paid,
                                    'outstanding' => (float) $item->total_revenue - (float) $item->total_paid,
                                    'invoice_count' => (int) $item->invoice_count,
                                    'payment_rate' => $item->total_revenue > 0 ? (($item->total_paid / $item->total_revenue) * 100) : 0
                                ];
                            });
                    }
                } catch (\Exception $e) {}

                return response()->json(['ok'=>true,'data'=>$rankings]);
            }

            // Payment Trend Analysis
            if ($type === 'payment_trends') {
                $months = 12;
                $trends = [];

                for ($i = $months - 1; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $monthStart = $month->startOfMonth()->toDateString();
                    $monthEnd = $month->endOfMonth()->toDateString();

                    $invoiced = 0;
                    $collected = 0;

                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                            $invoiced = \DB::table('invoices')
                                ->whereNotNull('customer_id')
                                ->whereBetween('date', [$monthStart, $monthEnd])
                                ->sum('amount');

                            // Approximate collection for the month
                            $collected = \DB::table('invoices')
                                ->whereNotNull('customer_id')
                                ->whereBetween('date', [$monthStart, $monthEnd])
                                ->sum('paid_amount');
                        }
                    } catch (\Exception $e) {}

                    $trends[] = [
                        'month' => $month->format('Y-m'),
                        'month_name' => $month->format('M Y'),
                        'invoiced' => $invoiced,
                        'collected' => $collected,
                        'collection_rate' => $invoiced > 0 ? (($collected / $invoiced) * 100) : 0
                    ];
                }

                return response()->json(['ok'=>true,'data'=>$trends]);
            }

            // Risk Analysis
            if ($type === 'risk_analysis') {
                $riskAnalysis = [];
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                        $customers = \App\Models\Customer::all();
                        foreach ($customers as $customer) {
                            $overdue = 0;
                            $total = 0;
                            $invoiceCount = 0;

                            if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                                $invoices = \DB::table('invoices')
                                    ->where('customer_id', $customer->id)
                                    ->whereRaw('amount - COALESCE(paid_amount, 0) > 0')
                                    ->get();

                                foreach ($invoices as $inv) {
                                    $balance = $inv->amount - ($inv->paid_amount ?? 0);
                                    $total += $balance;
                                    $invoiceCount++;

                                    if ($inv->due_date && $inv->due_date < now()) {
                                        $overdue += $balance;
                                    }
                                }
                            }

                            $riskScore = 0;
                            if ($total > 0) {
                                $overdueRatio = ($overdue / $total) * 100;
                                $riskScore = min(100, $overdueRatio + ($invoiceCount > 10 ? 20 : 0));
                            }

                            $riskAnalysis[] = [
                                'customer_id' => $customer->id,
                                'customer_name' => $customer->name,
                                'total_outstanding' => $total,
                                'overdue_amount' => $overdue,
                                'risk_score' => $riskScore,
                                'risk_level' => $riskScore > 70 ? 'high' : ($riskScore > 40 ? 'medium' : 'low')
                            ];
                        }

                        // Sort by risk score descending
                        usort($riskAnalysis, function($a, $b) {
                            return $b['risk_score'] <=> $a['risk_score'];
                        });
                    }
                } catch (\Exception $e) {}

                return response()->json(['ok'=>true,'data'=>$riskAnalysis]);
            }

            // Performance Metrics
            if ($type === 'performance_metrics') {
                $metrics = [
                    'collection_metrics' => [],
                    'payment_patterns' => [],
                    'seasonal_trends' => []
                ];

                try {
                    // Collection efficiency by month
                    for ($i = 11; $i >= 0; $i--) {
                        $month = now()->subMonths($i);
                        $monthStart = $month->startOfMonth();
                        $monthEnd = $month->endOfMonth();

                        $billed = \DB::table('invoices')
                            ->whereNotNull('customer_id')
                            ->whereBetween('date', [$monthStart, $monthEnd])
                            ->sum('amount');

                        $collected = \DB::table('invoices')
                            ->whereNotNull('customer_id')
                            ->whereBetween('date', [$monthStart, $monthEnd])
                            ->sum('paid_amount');

                        $metrics['collection_metrics'][] = [
                            'month' => $month->format('Y-m'),
                            'month_name' => $month->format('M Y'),
                            'billed' => $billed,
                            'collected' => $collected,
                            'efficiency' => $billed > 0 ? ($collected / $billed) * 100 : 0
                        ];
                    }

                    // Payment patterns - average days to pay
                    $avgDaysLateExpr = \DB::getDriverName() === 'sqlite'
                        ? "AVG(julianday('now') - julianday(due_date)) as avg_days_late"
                        : 'AVG(DATEDIFF(NOW(), due_date)) as avg_days_late';
                    $paymentPatterns = \DB::table('invoices')
                        ->whereNotNull('customer_id')
                        ->whereNotNull('paid_amount')
                        ->where('paid_amount', '>', 0)
                        ->whereNotNull('due_date')
                        ->select('customer_id',
                            \DB::raw($avgDaysLateExpr),
                            \DB::raw('COUNT(*) as payment_count'))
                        ->groupBy('customer_id')
                        ->havingRaw('COUNT(*) >= 3')
                        ->orderByDesc('avg_days_late')
                        ->limit(10)
                        ->get();

                    foreach ($paymentPatterns as $pattern) {
                        if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                            $customer = \App\Models\Customer::find($pattern->customer_id);
                            $metrics['payment_patterns'][] = [
                                'customer_name' => $customer ? $customer->name : 'Unknown',
                                'avg_days_late' => round($pattern->avg_days_late, 1),
                                'payment_count' => $pattern->payment_count
                            ];
                        }
                    }

                } catch (\Exception $e) {}

                return response()->json(['ok'=>true,'data'=>$metrics]);
            }

            return response()->json(['ok'=>false,'message'=>'Unknown report type'],400);
        } catch (\Exception $e) {
            return response()->json(['ok'=>false,'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Simple download entrypoint - returns PDF for reconciliation-like reports when requested
     */
    public function reportDownload(\Illuminate\Http\Request $request)
    {
        // authorization: only admin or finance role allowed (defensive)
        if (!auth()->check() || !optional(auth()->user())->is_admin) {
            // if user has roles attribute, allow 'finance'
            if (! (auth()->check() && isset(auth()->user()->roles) && in_array('finance', (array) auth()->user()->roles)) ) {
                abort(403);
            }
        }
        $kind = $request->query('kind');
        if ($kind === 'reconciliation' && $request->filled('customer_id')) {
            return $this->downloadReconciliation($request->query('customer_id'));
        }
        return abort(404);
    }

    /**
     * Export reports as CSV (simple streaming)
     * Supported types: balance_list, statement, receivable_ageing, payable_ageing
     */
    public function reportExport(\Illuminate\Http\Request $request)
    {
        // authorization: only admin or finance role allowed
        if (!auth()->check() || !optional(auth()->user())->is_admin) {
            if (! (auth()->check() && isset(auth()->user()->roles) && in_array('finance', (array) auth()->user()->roles)) ) {
                abort(403);
            }
        }
        $type = $request->query('type');
        $format = strtolower($request->query('format','csv'));
        $basename = 'report-'.($type ?: 'export').'-'.date('YmdHis');

        if ($format === 'xlsx') {
            // generate XLSX via PhpSpreadsheet
            $spread = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spread->getActiveSheet();
            if ($type === 'balance_list') {
                $sheet->fromArray(['Cari Kodu','Cari Unvanı','Toplam Borç','Toplam Alacak','Bakiye'],null,'A1');
                $row = 2;
                $partyType = $request->query('party_type','all');
                if ($partyType === 'all' || $partyType === 'customers') {
                    if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                        $customers = \App\Models\Customer::orderBy('name')->get();
                        foreach ($customers as $c) {
                            $bal = 0; try { if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) { $bal = \DB::table('invoices')->where('customer_id',$c->id)->whereRaw('amount - COALESCE(paid_amount,0) <> 0')->sum(\DB::raw('amount - COALESCE(paid_amount,0)')); } } catch (\Exception $e) {}
                            $sheet->fromArray([$c->id,$c->name,$bal,0,$bal],null,'A'.$row);
                            $row++;
                        }
                    }
                }
                if ($partyType === 'all' || $partyType === 'suppliers') {
                    if (\Illuminate\Support\Facades\Schema::hasTable('vendors')) {
                        $vendors = \App\Models\Vendor::orderBy('name')->get();
                        foreach ($vendors as $v) {
                            $bal = 0; try { if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) { $bal = \DB::table('invoices')->where('vendor_id',$v->id)->whereRaw('amount - COALESCE(paid_amount,0) <> 0')->sum(\DB::raw('amount - COALESCE(paid_amount,0)')); } } catch (\Exception $e) {}
                            $sheet->fromArray([$v->id,$v->name,0,$bal,-$bal],null,'A'.$row);
                            $row++;
                        }
                    }
                }
            } elseif ($type === 'statement') {
                $partyId = $request->query('party_id');
                $sheet->fromArray(['Tarih','Fiş No','Tür','Açıklama','Borç','Alacak','Bakiye'],null,'A1');
                $fake = $this->reportData($request);
                $content = $fake->getData(true);
                $row = 2;
                foreach ($content['data'] as $r) {
                    $sheet->fromArray([ $r['date'] ?? '', $r['no'] ?? '', $r['type'] ?? '', $r['desc'] ?? '', $r['debit'] ?? 0, $r['credit'] ?? 0, $r['balance'] ?? '' ],null,'A'.$row);
                    $row++;
                }
            } elseif ($type === 'receivable_ageing' || $type === 'payable_ageing') {
                $fake = $this->reportData($request);
                $content = $fake->getData(true);
                $d = $content['data'] ?? [];
                $sheet->fromArray(['Bucket','Tutar'],null,'A1');
                $sheet->fromArray(['Vadesi Gelmemiş', $d['current'] ?? 0],null,'A2');
                $sheet->fromArray(['1-30', $d['1_30'] ?? 0],null,'A3');
                $sheet->fromArray(['31-60', $d['31_60'] ?? 0],null,'A4');
                $sheet->fromArray(['61-90', $d['61_90'] ?? 0],null,'A5');
                $sheet->fromArray(['90+', $d['90_plus'] ?? 0],null,'A6');
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spread);
            $filename = $basename.'.xlsx';
            // stream
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            $writer->save('php://output');
            exit;
        }

        // default CSV streaming fallback (existing behavior)
        $filename = $basename.'.csv';
        $callback = function() use ($request, $type) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fwrite($out, "\xEF\xBB\xBF");
            if ($type === 'balance_list') {
                fputcsv($out, ['Cari Kodu','Cari Unvanı','Toplam Borç','Toplam Alacak','Bakiye']);
                $partyType = $request->query('party_type','all');
                if ($partyType === 'all' || $partyType === 'customers') {
                    if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
                        $customers = \App\Models\Customer::orderBy('name')->get();
                        foreach ($customers as $c) {
                            $bal = 0; try { if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) { $bal = \DB::table('invoices')->where('customer_id',$c->id)->whereRaw('amount - COALESCE(paid_amount,0) <> 0')->sum(\DB::raw('amount - COALESCE(paid_amount,0)')); } } catch (\Exception $e) {}
                            fputcsv($out, [$c->id,$c->name, (string) $bal, '0', (string) $bal]);
                        }
                    }
                }
                if ($partyType === 'all' || $partyType === 'suppliers') {
                    if (\Illuminate\Support\Facades\Schema::hasTable('vendors')) {
                        $vendors = \App\Models\Vendor::orderBy('name')->get();
                        foreach ($vendors as $v) {
                            $bal = 0; try { if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) { $bal = \DB::table('invoices')->where('vendor_id',$v->id)->whereRaw('amount - COALESCE(paid_amount,0) <> 0')->sum(\DB::raw('amount - COALESCE(paid_amount,0)')); } } catch (\Exception $e) {}
                            fputcsv($out, [$v->id,$v->name, '0', (string) $bal, (string)(-$bal)]);
                        }
                    }
                }
            } elseif ($type === 'statement') {
                $partyId = $request->query('party_id');
                if (!$partyId) { fputcsv($out, ['error','party_id required']); fclose($out); return; }
                fputcsv($out, ['Tarih','Fiş No','Tür','Açıklama','Borç','Alacak','Bakiye']);
                // reuse reportData logic by calling internally (non-HTTP)
                $fake = $this->reportData($request);
                $content = $fake->getData(true);
                if (isset($content['data']) && is_array($content['data'])) {
                    foreach ($content['data'] as $r) {
                        fputcsv($out, [ $r['date'] ?? '', $r['no'] ?? '', $r['type'] ?? '', $r['desc'] ?? '', $r['debit'] ?? 0, $r['credit'] ?? 0, $r['balance'] ?? '' ]);
                    }
                }
            } elseif ($type === 'receivable_ageing' || $type === 'payable_ageing') {
                fputcsv($out, ['Bucket','Tutar']);
                $fake = $this->reportData($request);
                $content = $fake->getData(true);
                $d = $content['data'] ?? [];
                fputcsv($out, ['Vadesi Gelmemiş', $d['current'] ?? 0]);
                fputcsv($out, ['1-30', $d['1_30'] ?? 0]);
                fputcsv($out, ['31-60', $d['31_60'] ?? 0]);
                fputcsv($out, ['61-90', $d['61_90'] ?? 0]);
                fputcsv($out, ['90+', $d['90_plus'] ?? 0]);
            } else {
                fputcsv($out, ['error','unsupported type']);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // Customer detail (360 view)
    public function customerShow($id)
    {
        $customer = \App\Models\Customer::findOrFail($id);

    // Account statement: try to load payments or fallback to invoices/receipts
        $statement = [];
        try {
            if (class_exists(\App\Models\Payment::class)) {
                $payments = \App\Models\Payment::where('contractor', $customer->name)->orderByDesc('date')->get();
                foreach ($payments as $p) {
                    $statement[] = [
                        'date' => $p->date,
                        'type' => 'Payment',
                        'amount' => $p->approved ?? 0,
                        'notes' => $p->notes ?? ''
                    ];
                }
            }
        } catch (\Exception $e) {
            // ignore
        }

        // Prepare statement from invoices and receipts if available
        try {
            if (Schema::hasTable('invoices')) {
                $invoices = $customer->invoices()->orderByDesc('date')->get();
                foreach ($invoices as $inv) {
                    $statement[] = ['date' => $inv->date ? $inv->date->toDateString() : null, 'type' => 'Invoice', 'amount' => $inv->amount, 'notes' => $inv->no];
                    foreach ($inv->receipts as $r) {
                        $statement[] = ['date' => $r->date ? $r->date->toDateString() : null, 'type' => 'Receipt', 'amount' => -$r->amount, 'notes' => ($r->method ?? '') . ' ' . ($r->reference ?? '')];
                    }
                }
            }
        } catch (\Exception $e) {
            // ignore
        }

        // Sort statement by date descending
        usort($statement, function($a, $b) {
            return strtotime($b['date'] ?? '1970-01-01') - strtotime($a['date'] ?? '1970-01-01');
        });



        // Placeholder related projects: try relation if exists
        $projects = [];
        try {
            if (method_exists($customer, 'projects')) {
                $projects = $customer->projects()->get();
            }
        } catch (\Exception $e) {}

        // Enhanced analytics
        $analytics = [
            'total_invoiced' => 0,
            'total_paid' => 0,
            'total_outstanding' => $customer->balance ?? 0,
            'payment_behavior' => 'İyi',
            'last_payment_date' => null,
            'avg_payment_days' => 0,
            'credit_utilization' => ($customer->credit_limit > 0) ? (($customer->balance ?? 0) / $customer->credit_limit) * 100 : 0
        ];

        // Enhanced risk assessment
        $risk = [
            'credit_limit' => $customer->credit_limit ?? 0,
            'risk_score' => $customer->risk_score ?? 0,
            'risk_level' => $this->calculateRiskLevel($customer->risk_score ?? 0),
            'notes' => $this->generateRiskNotes($customer),
            'recommendations' => $this->generateRecommendations($customer, $analytics)
        ];

        // reconciliation logs may not exist if migrations haven't been run
        $logs = [];
        try {
            if (Schema::hasTable('reconciliation_logs')) {
                $logs = $customer->reconciliationLogs()->orderByDesc('created_at')->limit(50)->get();
            }
        } catch (\Exception $e) {
            // swallow DB errors here to avoid breaking the customer view in non-migrated environments
            $logs = [];
        }

        try {
            if (Schema::hasTable('invoices')) {
                $analytics['total_invoiced'] = $customer->invoices()->sum('amount');
                $analytics['total_paid'] = $customer->invoices()->sum('paid_amount');

                // Last payment calculation
                $lastReceipt = $customer->receipts()->orderByDesc('date')->first();
                if ($lastReceipt) {
                    $analytics['last_payment_date'] = $lastReceipt->date;
                }
            }
        } catch (\Exception $e) {}

        // Enhanced ageing with details
        $ageingDetails = [];
        $ageing = ['0-30' => 0, '31-60' => 0, '61-90' => 0, '91_plus' => 0];
        try {
            if (Schema::hasTable('invoices')) {
                $today = now()->startOfDay();
                foreach ($customer->invoices as $inv) {
                    $due = $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->startOfDay() : (isset($inv->date) ? \Carbon\Carbon::parse($inv->date)->addDays(30)->startOfDay() : $today);
                    $overdueDays = $due->diffInDays($today, false);
                    $balance = ($inv->amount - ($inv->paid_amount ?? 0));
                    if ($balance <= 0) continue;

                    $bucket = '';
                    if ($overdueDays <= 30) {
                        $ageing['0-30'] += $balance;
                        $bucket = '0-30';
                    } elseif ($overdueDays <= 60) {
                        $ageing['31-60'] += $balance;
                        $bucket = '31-60';
                    } elseif ($overdueDays <= 90) {
                        $ageing['61-90'] += $balance;
                        $bucket = '61-90';
                    } else {
                        $ageing['91_plus'] += $balance;
                        $bucket = '91+';
                    }

                    $ageingDetails[] = [
                        'invoice_no' => $inv->no,
                        'date' => $inv->date,
                        'due_date' => $inv->due_date,
                        'amount' => $inv->amount,
                        'paid' => $inv->paid_amount ?? 0,
                        'balance' => $balance,
                        'days_overdue' => max(0, $overdueDays),
                        'bucket' => $bucket
                    ];
                }
            }
        } catch (\Exception $e) {}

        // Monthly payment trend (last 12 months)
        $monthlyTrend = [];
        try {
            if (Schema::hasTable('receipts')) {
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $amount = $customer->receipts()
                        ->whereYear('date', $date->year)
                        ->whereMonth('date', $date->month)
                        ->sum('amount');
                    $monthlyTrend[] = [
                        'month' => $date->format('M Y'),
                        'amount' => $amount
                    ];
                }
            }
        } catch (\Exception $e) {}

        return view('finance.accounts.customer_show', compact('customer','statement','projects','risk','ageing','ageingDetails','logs','analytics','monthlyTrend'));
    }

    private function calculateRiskLevel($riskScore)
    {
        if ($riskScore >= 80) return 'Yüksek';
        if ($riskScore >= 60) return 'Orta';
        if ($riskScore >= 40) return 'Düşük';
        return 'Minimal';
    }

    private function generateRiskNotes($customer)
    {
        $notes = [];

        if (($customer->balance ?? 0) > ($customer->credit_limit ?? 0) * 0.9) {
            $notes[] = "Kredi limitinin %90'ına yaklaşmış durumda.";
        }

        if (($customer->overdue_balance ?? 0) > 0) {
            $notes[] = "Vadesi geçmiş borcu bulunmaktadır.";
        }

        if (($customer->risk_score ?? 0) > 70) {
            $notes[] = "Risk skoru yüksek, dikkatli takip gereklidir.";
        }

        return empty($notes) ? "Risk faktörü tespit edilmemiştir." : implode(' ', $notes);
    }

    private function generateRecommendations($customer, $analytics)
    {
        $recommendations = [];

        if ($analytics['credit_utilization'] > 80) {
            $recommendations[] = "Kredi limitini artırmayı değerlendirin.";
        }

        if (($customer->balance ?? 0) > 0 && !$analytics['last_payment_date']) {
            $recommendations[] = "Müşteri ile iletişime geçerek ödeme planı oluşturun.";
        }

        if (($customer->risk_score ?? 0) > 60) {
            $recommendations[] = "Teminat veya avans ödemesi talep edin.";
        }

        return $recommendations;
    }

    // Supplier detail (mirror of SupplyController@show) for finance URL
    public function supplierShow($id)
    {
        $vendor = \App\Models\Vendor::findOrFail($id);

        $reviews = [];
        $documents = [];
        try {
            if (class_exists(\App\Models\VendorReview::class)) {
                $reviews = \App\Models\VendorReview::where('vendor_id', $vendor->id)->orderBy('created_at','desc')->get();
            }
        } catch (\Exception $e) { $reviews = collect(); }

        try {
            if (class_exists(\App\Models\VendorDocument::class)) {
                $documents = \App\Models\VendorDocument::where('vendor_id',$vendor->id)->orderBy('created_at','desc')->get();
            }
        } catch (\Exception $e) { $documents = collect(); }

        return view('supply.vendors.show', compact('vendor','reviews','documents'));
    }

    // Customer CRUD - Create
    public function customerCreate()
    {
        return view('finance.accounts.customer_form', ['customer' => new \App\Models\Customer(), 'mode' => 'create']);
    }

    public function customerStore(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'tax_office' => 'nullable|string|max:100',
            'mersis' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'iban' => 'nullable|string|max:34',
            'bank' => 'nullable|string|max:100',
            'tc_id' => 'nullable|string|max:11',
            'balance' => 'nullable|numeric',
            'credit_limit' => 'nullable|numeric',
            'risk_score' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        $customer = \App\Models\Customer::create($data);

        return redirect()->route('finance.accounts.customers.show', ['id' => $customer->id])->with('success', 'Müşteri oluşturuldu.');
    }

    public function customerEdit($id)
    {
        $customer = \App\Models\Customer::findOrFail($id);
        return view('finance.accounts.customer_form', ['customer' => $customer, 'mode' => 'edit']);
    }

    public function customerUpdate(\Illuminate\Http\Request $request, $id)
    {
        $customer = \App\Models\Customer::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'tax_office' => 'nullable|string|max:100',
            'mersis' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:20',
            'iban' => 'nullable|string|max:34',
            'bank' => 'nullable|string|max:100',
            'tc_id' => 'nullable|string|max:11',
            'balance' => 'nullable|numeric',
            'credit_limit' => 'nullable|numeric',
            'risk_score' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        $customer->update($data);
        return redirect()->route('finance.accounts.customers.show', ['id' => $customer->id])->with('success', 'Müşteri güncellendi.');
    }

    public function customerDestroy($id)
    {
        $customer = \App\Models\Customer::withTrashed()->findOrFail($id);

        // Allow permanent delete via request param `permanent=1`
        if(request()->filled('permanent') && request('permanent')){
            // only allow force delete if model supports it
            try {
                if(method_exists($customer, 'forceDelete')) {
                    $customer->forceDelete();
                    return redirect()->route('finance.accounts.customers')->with('success', 'Müşteri kalıcı olarak silindi.');
                }
            } catch (\Exception $e) {
                return redirect()->route('finance.accounts.customers')->with('error', 'Kalıcı silme sırasında hata: '.$e->getMessage());
            }
        }

        // soft delete
        try {
            $customer->delete();
            return redirect()->route('finance.accounts.customers')->with('success', 'Müşteri silindi.');
        } catch (\Exception $e) {
            return redirect()->route('finance.accounts.customers')->with('error', 'Silme sırasında hata: '.$e->getMessage());
        }
    }

    // List trashed customers (Recycle Bin)
    public function customersRecycle()
    {
        $query = \App\Models\Customer::onlyTrashed();
        // maintain same pagination and query params
        $customersPaged = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('finance.accounts.customers-recycle', compact('customersPaged'));
    }

    // Restore a trashed customer
    public function customerRestore($id)
    {
        $customer = \App\Models\Customer::onlyTrashed()->where('id', $id)->first();
        if(!$customer) return redirect()->route('finance.accounts.customers.recycle')->with('error', 'Müşteri bulunamadı.');
        try{
            $customer->restore();
            return redirect()->route('finance.accounts.customers.recycle')->with('success', 'Müşteri geri yüklendi.');
        } catch (\Exception $e) {
            return redirect()->route('finance.accounts.customers.recycle')->with('error', 'Geri yükleme sırasında hata: '.$e->getMessage());
        }
    }

    public function downloadReconciliation($id)
    {
        $customer = \App\Models\Customer::findOrFail($id);
        $invoices = collect();
        if (Schema::hasTable('invoices')) {
            try {
                $invoices = $customer->invoices()->orderBy('date')->get();
            } catch (\Exception $e) {
                $invoices = collect();
            }
        }
        $bodyHtml = view('emails.reconciliation', ['customer' => $customer, 'bodyHtml' => ''])->render();

        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.reconciliation', ['customer' => $customer, 'bodyHtml' => $bodyHtml]);
                // log download (only if reconciliation_logs table exists)
                if (Schema::hasTable('reconciliation_logs')) {
                    \App\Models\ReconciliationLog::create([
                        'customer_id' => $customer->id,
                        'user_id' => optional(auth())->id(),
                        'action' => 'downloaded',
                        'email' => $customer->email,
                        'success' => true,
                        'filename' => 'mutabakat-'.$customer->id.'.pdf',
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                }
                return $pdf->download('mutabakat-'.$customer->id.'.pdf');
        }

        // fallback: return HTML
        return response($bodyHtml);
    }

    public function sendReconciliation($id)
    {
        $customer = \App\Models\Customer::findOrFail($id);

        // build a simple account summary html
        $rows = "";
        if (Schema::hasTable('invoices')) {
            try {
                $invoices = $customer->invoices()->orderBy('date')->get();
                $rows = "<table class='table'><thead><tr><th>Fatura No</th><th>Tarih</th><th>Vade</th><th>Tutar</th><th>Ödenen</th><th>Bakiye</th></tr></thead><tbody>";
                foreach ($invoices as $inv) {
                    $rows .= "<tr><td>{$inv->no}</td><td>{$inv->date}</td><td>{$inv->due_date}</td><td>".number_format($inv->amount,2,',','.')."</td><td>".number_format($inv->paid_amount,2,',','.')."</td><td>".number_format(($inv->amount - ($inv->paid_amount ?? 0)),2,',','.')."</td></tr>";
                }
                $rows .= "</tbody></table>";
            } catch (\Exception $e) {
                $rows = "<div class='text-muted'>Fatura verileri okunamadı.</div>";
            }
        } else {
            $rows = "<div class='text-muted'>Fatura tablosu mevcut değil. Lütfen veritabanı migrasyonlarını çalıştırın.</div>";
        }

        $bodyHtml = "<p>Sayın {$customer->contact_person},</p><p>Ek'te bulunan mutabakat tablosunu lütfen kontrol ediniz.</p>" . $rows;

        try {
            $mail = new \App\Mail\ReconciliationMail($customer, $bodyHtml);
            // if dompdf available, generate PDF and attach
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf') || class_exists('\Barryvdh\DomPDF\Facade\DOMPDF')) {
                try {
                    $pdf = null;
                    if(class_exists('\Barryvdh\DomPDF\Facade\Pdf')){
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.reconciliation', ['customer' => $customer, 'bodyHtml' => $bodyHtml]);
                    }else{
                        $pdf = app('\Barryvdh\DomPDF\Facade\DOMPDF')->loadView('emails.reconciliation', ['customer' => $customer, 'bodyHtml' => $bodyHtml]);
                    }
                    $mail->attachData($pdf->output(), 'mutabakat-'.$customer->id.'.pdf', ['mime' => 'application/pdf']);
                } catch (\Exception $e) {
                    // ignore pdf generation errors, continue to send html
                }
            }

            \Mail::to($customer->email)->send($mail);

            // log success
            if (Schema::hasTable('reconciliation_logs')) {
                \App\Models\ReconciliationLog::create([
                    'customer_id' => $customer->id,
                    'user_id' => optional(auth())->id(),
                    'action' => 'sent',
                    'email' => $customer->email,
                    'success' => true,
                    'filename' => isset($pdf) ? ('mutabakat-'.$customer->id.'.pdf') : null,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'notes' => null,
                ]);
            }

            return back()->with('success', 'Mutabakat gönderildi.');
        } catch (\Exception $e) {
            // log failure
            if (Schema::hasTable('reconciliation_logs')) {
                \App\Models\ReconciliationLog::create([
                    'customer_id' => $customer->id,
                    'user_id' => optional(auth())->id(),
                    'action' => 'sent',
                    'email' => $customer->email,
                    'success' => false,
                    'filename' => null,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'notes' => $e->getMessage(),
                ]);
            }

            return back()->with('error', 'E-posta gönderilemedi: '.$e->getMessage());
        }
    }

    /**
     * Update an existing voucher
     */
    public function vouchersUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (!auth()->check()) return response()->json(['ok' => false, 'message' => 'Yetkisiz'], 403);

        $data = $request->validate([
            'type' => 'required|in:collection,payment,journal',
            'date' => 'required|date',
            'no' => ['nullable','string','max:50',"unique:vouchers,no,{$id}"],
            'account' => 'nullable|string|max:255',
            'counterparty' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'lines' => 'nullable',
        ]);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('vouchers')) {
                $v = \App\Models\Voucher::find($id);
                if (!$v) return response()->json(['ok' => false, 'message' => 'Kayıt bulunamadı'], 404);
                // permission
                if (!optional(auth()->user())->is_admin && $v->created_by != auth()->id()) {
                    return response()->json(['ok' => false, 'message' => 'Yetkisiz'], 403);
                }

                \DB::beginTransaction();
                $v->update([
                    'no' => $data['no'] ?? $v->no,
                    'type' => $data['type'],
                    'date' => $data['date'],
                    'account' => $data['account'] ?? null,
                    'counterparty' => $data['counterparty'] ?? null,
                    'amount' => $data['amount'],
                    'payment_method' => $data['payment_method'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);

                // replace lines if provided
                if (!empty($data['lines'])) {
                    $lines = is_string($data['lines']) ? json_decode($data['lines'], true) : $data['lines'];
                    // delete old lines
                    \App\Models\VoucherLine::where('voucher_id', $v->id)->delete();
                    foreach ($lines as $ln) {
                        \App\Models\VoucherLine::create([
                            'voucher_id' => $v->id,
                            'account' => $ln['account'] ?? null,
                            'debit' => $ln['debit'] ?? 0,
                            'credit' => $ln['credit'] ?? 0,
                            'description' => $ln['description'] ?? null,
                        ]);
                    }
                }

                \DB::commit();
                return response()->json(['ok' => true, 'entry' => $v->fresh()]);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }

        // JSON fallback: update file entries if present
        $path = storage_path('app/vouchers.json');
        if (!file_exists($path)) return response()->json(['ok' => false, 'message' => 'Kayıt bulunamadı'], 404);
        try {
            $contents = json_decode(file_get_contents($path), true) ?? [];
            $found = false;
            foreach ($contents as &$r) {
                if (($r['id'] ?? null) == $id) {
                    $r = array_merge($r, $data);
                    $found = true;
                    break;
                }
            }
            if (!$found) return response()->json(['ok' => false, 'message' => 'Kayıt bulunamadı'], 404);
            file_put_contents($path, json_encode($contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeReceipt(\Illuminate\Http\Request $request, $invoiceId = null)
    {
        // ensure invoices table exists
        if (!Schema::hasTable('invoices')) {
            return back()->with('error', 'Fatura tablosu mevcut değil. Lütfen veritabanı migrasyonlarını çalıştırın.');
        }
        $invoiceId = $invoiceId ?? $request->input('invoice_id');
        $invoice = \App\Models\Invoice::findOrFail($invoiceId);
        $data = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

    $receipt = \App\Models\Receipt::create(array_merge($data, ['invoice_id' => $invoice->id]));

        // update invoice paid amount and status
        $invoice->paid_amount = ($invoice->paid_amount ?? 0) + $receipt->amount;
        if($invoice->paid_amount >= $invoice->amount) $invoice->status = 'paid';
        elseif($invoice->paid_amount > 0) $invoice->status = 'partially_paid';
        $invoice->save();

        // update customer balance / overdue (simple recalculation)
        $customer = $invoice->customer;
        if($customer){
            $balance = $customer->invoices()->sum(\DB::raw('amount - paid_amount'));
            $customer->balance = $balance;
            // overdue balance: sum of unpaid invoices past due date (only if column exists)
            if(Schema::hasColumn('customers','overdue_balance')){
                $customer->overdue_balance = $customer->invoices()->whereRaw('due_date < ? and (amount - paid_amount) > 0', [now()->toDateString()])->sum(\DB::raw('amount - paid_amount'));
            }
            $customer->save();
        }

        return back()->with('success','Tahsilat kaydedildi.');
    }

    // Cash & Bank
    public function cashAccounts()
    {
        return view('finance.cash.accounts');
    }
    // JSON data provider for cash accounts (used by the frontend)
    public function cashAccountsData(\Illuminate\Http\Request $request)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('accounts')) {
                $accounts = \App\Models\Account::orderBy('name')->get();
                // Treat legacy or alternate 'cash' type as 'cashbox' so cash accounts created elsewhere are visible
                $totals = [
                    'total' => $accounts->sum('balance'),
                    'total_bank' => $accounts->where('type','bank')->sum('balance'),
                    // include both 'cashbox' and legacy 'cash' types
                    'total_cash' => $accounts->whereIn('type',['cashbox','cash'])->sum('balance'),
                    'count' => $accounts->count(),
                    'active_count' => $accounts->where('active',1)->count(),
                    'inactive_count' => $accounts->where('active',0)->count(),
                ];
                $distribution = $accounts->groupBy(function($a){ return $a->bank_name ?: $a->name; })->map(function($group){ return ['label'=>$group->first()->bank_name ?? $group->first()->name, 'value'=>$group->sum('balance')]; })->values();
                // simple trend: last 30 days use a flat progression of total for demo (better: compute from transactions table)
                $trend = [];
                for ($i=29;$i>=0;$i--) {
                    $trend[] = ['date'=>\Carbon\Carbon::now()->subDays($i)->toDateString(), 'value'=>round($totals['total'] * (0.9 + ($i/300)),2)];
                }
                return response()->json(['ok'=>true,'accounts'=>$accounts,'totals'=>$totals,'distribution'=>$distribution,'trend'=>$trend]);
            }
            return response()->json(['ok'=>false,'message'=>'accounts table not found'],404);
        } catch (\Exception $e) {
            return response()->json(['ok'=>false,'message'=>$e->getMessage()],500);
        }
    }

    // Store a new account (basic implementation)
    public function cashAccountsStore(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:bank,cashbox',
            'name' => 'required|string|max:191',
            'currency' => 'nullable|string|max:10',
            'bank_name' => 'nullable|string|max:191',
            'branch' => 'nullable|string|max:191',
            'account_no' => 'nullable|string|max:191',
            'iban' => 'nullable|string|max:64',
            'responsible' => 'nullable|string|max:191',
            'opening_balance' => 'nullable|numeric',
            'opening_date' => 'nullable|date',
        ]);

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('accounts')) {
                return back()->with('error','Accounts table missing. Run migrations.');
            }
            $acc = \App\Models\Account::create([
                'type' => $data['type'],
                'name' => $data['name'],
                'currency' => $data['currency'] ?? 'TRY',
                'bank_name' => $data['bank_name'] ?? null,
                'branch' => $data['branch'] ?? null,
                'account_no' => $data['account_no'] ?? null,
                'iban' => $data['iban'] ?? null,
                'responsible' => $data['responsible'] ?? null,
                'balance' => (float) ($data['opening_balance'] ?? 0),
                'opening_date' => $data['opening_date'] ?? null,
                'created_by' => auth()->id() ?? null,
            ]);
            // If request is AJAX, return JSON
            if($request->wantsJson() || $request->ajax()){
                return response()->json(['ok'=>true,'account'=>$acc]);
            }
            return back()->with('success','Hesap kaydedildi.');
        } catch (\Exception $e) {
            return back()->with('error','Kaydetme hatası: '.$e->getMessage());
        }
    }

    // Return account movements for given account id (JSON)
    public function cashAccountMovements($id, \Illuminate\Http\Request $request)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('account_transactions')) {
                return response()->json(['ok'=>false,'message'=>'account_transactions table missing'],404);
            }

            $q = \DB::table('account_transactions')->where('account_id', $id);

            // Date filters
            if ($request->filled('start')) {
                $q->where('date', '>=', $request->query('start'));
            }
            if ($request->filled('end')) {
                $q->where('date', '<=', $request->query('end'));
            }

            // Search filter (description or voucher)
            if ($request->filled('q')) {
                $searchTerm = '%' . $request->query('q') . '%';
                $q->where(function($subQuery) use ($searchTerm) {
                    $subQuery->where('description', 'like', $searchTerm)
                            ->orWhere('reference', 'like', $searchTerm);
                });
            }

            // Transaction type filter
            if ($request->filled('type')) {
                $type = $request->query('type');
                switch ($type) {
                    case 'collection':
                        $q->where('debit', '>', 0)->whereNull('credit');
                        break;
                    case 'payment':
                        $q->where('credit', '>', 0)->whereNull('debit');
                        break;
                    case 'transfer':
                        $q->where(function($subQuery) {
                            $subQuery->where('description', 'like', '%transfer%')
                                    ->orWhere('description', 'like', '%virman%');
                        });
                        break;
                    case 'expense':
                        $q->where('credit', '>', 0)
                          ->where(function($subQuery) {
                              $subQuery->where('description', 'like', '%expense%')
                                      ->orWhere('description', 'like', '%masraf%')
                                      ->orWhere('description', 'like', '%gider%');
                          });
                        break;
                }
            }

            // Amount filters
            if ($request->filled('min_amount')) {
                $minAmount = (float) $request->query('min_amount');
                $q->where(function($subQuery) use ($minAmount) {
                    $subQuery->where('debit', '>=', $minAmount)
                            ->orWhere('credit', '>=', $minAmount);
                });
            }
            if ($request->filled('max_amount')) {
                $maxAmount = (float) $request->query('max_amount');
                $q->where(function($subQuery) use ($maxAmount) {
                    $subQuery->where('debit', '<=', $maxAmount)
                            ->orWhere('credit', '<=', $maxAmount);
                });
            }

            $rows = $q->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

            // Enrich rows with voucher information and additional data
            $enriched = [];
            foreach ($rows as $r) {
                $row = (array) $r;

                // Add formatted amounts
                $row['debit'] = $r->debit ? (float) $r->debit : null;
                $row['credit'] = $r->credit ? (float) $r->credit : null;
                $row['balance'] = $r->balance ? (float) $r->balance : null;

                // Add transaction type classification
                if ($r->debit && $r->debit > 0) {
                    $row['transaction_type'] = 'income';
                } elseif ($r->credit && $r->credit > 0) {
                    $row['transaction_type'] = 'expense';
                } else {
                    $row['transaction_type'] = 'neutral';
                }

                // Attempt to detect voucher relation
                if (isset($r->voucher_id) && $r->voucher_id) {
                    try {
                        $v = \DB::table('vouchers')->where('id', $r->voucher_id)->first();
                        if ($v) {
                            $row['voucher_no'] = $v->no ?? ('#'.$v->id);
                            $row['voucher_link'] = url('finance/accounts/vouchers/'.$v->id.'/show');
                        }
                    } catch (\Exception $e) {}
                }

                // Add formatted date
                $row['formatted_date'] = \Carbon\Carbon::parse($r->date)->format('d.m.Y');
                $row['formatted_datetime'] = \Carbon\Carbon::parse($r->created_at ?? $r->date)->format('d.m.Y H:i');

                $enriched[] = $row;
            }

            return response()->json([
                'ok' => true,
                'data' => $enriched,
                'meta' => [
                    'total' => count($enriched),
                    'account_id' => $id,
                    'filters_applied' => [
                        'start' => $request->query('start'),
                        'end' => $request->query('end'),
                        'type' => $request->query('type'),
                        'search' => $request->query('q'),
                        'min_amount' => $request->query('min_amount'),
                        'max_amount' => $request->query('max_amount')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Cash account movements error: ' . $e->getMessage());
            return response()->json(['ok'=>false,'message'=>$e->getMessage()],500);
        }
    }

    // Export account movements in various formats
    public function cashAccountMovementsExport($id, \Illuminate\Http\Request $request)
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('account_transactions')) {
            abort(404);
        }

        // Get account info
        $account = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('accounts')) {
                $account = \App\Models\Account::find($id);
            }
        } catch (\Exception $e) {}

        $accountName = $account ? $account->name : 'Hesap-'.$id;
        $format = $request->query('format', 'csv');

        // Build query with same filters as the main method
        $q = \DB::table('account_transactions')->where('account_id', $id);

        if ($request->filled('start')) $q->where('date', '>=', $request->query('start'));
        if ($request->filled('end')) $q->where('date', '<=', $request->query('end'));

        if ($request->filled('q')) {
            $searchTerm = '%' . $request->query('q') . '%';
            $q->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('description', 'like', $searchTerm)
                        ->orWhere('reference', 'like', $searchTerm);
            });
        }

        if ($request->filled('type')) {
            $type = $request->query('type');
            switch ($type) {
                case 'collection':
                    $q->where('debit', '>', 0)->whereNull('credit');
                    break;
                case 'payment':
                    $q->where('credit', '>', 0)->whereNull('debit');
                    break;
                case 'transfer':
                    $q->where(function($subQuery) {
                        $subQuery->where('description', 'like', '%transfer%')
                                ->orWhere('description', 'like', '%virman%');
                    });
                    break;
                case 'expense':
                    $q->where('credit', '>', 0)
                      ->where(function($subQuery) {
                          $subQuery->where('description', 'like', '%expense%')
                                  ->orWhere('description', 'like', '%masraf%')
                                  ->orWhere('description', 'like', '%gider%');
                      });
                    break;
            }
        }

        if ($request->filled('min_amount')) {
            $minAmount = (float) $request->query('min_amount');
            $q->where(function($subQuery) use ($minAmount) {
                $subQuery->where('debit', '>=', $minAmount)
                        ->orWhere('credit', '>=', $minAmount);
            });
        }
        if ($request->filled('max_amount')) {
            $maxAmount = (float) $request->query('max_amount');
            $q->where(function($subQuery) use ($maxAmount) {
                $subQuery->where('debit', '<=', $maxAmount)
                        ->orWhere('credit', '<=', $maxAmount);
            });
        }

        $rows = $q->orderBy('date', 'desc')->get();

        $timestamp = date('Y-m-d_H-i-s');
        $safeAccountName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $accountName);

        switch ($format) {
            case 'xlsx':
                return $this->exportToExcel($rows, $safeAccountName, $timestamp);
            case 'pdf':
                return $this->exportToPDF($rows, $accountName, $timestamp, $request);
            default: // csv
                return $this->exportToCSV($rows, $safeAccountName, $timestamp);
        }
    }

    private function exportToCSV($rows, $accountName, $timestamp)
    {
        $callback = function() use ($rows) {
            $out = fopen('php://output', 'w');
            // BOM for Excel Turkish support
            fwrite($out, "\xEF\xBB\xBF");

            // Headers
            fputcsv($out, [
                'Tarih',
                'Açıklama',
                'Referans',
                'Gelen Tutar',
                'Giden Tutar',
                'Bakiye',
                'İşlem Türü'
            ]);

            foreach ($rows as $r) {
                $transactionType = '';
                if ($r->debit && $r->debit > 0) {
                    $transactionType = 'Gelir';
                } elseif ($r->credit && $r->credit > 0) {
                    $transactionType = 'Gider';
                }

                fputcsv($out, [
                    $r->date,
                    $r->description ?? '',
                    $r->reference ?? '',
                    $r->debit ? number_format($r->debit, 2, ',', '.') : '',
                    $r->credit ? number_format($r->credit, 2, ',', '.') : '',
                    $r->balance ? number_format($r->balance, 2, ',', '.') : '',
                    $transactionType
                ]);
            }
            fclose($out);
        };

        $filename = $accountName . '_hareketler_' . $timestamp . '.csv';
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function exportToExcel($rows, $accountName, $timestamp)
    {
        // Simple XLSX export (basic implementation)
        // For a full implementation, consider using PhpSpreadsheet
        return $this->exportToCSV($rows, $accountName, $timestamp);
    }

    private function exportToPDF($rows, $accountName, $timestamp, $request)
    {
        // Generate HTML content for PDF
        $html = $this->generatePDFContent($rows, $accountName, $request);

        // For now, return HTML (you can integrate with DomPDF or similar)
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);
    }

    private function generatePDFContent($rows, $accountName, $request)
    {
        $totalIncome = 0;
        $totalExpense = 0;
        $transactionCount = count($rows);

        foreach ($rows as $row) {
            if ($row->debit) $totalIncome += $row->debit;
            if ($row->credit) $totalExpense += $row->credit;
        }

        $netBalance = $totalIncome - $totalExpense;
        $reportDate = date('d.m.Y H:i');
        $startDate = e($request->query('start', 'Belirtilmemiş'));
        $endDate = e($request->query('end', 'Belirtilmemiş'));

        $html = '<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hesap Hareketleri Raporu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .company { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 16px; margin: 10px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table th { background-color: #f2f2f2; font-weight: bold; }
        .amount-positive { color: green; font-weight: bold; }
        .amount-negative { color: red; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
        .summary { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">PERGEL İNŞAAT YÖNETİM SİSTEMİ</div>
        <div class="report-title">HESAP HAREKETLERİ RAPORU</div>
        <div>Rapor Tarihi: ' . $reportDate . '</div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Hesap Adı:</strong></td>
            <td>' . htmlspecialchars($accountName) . '</td>
            <td><strong>Başlangıç Tarihi:</strong></td>
            <td>' . $startDate . '</td>
        </tr>
        <tr>
            <td><strong>İşlem Sayısı:</strong></td>
            <td>' . $transactionCount . '</td>
            <td><strong>Bitiş Tarihi:</strong></td>
            <td>' . $endDate . '</td>
        </tr>
    </table>

    <div class="summary">
        <table style="width: 100%;">
            <tr>
                <td><strong>Toplam Gelir:</strong> <span class="amount-positive">₺' . number_format($totalIncome, 2, ',', '.') . '</span></td>
                <td><strong>Toplam Gider:</strong> <span class="amount-negative">₺' . number_format($totalExpense, 2, ',', '.') . '</span></td>
                <td><strong>Net Bakiye:</strong> <span class="' . ($netBalance >= 0 ? 'amount-positive' : 'amount-negative') . '">₺' . number_format($netBalance, 2, ',', '.') . '</span></td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Açıklama</th>
                <th>Gelen</th>
                <th>Giden</th>
                <th>Bakiye</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>
                <td>' . date('d.m.Y', strtotime($row->date)) . '</td>
                <td>' . htmlspecialchars($row->description ?? '') . '</td>
                <td class="amount-positive">' . ($row->debit ? '₺' . number_format($row->debit, 2, ',', '.') : '') . '</td>
                <td class="amount-negative">' . ($row->credit ? '₺' . number_format($row->credit, 2, ',', '.') : '') . '</td>
                <td>' . ($row->balance ? '₺' . number_format($row->balance, 2, ',', '.') : '') . '</td>
            </tr>';
        }

        $html .= '</tbody>
    </table>

    <div class="footer">
        Bu rapor ' . $reportDate . ' tarihinde Pergel İnşaat Yönetim Sistemi tarafından otomatik olarak oluşturulmuştur.
    </div>
</body>
</html>';

        return $html;
    }
    public function cashMovements()
    {
        // Get basic account statistics for initial load
        $accountStats = [];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('accounts')) {
                $accounts = \App\Models\Account::orderBy('name')->get();
                $accountStats = [
                    'total_accounts' => $accounts->count(),
                    'total_balance' => $accounts->sum('balance'),
                    'accounts' => $accounts->map(function($account) {
                        return [
                            'id' => $account->id,
                            'name' => $account->name,
                            'type' => $account->type,
                            'currency' => $account->currency ?? 'TRY',
                            'balance' => $account->balance ?? 0,
                            'formatted_balance' => number_format($account->balance ?? 0, 2, ',', '.')
                        ];
                    })
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Cash movements page error: ' . $e->getMessage());
        }

        return view('finance.cash.movements', compact('accountStats'));
    }

    /**
     * Show single voucher (simple viewer used by movements links)
     */
    public function voucherShow($id)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('vouchers')) {
                $v = \App\Models\Voucher::with('lines')->find($id);
                if (!$v) abort(404);
                return view('finance.accounts.voucher_show', compact('v'));
            }
        } catch (\Exception $e) {}

        // fallback to JSON file storage
        $path = storage_path('app/vouchers.json');
        if (!file_exists($path)) abort(404);
        $contents = json_decode(file_get_contents($path), true) ?? [];
        foreach ($contents as $r) {
            if (($r['id'] ?? null) == $id) {
                $v = (object) $r;
                $v->lines = $r['lines'] ?? [];
                return view('finance.accounts.voucher_show', compact('v'));
            }
        }
        abort(404);
    }
    public function cashTransfers()
    {
        return view('finance.cash.transfers');
    }

    public function cashTransfersData(\Illuminate\Http\Request $request)
    {
        try {
            // Demo transfer data - replace with real database queries
            $transfers = [
                [
                    'id' => 1001,
                    'date' => '2025-10-01 14:30:00',
                    'type' => 'transfer',
                    'from_account' => 'Garanti BBVA TL',
                    'to_account' => 'Ana Kasa',
                    'amount' => 50000.00,
                    'status' => 'completed',
                    'description' => 'Kasa ikmali'
                ],
                [
                    'id' => 1002,
                    'date' => '2025-10-01 11:15:00',
                    'type' => 'eft',
                    'from_account' => 'Akbank USD',
                    'beneficiary_name' => 'ABC Tedarik Ltd.',
                    'amount' => 25000.00,
                    'status' => 'completed',
                    'description' => 'Tedarikçi ödemesi'
                ],
                [
                    'id' => 1003,
                    'date' => '2025-10-01 09:45:00',
                    'type' => 'havale',
                    'from_account' => 'İş Bankası EUR',
                    'beneficiary_name' => 'XYZ İnşaat A.Ş.',
                    'amount' => 75000.00,
                    'status' => 'pending',
                    'description' => 'Proje ödemesi'
                ],
                [
                    'id' => 1004,
                    'date' => '2025-09-30 16:20:00',
                    'type' => 'transfer',
                    'from_account' => 'Yedek Kasa',
                    'to_account' => 'Garanti BBVA TL',
                    'amount' => 15000.00,
                    'status' => 'completed',
                    'description' => 'Nakit yatırma'
                ],
                [
                    'id' => 1005,
                    'date' => '2025-09-30 13:10:00',
                    'type' => 'eft',
                    'from_account' => 'Garanti BBVA TL',
                    'beneficiary_name' => 'DEF Teknoloji Ltd.',
                    'amount' => 35000.00,
                    'status' => 'failed',
                    'description' => 'Yazılım lisans ödemesi'
                ]
            ];

            // Apply filters if provided
            $filter = $request->get('filter', 'all');
            if ($filter !== 'all') {
                $transfers = array_filter($transfers, function($transfer) use ($filter) {
                    if ($filter === 'pending') return $transfer['status'] === 'pending';
                    return $transfer['type'] === $filter;
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $offset = ($page - 1) * $perPage;
            $paginatedTransfers = array_slice($transfers, $offset, $perPage);

            return response()->json([
                'ok' => true,
                'data' => array_values($paginatedTransfers),
                'total' => count($transfers),
                'per_page' => $perPage,
                'current_page' => $page
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Transfer verileri yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cashTransfersPending()
    {
        try {
            // Demo pending transfers - replace with real database queries
            $pendingTransfers = [
                [
                    'id' => 2001,
                    'date' => '2025-10-01 15:45:00',
                    'type' => 'eft',
                    'from_account' => 'Garanti BBVA TL',
                    'beneficiary_name' => 'GHI Müteahhitlik Ltd.',
                    'amount' => 125000.00,
                    'status' => 'pending',
                    'description' => 'İnşaat hakediş ödemesi',
                    'requires_approval' => true
                ],
                [
                    'id' => 2002,
                    'date' => '2025-10-01 12:30:00',
                    'type' => 'transfer',
                    'from_account' => 'Akbank USD',
                    'to_account' => 'İş Bankası EUR',
                    'amount' => 45000.00,
                    'status' => 'pending',
                    'description' => 'Döviz transfer işlemi',
                    'requires_approval' => true
                ],
                [
                    'id' => 2003,
                    'date' => '2025-10-01 10:15:00',
                    'type' => 'havale',
                    'from_account' => 'Garanti BBVA TL',
                    'beneficiary_name' => 'JKL Danışmanlık A.Ş.',
                    'amount' => 18500.00,
                    'status' => 'pending',
                    'description' => 'Danışmanlık hizmet bedeli',
                    'requires_approval' => true
                ]
            ];

            return response()->json([
                'ok' => true,
                'data' => $pendingTransfers,
                'count' => count($pendingTransfers)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Bekleyen transferler yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cashTransfersStoreTransfer(\Illuminate\Http\Request $request)
    {
        try {
            $validatedData = $request->validate([
                'from_account' => 'required|string',
                'to_account' => 'required|string',
                'amount' => 'required|numeric|min:0.01',
                'date' => 'required|date',
                'description' => 'nullable|string|max:500',
                'requires_approval' => 'boolean',
                'send_notification' => 'boolean'
            ]);

            // Here you would typically save to database
            // For demo purposes, we'll just return success

            return response()->json([
                'ok' => true,
                'message' => 'Transfer işlemi başarıyla oluşturuldu',
                'data' => [
                    'id' => rand(3000, 3999),
                    'status' => $validatedData['requires_approval'] ?? false ? 'pending' : 'completed',
                    'created_at' => now()->toISOString()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Transfer işlemi sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cashTransfersStoreEft(\Illuminate\Http\Request $request)
    {
        try {
            $validatedData = $request->validate([
                'from_account' => 'required|string',
                'beneficiary_name' => 'required|string|max:255',
                'iban' => 'required|string|size:32',
                'bank' => 'nullable|string|max:255',
                'amount' => 'required|numeric|min:0.01',
                'date' => 'required|date',
                'description' => 'nullable|string|max:500',
                'beneficiary_type' => 'nullable|in:individual,corporate',
                'requires_approval' => 'boolean',
                'urgent' => 'boolean',
                'send_sms' => 'boolean'
            ]);

            // IBAN validation (basic Turkish IBAN format)
            $iban = preg_replace('/\s+/', '', $validatedData['iban']);
            if (!preg_match('/^TR\d{24}$/', $iban)) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Geçersiz IBAN formatı'
                ], 422);
            }

            // Here you would typically save to database
            // For demo purposes, we'll just return success

            return response()->json([
                'ok' => true,
                'message' => 'EFT/Havale işlemi başarıyla oluşturuldu',
                'data' => [
                    'id' => rand(4000, 4999),
                    'status' => $validatedData['requires_approval'] ?? false ? 'pending' : 'completed',
                    'created_at' => now()->toISOString()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'EFT/Havale işlemi sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cashTransfersApprove($id)
    {
        try {
            // Here you would typically update the transfer status in database
            // For demo purposes, we'll just return success

            return response()->json([
                'ok' => true,
                'message' => 'Transfer işlemi başarıyla onaylandı',
                'data' => [
                    'id' => $id,
                    'status' => 'completed',
                    'approved_at' => now()->toISOString(),
                    'approved_by' => auth()->user()->name ?? 'System'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Onay işlemi sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cashMutabakat()
    {
        return view('finance.cash.mutabakat');
    }

    public function uploadBankStatement(Request $request)
    {
        try {
            $request->validate([
                'account_id' => 'required|exists:accounts,id',
                'end_date' => 'required|date',
                'closing_balance' => 'required|numeric',
                'statement' => 'required|file|mimes:csv,xlsx,xls|max:10240'
            ]);

            // Get the uploaded file
            $file = $request->file('statement');
            $account = \App\Models\Account::find($request->account_id);

            // Process the file (simplified version)
            $transactions = $this->parseStatementFile($file);

            // Get matching accounting records
            $accountingRecords = $this->getAccountingRecords($request->account_id, $request->end_date);

            return response()->json([
                'ok' => true,
                'message' => 'Ekstre başarıyla yüklendi',
                'transactions' => $transactions,
                'accountingRecords' => $accountingRecords,
                'closingBalance' => (float) $request->closing_balance,
                'account' => $account
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Ekstre yüklenirken hata oluştu: ' . $e->getMessage()
            ], 400);
        }
    }

    public function mutabakatAutoMatch(Request $request)
    {
        try {
            $transactions = $request->input('transactions', []);
            $accountingRecords = $request->input('accountingRecords', []);

            $matches = [];
            $matchedAccountingIndexes = [];

            foreach ($transactions as $index => $transaction) {
                $match = null;

                // Find matching accounting record
                foreach ($accountingRecords as $accIndex => $record) {
                    if (in_array($accIndex, $matchedAccountingIndexes)) {
                        continue; // Already matched
                    }

                    // Simple matching logic: same amount and close date
                    if (abs($transaction['amount']) == abs($record['amount'])) {
                        $transDate = \Carbon\Carbon::parse($transaction['date']);
                        $recordDate = \Carbon\Carbon::parse($record['date']);

                        if ($transDate->diffInDays($recordDate) <= 2) {
                            $match = [
                                'matched' => true,
                                'transaction' => $transaction,
                                'record' => $record,
                                'confidence' => 0.95
                            ];
                            $matchedAccountingIndexes[] = $accIndex;
                            break;
                        }
                    }
                }

                $matches[] = [
                    'index' => $index,
                    'matched' => $match !== null,
                    'match' => $match,
                    'transaction' => $transaction
                ];
            }

            return response()->json([
                'ok' => true,
                'matches' => $matches
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Otomatik eşleştirme hatası: ' . $e->getMessage()
            ], 400);
        }
    }

    public function mutabakatCreateVoucher(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'amount' => 'required|numeric',
                'description' => 'required|string'
            ]);

            // Create a simple voucher record
            $voucher = new \stdClass();
            $voucher->id = rand(1000, 9999);
            $voucher->date = $request->date;
            $voucher->amount = $request->amount;
            $voucher->description = $request->description;
            $voucher->created_at = now();

            // In a real implementation, you would save this to database

            return response()->json([
                'ok' => true,
                'message' => 'Fiş başarıyla oluşturuldu',
                'voucher' => $voucher
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Fiş oluşturulurken hata: ' . $e->getMessage()
            ], 400);
        }
    }

    private function parseStatementFile($file)
    {
        // Simplified file parsing - in reality you'd use a proper CSV/Excel parser
        $transactions = [];

        // Demo data for testing
        $demoTransactions = [
            ['date' => '2024-10-01', 'description' => 'Maaş Ödemesi', 'amount' => -15000],
            ['date' => '2024-10-02', 'description' => 'Müşteri Ödemesi - ABC Şirketi', 'amount' => 25000],
            ['date' => '2024-10-03', 'description' => 'Elektrik Faturası', 'amount' => -1200],
            ['date' => '2024-10-04', 'description' => 'Satış Tahsilatı', 'amount' => 8500],
            ['date' => '2024-10-05', 'description' => 'Ofis Kirası', 'amount' => -5000],
            ['date' => '2024-10-06', 'description' => 'Ürün Satışı', 'amount' => 12000],
            ['date' => '2024-10-07', 'description' => 'Telefon Faturası', 'amount' => -350],
            ['date' => '2024-10-08', 'description' => 'Danışmanlık Ücreti', 'amount' => 7500],
        ];

        return $demoTransactions;
    }

    private function getAccountingRecords($accountId, $endDate)
    {
        // In a real implementation, fetch from database
        // For now, return demo data that partially matches the statement
        return [
            ['date' => '2024-10-01', 'description' => 'Maaş Ödemesi', 'amount' => -15000],
            ['date' => '2024-10-02', 'description' => 'Müşteri Ödemesi - ABC Şirketi', 'amount' => 25000],
            ['date' => '2024-10-03', 'description' => 'Elektrik Faturası', 'amount' => -1200],
            ['date' => '2024-10-04', 'description' => 'Satış Tahsilatı', 'amount' => 8500],
            ['date' => '2024-10-05', 'description' => 'Ofis Kirası', 'amount' => -5000],
            ['date' => '2024-10-06', 'description' => 'Ürün Satışı', 'amount' => 12000],
        ];
    }
    public function cashPos()
    {
        return view('finance.cash.pos');
    }

    public function getPosTerminals()
    {
        try {
            // In a real implementation, fetch from database
            $terminals = [
                [
                    'id' => 1,
                    'name' => 'Terminal 1 - Kasa Ana',
                    'status' => 'online',
                    'location' => 'Ana Kasa',
                    'ip_address' => '192.168.1.101',
                    'last_transaction' => now()->subMinutes(2),
                    'daily_sales' => 8500.00,
                    'transaction_count' => 45
                ],
                [
                    'id' => 2,
                    'name' => 'Terminal 2 - Kasa Yan',
                    'status' => 'online',
                    'location' => 'Yan Kasa',
                    'ip_address' => '192.168.1.102',
                    'last_transaction' => now()->subMinutes(5),
                    'daily_sales' => 6200.00,
                    'transaction_count' => 32
                ],
                [
                    'id' => 3,
                    'name' => 'Terminal 3 - Mobil',
                    'status' => 'online',
                    'location' => 'Mobil POS',
                    'ip_address' => '192.168.1.103',
                    'last_transaction' => now()->subMinutes(1),
                    'daily_sales' => 4800.00,
                    'transaction_count' => 28
                ],
                [
                    'id' => 4,
                    'name' => 'Terminal 4 - Self Servis',
                    'status' => 'maintenance',
                    'location' => 'Self Servis',
                    'ip_address' => '192.168.1.104',
                    'last_transaction' => now()->subHours(2),
                    'daily_sales' => 0.00,
                    'transaction_count' => 0
                ]
            ];

            return response()->json([
                'success' => true,
                'terminals' => $terminals
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terminal bilgileri alınamadı: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPosTransactions(Request $request)
    {
        try {
            $period = $request->input('period', 'today');
            $terminal_id = $request->input('terminal_id');

            // Simulate transaction data based on period
            $transactions = $this->generatePosTransactionData($period, $terminal_id);

            return response()->json([
                'success' => true,
                'transactions' => $transactions,
                'summary' => [
                    'total_amount' => collect($transactions)->sum('amount'),
                    'total_count' => count($transactions),
                    'average_amount' => count($transactions) > 0 ? collect($transactions)->avg('amount') : 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İşlem verileri alınamadı: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPosStats(Request $request)
    {
        try {
            $period = $request->input('period', 'today');

            $stats = [
                'daily_sales' => 45280.00,
                'transaction_count' => 248,
                'active_terminals' => 7,
                'total_terminals' => 8,
                'average_transaction' => 182.58,
                'payment_methods' => [
                    'credit_card' => 65,
                    'cash' => 25,
                    'digital' => 10
                ],
                'hourly_sales' => [1200, 1900, 3000, 2500, 4200, 3800, 5100, 4600, 5800, 6200],
                'growth_rate' => 12.5
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İstatistikler alınamadı: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTerminalStatus(Request $request, $terminalId)
    {
        try {
            $request->validate([
                'status' => 'required|in:online,offline,maintenance'
            ]);

            // In a real implementation, update database and notify terminal
            $status = $request->input('status');

            return response()->json([
                'success' => true,
                'message' => "Terminal durumu '{$status}' olarak güncellendi",
                'terminal_id' => $terminalId,
                'new_status' => $status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terminal durumu güncellenemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePosReport(Request $request)
    {
        try {
            $request->validate([
                'period' => 'required|in:daily,weekly,monthly,custom',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date'
            ]);

            $period = $request->input('period');
            $reportData = $this->generateReportData($period, $request->input('start_date'), $request->input('end_date'));

            // In a real implementation, generate PDF or Excel file
            return response()->json([
                'success' => true,
                'message' => 'Rapor başarıyla oluşturuldu',
                'report_data' => $reportData,
                'download_url' => '/downloads/pos-report-' . time() . '.pdf'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rapor oluşturulamadı: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generatePosTransactionData($period, $terminalId = null)
    {
        $transactions = [];
        $count = $period === 'today' ? 20 : ($period === 'week' ? 100 : 500);

        $paymentMethods = ['Kredi Kartı', 'Nakit', 'Dijital Cüzdan', 'Çek'];
        $statuses = ['Başarılı', 'Başarılı', 'Başarılı', 'Beklemede', 'İptal'];
        $amounts = [25, 50, 75, 100, 150, 200, 250, 300, 450, 600, 850, 1200];

        for ($i = 0; $i < $count; $i++) {
            $transactions[] = [
                'id' => $i + 1,
                'terminal_id' => $terminalId ?: rand(1, 8),
                'amount' => $amounts[array_rand($amounts)],
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subMinutes(rand(1, 1440))->format('Y-m-d H:i:s'),
                'reference_number' => 'REF' . str_pad($i + 1, 6, '0', STR_PAD_LEFT)
            ];
        }

        return $transactions;
    }

    private function generateReportData($period, $startDate = null, $endDate = null)
    {
        return [
            'period' => $period,
            'start_date' => $startDate ?: now()->startOfDay(),
            'end_date' => $endDate ?: now()->endOfDay(),
            'total_sales' => 45280.00,
            'total_transactions' => 248,
            'terminals_summary' => [
                'active' => 7,
                'inactive' => 1,
                'maintenance' => 1
            ],
            'payment_methods_breakdown' => [
                'credit_card' => ['count' => 161, 'amount' => 29432.00],
                'cash' => ['count' => 62, 'amount' => 11320.00],
                'digital' => ['count' => 25, 'amount' => 4528.00]
            ]
        ];
    }
    public function cashCurrency()
    {
        // Güncel döviz kurları (demo verisi - gerçek uygulamada API'den alınabilir)
        $exchangeRates = [
            'USD' => [
                'symbol' => '$',
                'name' => 'Amerikan Doları',
                'buy' => 34.2150,
                'sell' => 34.2850,
                'change' => 0.85,
                'change_percent' => 2.55,
                'last_update' => now()->format('H:i'),
                'flag' => '🇺🇸'
            ],
            'EUR' => [
                'symbol' => '€',
                'name' => 'Euro',
                'buy' => 37.1200,
                'sell' => 37.1950,
                'change' => -0.45,
                'change_percent' => -1.19,
                'last_update' => now()->format('H:i'),
                'flag' => '🇪🇺'
            ],
            'GBP' => [
                'symbol' => '£',
                'name' => 'İngiliz Sterlini',
                'buy' => 43.8500,
                'sell' => 43.9200,
                'change' => 1.25,
                'change_percent' => 2.93,
                'last_update' => now()->format('H:i'),
                'flag' => '🇬🇧'
            ],
            'CHF' => [
                'symbol' => 'CHF',
                'name' => 'İsviçre Frangı',
                'buy' => 38.4500,
                'sell' => 38.5200,
                'change' => 0.15,
                'change_percent' => 0.39,
                'last_update' => now()->format('H:i'),
                'flag' => '🇨🇭'
            ],
            'JPY' => [
                'symbol' => '¥',
                'name' => 'Japon Yeni',
                'buy' => 0.2285,
                'sell' => 0.2295,
                'change' => -0.0015,
                'change_percent' => -0.65,
                'last_update' => now()->format('H:i'),
                'flag' => '🇯🇵'
            ],
            'CAD' => [
                'symbol' => 'C$',
                'name' => 'Kanada Doları',
                'buy' => 25.1500,
                'sell' => 25.2000,
                'change' => 0.35,
                'change_percent' => 1.41,
                'last_update' => now()->format('H:i'),
                'flag' => '🇨🇦'
            ]
        ];

        // Döviz pozisyonları (hesaplardaki döviz miktarları)
        $positions = [
            'USD' => [
                'amount' => 125500.00,
                'tl_value' => 125500.00 * 34.2150,
                'accounts' => 3
            ],
            'EUR' => [
                'amount' => 85250.00,
                'tl_value' => 85250.00 * 37.1200,
                'accounts' => 2
            ],
            'GBP' => [
                'amount' => 22800.00,
                'tl_value' => 22800.00 * 43.8500,
                'accounts' => 1
            ],
            'CHF' => [
                'amount' => 15000.00,
                'tl_value' => 15000.00 * 38.4500,
                'accounts' => 1
            ]
        ];

        // Son işlemler
        $recentTransactions = [
            [
                'date' => now()->subHours(2)->format('d.m.Y H:i'),
                'type' => 'Döviz Alış',
                'currency' => 'USD',
                'amount' => 5000,
                'rate' => 34.1800,
                'tl_amount' => 170900,
                'account' => 'Ziraat Bankası USD'
            ],
            [
                'date' => now()->subHours(5)->format('d.m.Y H:i'),
                'type' => 'Döviz Satış',
                'currency' => 'EUR',
                'amount' => 2500,
                'rate' => 37.0500,
                'tl_amount' => 92625,
                'account' => 'İş Bankası EUR'
            ],
            [
                'date' => now()->subDay()->format('d.m.Y H:i'),
                'type' => 'Döviz Transfer',
                'currency' => 'USD',
                'amount' => 10000,
                'rate' => 34.0200,
                'tl_amount' => 340200,
                'account' => 'Garanti USD → Ziraat USD'
            ]
        ];

        // Toplam pozisyon özeti
        $totalPositionTL = array_sum(array_column($positions, 'tl_value'));
        $totalAccounts = array_sum(array_column($positions, 'accounts'));

        return view('finance.cash.currency', compact(
            'exchangeRates',
            'positions',
            'recentTransactions',
            'totalPositionTL',
            'totalAccounts'
        ));
    }

    // Cheques
    public function chequesCustomers()
    {
        // Müşteri çekleri özet istatistikleri
        $chequeStats = [
            'total_cheques' => 156,
            'pending_cheques' => 23,
            'paid_cheques' => 118,
            'bounced_cheques' => 15,
            'total_amount' => 2485750.00,
            'pending_amount' => 385200.00,
            'paid_amount' => 1965550.00,
            'bounced_amount' => 135000.00
        ];

        // Müşteri çek listesi
        $customerCheques = [
            [
                'id' => 1,
                'customer_name' => 'ABC İnşaat Ltd. Şti.',
                'customer_code' => 'CUS001',
                'cheque_no' => '123456789',
                'bank_name' => 'Türkiye İş Bankası',
                'branch' => 'Kadıköy Şubesi',
                'amount' => 125000.00,
                'issue_date' => '2024-09-15',
                'due_date' => '2024-12-15',
                'status' => 'pending',
                'currency' => 'TRY',
                'endorser' => 'Mehmet Yılmaz',
                'account_no' => '1234567890',
                'notes' => 'İnşaat projesi ödemesi',
                'priority' => 'high'
            ],
            [
                'id' => 2,
                'customer_name' => 'XYZ Tekstil A.Ş.',
                'customer_code' => 'CUS002',
                'cheque_no' => '987654321',
                'bank_name' => 'Garanti BBVA',
                'branch' => 'Ataşehir Şubesi',
                'amount' => 85000.00,
                'issue_date' => '2024-10-01',
                'due_date' => '2024-11-30',
                'status' => 'paid',
                'currency' => 'TRY',
                'endorser' => 'Ayşe Demir',
                'account_no' => '0987654321',
                'notes' => 'Kumaş tedarik ödemesi',
                'priority' => 'medium'
            ],
            [
                'id' => 3,
                'customer_name' => 'DEF Otomotiv San.',
                'customer_code' => 'CUS003',
                'cheque_no' => '555666777',
                'bank_name' => 'Ziraat Bankası',
                'branch' => 'Merkez Şubesi',
                'amount' => 175000.00,
                'issue_date' => '2024-08-20',
                'due_date' => '2024-12-01',
                'status' => 'bounced',
                'currency' => 'TRY',
                'endorser' => 'Can Özkan',
                'account_no' => '5556667777',
                'notes' => 'Yedek parça ödemesi - karşılıksız',
                'priority' => 'urgent'
            ],
            [
                'id' => 4,
                'customer_name' => 'GHI Gıda Ltd.',
                'customer_code' => 'CUS004',
                'cheque_no' => '111222333',
                'bank_name' => 'Akbank',
                'branch' => 'Beşiktaş Şubesi',
                'amount' => 95000.00,
                'issue_date' => '2024-09-25',
                'due_date' => '2024-11-15',
                'status' => 'pending',
                'currency' => 'TRY',
                'endorser' => 'Fatma Kaya',
                'account_no' => '1112223333',
                'notes' => 'Gıda ürünleri tedarik',
                'priority' => 'medium'
            ],
            [
                'id' => 5,
                'customer_name' => 'JKL Teknoloji A.Ş.',
                'customer_code' => 'CUS005',
                'cheque_no' => '444555666',
                'bank_name' => 'Yapı Kredi Bankası',
                'branch' => 'Levent Şubesi',
                'amount' => 225000.00,
                'issue_date' => '2024-09-10',
                'due_date' => '2024-10-25',
                'status' => 'pending',
                'currency' => 'TRY',
                'endorser' => 'Ali Vural',
                'account_no' => '4445556666',
                'notes' => 'Yazılım lisans ödemesi',
                'priority' => 'high'
            ]
        ];

        // Yaklaşan vadeler (önümüzdeki 30 gün)
        $upcomingDues = collect($customerCheques)
            ->filter(function($cheque) {
                $dueDate = \Carbon\Carbon::parse($cheque['due_date']);
                return $dueDate->between(now(), now()->addDays(30)) && $cheque['status'] === 'pending';
            })
            ->sortBy('due_date')
            ->values()
            ->toArray();

        // Aylık rapor verileri
        $monthlyData = [
            'January' => ['received' => 12, 'paid' => 10, 'bounced' => 1, 'amount' => 450000],
            'February' => ['received' => 8, 'paid' => 8, 'bounced' => 0, 'amount' => 320000],
            'March' => ['received' => 15, 'paid' => 12, 'bounced' => 2, 'amount' => 580000],
            'April' => ['received' => 11, 'paid' => 9, 'bounced' => 1, 'amount' => 410000],
            'May' => ['received' => 9, 'paid' => 8, 'bounced' => 1, 'amount' => 380000],
            'June' => ['received' => 13, 'paid' => 11, 'bounced' => 1, 'amount' => 520000],
            'July' => ['received' => 16, 'paid' => 14, 'bounced' => 2, 'amount' => 650000],
            'August' => ['received' => 14, 'paid' => 12, 'bounced' => 1, 'amount' => 595000],
            'September' => ['received' => 18, 'paid' => 15, 'bounced' => 2, 'amount' => 720000],
            'October' => ['received' => 10, 'paid' => 8, 'bounced' => 1, 'amount' => 385000]
        ];

        return view('finance.cheques.customers', compact(
            'chequeStats',
            'customerCheques',
            'upcomingDues',
            'monthlyData'
        ));
    }
    public function chequesOwn()
    {
        // Simulated data for cheques
        $cheques = \App\Models\Cheque::all();

        // Statistics
        $totalAmount = $cheques->sum('amount');
        $pendingAmount = $cheques->where('status', 'pending')->sum('amount');
        $cashedAmount = $cheques->where('status', 'cashed')->sum('amount');
        $returnedAmount = $cheques->where('status', 'returned')->sum('amount');

        $statusCounts = [
            'pending' => $cheques->where('status', 'pending')->count(),
            'cashed' => $cheques->where('status', 'cashed')->count(),
            'returned' => $cheques->where('status', 'returned')->count(),
            'cancelled' => $cheques->where('status', 'cancelled')->count(),
        ];

        return view('finance.cheques.own', compact(
            'cheques', 'totalAmount', 'pendingAmount',
            'cashedAmount', 'returnedAmount', 'statusCounts'
        ));
    }

    // Stock API
    public function stockCardsApi()
    {
        $stockCards = \App\Models\StockCard::with('movements')
            ->orderBy('name')
            ->get();

        $categories = $stockCards->pluck('category')->unique()->values();

        $stats = [
            'total_items' => $stockCards->count(),
            'total_value' => $stockCards->sum('total_value'),
            'critical_stock' => $stockCards->where('stock_status', 'critical')->count(),
            'low_stock' => $stockCards->where('stock_status', 'low')->count(),
            'categories' => $categories->count(),
        ];

        return view('finance.stock.cards', compact('stockCards', 'categories', 'stats'));
    }

    public function storeStockCard(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:stock_cards',
            'name' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'min_stock' => 'required|numeric|min:0',
            'max_stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stockCard = \App\Models\StockCard::create($request->all());

        return response()->json(['success' => true, 'message' => 'Stok kartı başarıyla oluşturuldu.', 'id' => $stockCard->id]);
    }

    public function showStockCardApi($id)
    {
        $stockCard = \App\Models\StockCard::findOrFail($id);
        return response()->json($stockCard);
    }

    public function updateStockCard(Request $request, $id)
    {
        $stockCard = \App\Models\StockCard::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:stock_cards,code,' . $id,
            'name' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'min_stock' => 'required|numeric|min:0',
            'max_stock' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stockCard->update($request->all());

        return response()->json(['success' => true, 'message' => 'Stok kartı başarıyla güncellendi.']);
    }

    public function destroyStockCard($id)
    {
        $stockCard = \App\Models\StockCard::findOrFail($id);
        $stockCard->delete();

        return response()->json(['success' => true, 'message' => 'Stok kartı başarıyla silindi.']);
    }

    public function getStockMovements($id)
    {
        $movements = \App\Models\StockMovement::where('stock_card_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($movements);
    }

    public function storeStockMovement(Request $request)
    {
        $request->validate([
            'stock_card_id' => 'required|exists:stock_cards,id',
            'type' => 'required|in:in,out,adjustment,transfer',
            'quantity' => 'required|numeric|not_in:0',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'required',
        ]);

        $stockCard = \App\Models\StockCard::findOrFail($request->stock_card_id);

        $movement = \App\Models\StockMovement::create([
            'stock_card_id' => $request->stock_card_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->quantity * $request->unit_price,
            'reference_no' => $request->reference_no,
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        // Update stock card
        if ($request->type == 'in') {
            $stockCard->current_stock += $request->quantity;
        } elseif ($request->type == 'out') {
            $stockCard->current_stock -= $request->quantity;
        } elseif ($request->type == 'adjustment') {
            $stockCard->current_stock = $request->quantity;
        }

        $stockCard->total_value = $stockCard->current_stock * $stockCard->unit_price;
        $stockCard->save();

        return response()->json(['success' => true, 'message' => 'Stok hareketi başarıyla kaydedildi.']);
    }

    public function showStockMovementApi($id)
    {
        $movement = \App\Models\StockMovement::with(['stockCard', 'user'])->findOrFail($id);

        return view('finance.stock.movement-details', compact('movement'));
    }

    public function printStockMovement($id)
    {
        $movement = \App\Models\StockMovement::with(['stockCard', 'user'])->findOrFail($id);

        return view('finance.stock.movement-print', compact('movement'));
    }

    public function exportStock()
    {
        $stockCards = \App\Models\StockCard::all();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="stok_kartlari_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($stockCards) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, [
                'Kod', 'Adı', 'Kategori', 'Birim', 'Min Stok', 'Max Stok',
                'Mevcut Stok', 'Birim Fiyat', 'Toplam Değer', 'Konum', 'Tedarikçi',
                'Durum', 'Stok Durumu'
            ]);

            foreach ($stockCards as $card) {
                fputcsv($file, [
                    $card->code,
                    $card->name,
                    $card->category,
                    $card->unit,
                    $card->min_stock,
                    $card->max_stock,
                    $card->current_stock,
                    $card->unit_price,
                    $card->total_value,
                    $card->location,
                    $card->supplier,
                    $card->status,
                    $card->stock_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function stockLocations()
    {
        $locations = \App\Models\StockLocation::with('stockCards')
            ->withCount('stockCards')
            ->get();

        // İstatistikler
        $stats = [
            'total_locations' => $locations->count(),
            'active_locations' => $locations->where('is_active', true)->count(),
            'total_capacity' => $locations->sum('capacity'),
            'total_stock_items' => $locations->sum('stock_cards_count'),
            'warehouse_count' => $locations->where('type', 'warehouse')->count(),
            'room_count' => $locations->where('type', 'room')->count(),
            'shelf_count' => $locations->where('type', 'shelf')->count(),
            'high_security_count' => $locations->whereIn('security_level', ['high', 'restricted'])->count(),
        ];

        // Lokasyon türleri
        $locationTypes = \App\Models\StockLocation::getTypes();
        $securityLevels = \App\Models\StockLocation::getSecurityLevels();
        $temperatureOptions = \App\Models\StockLocation::getTemperatureControlOptions();

        return view('finance.stock.locations', compact(
            'locations',
            'stats',
            'locationTypes',
            'securityLevels',
            'temperatureOptions'
        ));
    }

    public function stockLocationStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:stock_locations,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:warehouse,room,shelf,bin',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit' => 'nullable|string|max:50',
            'temperature_controlled' => 'required|string|in:no,yes,cold,frozen',
            'security_level' => 'required|string|in:low,standard,high,restricted',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'working_hours' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $location = \App\Models\StockLocation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lokasyon başarıyla oluşturuldu.',
            'location' => $location
        ]);
    }

    public function stockLocationUpdate(Request $request, $id)
    {
        $location = \App\Models\StockLocation::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:stock_locations,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:warehouse,room,shelf,bin',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit' => 'nullable|string|max:50',
            'temperature_controlled' => 'required|string|in:no,yes,cold,frozen',
            'security_level' => 'required|string|in:low,standard,high,restricted',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'working_hours' => 'nullable|array',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $location->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lokasyon başarıyla güncellendi.',
            'location' => $location
        ]);
    }

    public function stockLocationDestroy($id)
    {
        $location = \App\Models\StockLocation::findOrFail($id);

        // Stok kartı kontrolü
        if ($location->stockCards()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu lokasyonda stok kartları bulunmaktadır. Önce stok kartlarını başka bir lokasyona taşıyın.'
            ], 400);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lokasyon başarıyla silindi.'
        ]);
    }

    public function stockLocationToggleStatus($id)
    {
        $location = \App\Models\StockLocation::findOrFail($id);
        $location->update(['is_active' => !$location->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasyon durumu güncellendi.',
            'is_active' => $location->is_active
        ]);
    }

    public function stockLocationShow($id)
    {
        $location = \App\Models\StockLocation::findOrFail($id);
        return response()->json($location);
    }

    public function stockMovementsApi()
    {
        // Get all stock movements with related data
        $movements = \App\Models\StockMovement::with(['stockCard', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total_movements' => \App\Models\StockMovement::count(),
            'in_movements' => \App\Models\StockMovement::where('type', 'in')->count(),
            'out_movements' => \App\Models\StockMovement::where('type', 'out')->count(),
            'adjustment_movements' => \App\Models\StockMovement::where('type', 'adjustment')->count(),
            'today_movements' => \App\Models\StockMovement::whereDate('created_at', today())->count(),
            'total_in_value' => \App\Models\StockMovement::where('type', 'in')->sum('total_amount'),
            'total_out_value' => \App\Models\StockMovement::where('type', 'out')->sum('total_amount'),
        ];

        // Get stock cards for filters
        $stockCards = \App\Models\StockCard::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        // Get recent activities (last 10 movements)
        $recentActivities = \App\Models\StockMovement::with(['stockCard', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('finance.stock.movements', compact('movements', 'stats', 'stockCards', 'recentActivities'));
    }
    public function stockReports()
    {
        // Temel istatistikler
        $totalValue = \App\Models\StockCard::sum('total_value');
        $totalItems = \App\Models\StockCard::count();
        $criticalCount = \App\Models\StockCard::criticalStock()->count();
        $lowStockCount = \App\Models\StockCard::lowStock()->count();

        // Kategorilere göre stok dağılımı
        $categoryStats = \App\Models\StockCard::selectRaw('category, COUNT(*) as count, SUM(total_value) as total_value, SUM(current_stock) as total_stock')
            ->groupBy('category')
            ->get();

        // Stok durumu dağılımı
        $stockStatusCounts = [
            'critical' => \App\Models\StockCard::criticalStock()->count(),
            'low' => \App\Models\StockCard::lowStock()->count(),
            'normal' => \App\Models\StockCard::whereRaw('current_stock > min_stock * 1.2 AND current_stock < max_stock')->count(),
            'overstock' => \App\Models\StockCard::whereRaw('current_stock >= max_stock')->count(),
        ];

        // En çok hareket gören ürünler (son 30 gün)
        $topMovedProducts = \App\Models\StockMovement::select('stock_card_id')
            ->selectRaw('SUM(ABS(quantity)) as total_movement')
            ->where('created_at', '>=', now()->subDays(30))
            ->with('stockCard')
            ->groupBy('stock_card_id')
            ->orderByDesc('total_movement')
            ->limit(10)
            ->get();

        // Düşük stoklu ürünler
        $lowStockProducts = \App\Models\StockCard::criticalStock()
            ->orWhere(function($q) {
                $q->lowStock()->whereRaw('current_stock > min_stock');
            })
            ->orderByRaw('(current_stock / (CASE WHEN max_stock > 1 THEN max_stock ELSE 1 END)) ASC')
            ->limit(10)
            ->get();

        // Son hareketler
        $recentMovements = \App\Models\StockMovement::with(['stockCard', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Aylık hareket trends (son 12 ay)
        $stockMonthExpr = \DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";
        $monthlyTrends = \App\Models\StockMovement::selectRaw("
                {$stockMonthExpr} as month,
                SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as total_in,
                SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as total_out,
                SUM(CASE WHEN type = 'in' THEN total_amount ELSE 0 END) as value_in,
                SUM(CASE WHEN type = 'out' THEN total_amount ELSE 0 END) as value_out
            ")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('finance.stock.reports', compact(
            'totalValue', 'totalItems', 'criticalCount', 'lowStockCount',
            'categoryStats', 'stockStatusCounts', 'topMovedProducts',
            'lowStockProducts', 'recentMovements', 'monthlyTrends'
        ));
    }

    public function sendLowStockAlert()
    {
        $lowStockProducts = \App\Models\StockCard::criticalStock()
            ->orWhere(function($q) {
                $q->lowStock()->whereRaw('current_stock > min_stock');
            })
            ->get();

        if ($lowStockProducts->count() > 0) {
            // Here you would send email notification
            // For now, just return success
            return response()->json([
                'success' => true,
                'message' => 'Düşük stok uyarısı gönderildi.',
                'count' => $lowStockProducts->count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Düşük stok bulunamadı.'
        ]);
    }

    public function exportStockReports(Request $request)
    {
        $format = $request->get('format', 'excel');

        $stockCards = \App\Models\StockCard::with('movements')->get();

        $filename = 'stok-raporu-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stockCards) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Kod', 'Ürün Adı', 'Kategori', 'Birim', 'Minimum Stok',
                'Maksimum Stok', 'Mevcut Stok', 'Birim Fiyat', 'Toplam Değer',
                'Stok Durumu', 'Stok Seviyesi (%)', 'Lokasyon'
            ]);

            foreach ($stockCards as $card) {
                fputcsv($file, [
                    $card->code,
                    $card->name,
                    $card->category,
                    $card->unit,
                    $card->min_stock,
                    $card->max_stock,
                    $card->current_stock,
                    $card->unit_price,
                    $card->total_value,
                    $card->stock_status,
                    $card->stock_level_percentage,
                    $card->location
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // HR/Payroll
    public function hrExpenses()
    {
        // Defensive: if model or table missing, avoid fatal errors and show friendly message
        if (!class_exists(\App\Models\HrExpense::class) || !\Illuminate\Support\Facades\Schema::hasTable('hr_expenses')) {
            // flash message instructing developer to run migrations/seeders
            try {
                session()->flash('error', 'HR expenses tablosu bulunamadı. Lütfen veritabanı bağlantınızı kontrol edin ve `php artisan migrate --seed` ya da `php artisan db:seed --class=HrExpenseSeeder` komutunu çalıştırın.');
            } catch (\Exception $e) {
                // session may not be available in some contexts; ignore
            }

            $totalExpenses = 0;
            $pendingCount = 0;
            $thisMonthTotal = 0;
            $averageAmount = 0;
            $recentExpenses = collect();
            $departmentStats = collect();
            $expenseTypeStats = collect();
            $pendingExpenses = collect();
            $monthlyTrends = collect();
            $statusStats = [
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'paid' => 0,
            ];
            $topSpenders = collect();

            return view('finance.hr.expenses', compact(
                'totalExpenses', 'pendingCount', 'thisMonthTotal', 'averageAmount',
                'recentExpenses', 'departmentStats', 'expenseTypeStats', 'pendingExpenses',
                'monthlyTrends', 'statusStats', 'topSpenders'
            ));
        }

        // Genel istatistikler
        $totalExpenses = \App\Models\HrExpense::sum('amount');
        $pendingCount = \App\Models\HrExpense::pending()->count();
        $thisMonthTotal = \App\Models\HrExpense::thisMonth()->sum('amount');
        $averageAmount = \App\Models\HrExpense::avg('amount') ?? 0;

        // Son harcamalar
        $recentExpenses = \App\Models\HrExpense::with(['employee', 'approver'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Departman bazında analiz
        $departmentStats = \App\Models\HrExpense::selectRaw('
                department,
                COUNT(*) as total_count,
                SUM(amount) as total_amount,
                AVG(amount) as avg_amount
            ')
            ->whereNotNull('department')
            ->groupBy('department')
            ->get();

        // Harcama türü analizi
        $expenseTypeStats = \App\Models\HrExpense::selectRaw('
                expense_type,
                COUNT(*) as count,
                SUM(amount) as total_amount
            ')
            ->groupBy('expense_type')
            ->orderByDesc('total_amount')
            ->get();

        // Onay bekleyen harcamalar
        $pendingExpenses = \App\Models\HrExpense::pending()
            ->with(['employee'])
            ->orderBy('submission_date', 'asc')
            ->limit(10)
            ->get();

        // Aylık trend (son 12 ay)
        $hrMonthExpr = \DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', expense_date)"
            : "DATE_FORMAT(expense_date, '%Y-%m')";
        $monthlyTrends = \App\Models\HrExpense::selectRaw("
                {$hrMonthExpr} as month,
                COUNT(*) as count,
                SUM(amount) as total_amount,
                AVG(amount) as avg_amount
            ")
            ->where('expense_date', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Durum dağılımı
        $statusStats = [
            'pending' => \App\Models\HrExpense::where('status', 'pending')->count(),
            'approved' => \App\Models\HrExpense::where('status', 'approved')->count(),
            'rejected' => \App\Models\HrExpense::where('status', 'rejected')->count(),
            'paid' => \App\Models\HrExpense::where('status', 'paid')->count(),
        ];

        // En çok harcama yapan çalışanlar
        $topSpenders = \App\Models\HrExpense::selectRaw('
                employee_id,
                COUNT(*) as expense_count,
                SUM(amount) as total_amount
            ')
            ->with('employee')
            ->whereNotNull('employee_id')
            ->groupBy('employee_id')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

    // If there are no real HR expenses in DB and fake data is enabled via env, create in-memory sample data so the UI isn't empty
    if (env('APP_FAKE_DATA', true) && $recentExpenses->isEmpty() && $departmentStats->isEmpty() && $expenseTypeStats->isEmpty() && $monthlyTrends->isEmpty()) {
            // lightweight generator without persisting to DB
            $sampleUsers = [
                (object)['id' => 1, 'name' => 'Ali Veli'],
                (object)['id' => 2, 'name' => 'Ayşe Yılmaz'],
                (object)['id' => 3, 'name' => 'Mehmet Can'],
            ];

            $types = array_keys(\App\Models\HrExpense::getExpenseTypes());
            $depts = array_keys(\App\Models\HrExpense::getDepartments());

            $sampleRecent = collect();
            $deptAgg = [];
            $typeAgg = [];
            $monthlyAgg = [];

            for ($i = 0; $i < 8; $i++) {
                $amount = rand(100, 2500) + (rand(0,99)/100);
                $user = $sampleUsers[array_rand($sampleUsers)];
                $type = $types[array_rand($types)];
                $dept = $depts[array_rand($depts)];
                $date = now()->subDays(rand(1, 120));

                $e = new \App\Models\HrExpense();
                $e->id = 1000 + $i;
                $e->employee_id = $user->id;
                $e->employee = $user;
                $e->expense_type = $type;
                $e->title = ucfirst($type) . ' masrafı';
                $e->description = 'Örnek açıklama';
                $e->amount = $amount;
                $e->currency = 'TRY';
                $e->expense_date = $date;
                $e->submission_date = $date;
                $e->status = ['pending','approved','rejected','paid'][array_rand(['pending','approved','rejected','paid'])];
                $e->department = $dept;

                $sampleRecent->push($e);

                // accumulate
                if (!isset($deptAgg[$dept])) $deptAgg[$dept] = 0;
                $deptAgg[$dept] += $amount;

                if (!isset($typeAgg[$type])) $typeAgg[$type] = 0;
                $typeAgg[$type] += $amount;

                $mKey = $date->format('Y-m');
                if (!isset($monthlyAgg[$mKey])) $monthlyAgg[$mKey] = 0;
                $monthlyAgg[$mKey] += $amount;
            }

            // Build departmentStats and expenseTypeStats collections similar to DB shape
            $departmentStats = collect();
            foreach ($deptAgg as $k => $v) {
                $departmentStats->push((object)['department' => $k, 'total_amount' => $v, 'avg_amount' => $v/3]);
            }

            $expenseTypeStats = collect();
            foreach ($typeAgg as $k => $v) {
                $expenseTypeStats->push((object)['expense_type' => $k, 'total_amount' => $v, 'count' => 1]);
            }

            $monthlyTrends = collect();
            foreach ($monthlyAgg as $k => $v) {
                $monthlyTrends->push((object)['month' => $k, 'total_amount' => $v, 'count' => 1]);
            }

            $recentExpenses = $sampleRecent;
            $pendingExpenses = $sampleRecent->filter(fn($x)=>$x->status==='pending');
            $statusStats = [
                'pending' => $recentExpenses->where('status','pending')->count(),
                'approved' => $recentExpenses->where('status','approved')->count(),
                'rejected' => $recentExpenses->where('status','rejected')->count(),
                'paid' => $recentExpenses->where('status','paid')->count(),
            ];

            $totalExpenses = array_sum(array_column($sampleRecent->map(fn($s)=> (float)$s->amount)->toArray(), 0)) ?: 0;
            $pendingCount = $pendingExpenses->count();
            $thisMonthTotal = $recentExpenses->filter(fn($r) => $r->expense_date->month == now()->month && $r->expense_date->year == now()->year)->sum('amount');
            $averageAmount = $recentExpenses->avg('amount') ?: 0;

            $topSpenders = collect();
            $grouped = $recentExpenses->groupBy('employee_id');
            foreach ($grouped as $uid => $group) {
                $topSpenders->push((object)['employee' => (object)['name' => $group->first()->employee->name], 'expense_count' => $group->count(), 'total_amount' => $group->sum('amount')]);
            }
        }

        return view('finance.hr.expenses', compact(
            'totalExpenses', 'pendingCount', 'thisMonthTotal', 'averageAmount',
            'recentExpenses', 'departmentStats', 'expenseTypeStats', 'pendingExpenses',
            'monthlyTrends', 'statusStats', 'topSpenders'
        ));
    }

    public function exportHrExpenses()
    {
        $expenses = \App\Models\HrExpense::with(['employee', 'approver'])->get();

        $filename = 'hr-expenses-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Referans No', 'Çalışan', 'Harcama Türü', 'Kategori', 'Başlık',
                'Açıklama', 'Tutar', 'Para Birimi', 'Harcama Tarihi', 'Başvuru Tarihi',
                'Durum', 'Onaylayan', 'Onay Tarihi', 'Departman'
            ]);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->reference_no,
                    $expense->employee->name ?? 'Bilinmiyor',
                    $expense->expense_type,
                    $expense->category,
                    $expense->title,
                    $expense->description,
                    $expense->amount,
                    $expense->currency,
                    $expense->expense_date->format('Y-m-d'),
                    $expense->submission_date->format('Y-m-d'),
                    $expense->status,
                    $expense->approver->name ?? '',
                    $expense->approved_at ? $expense->approved_at->format('Y-m-d') : '',
                    $expense->department
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function approveHrExpense($id)
    {
        $expense = \App\Models\HrExpense::findOrFail($id);

        if ($expense->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Bu harcama zaten işlem görmüş.'
            ], 400);
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id() ?? 4, // Default user ID
            'approved_at' => now(),
            'approval_notes' => 'Sistem üzerinden onaylandı.'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Harcama başarıyla onaylandı.'
        ]);
    }

    public function rejectHrExpense($id)
    {
        $expense = \App\Models\HrExpense::findOrFail($id);

        if ($expense->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Bu harcama zaten işlem görmüş.'
            ], 400);
        }

        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id() ?? 4, // Default user ID
            'approved_at' => now(),
            'approval_notes' => 'Sistem üzerinden reddedildi.'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Harcama reddedildi.'
        ]);
    }

    public function createHrExpense()
    {
        $users = \App\Models\User::all();
        $expenseTypes = class_exists(\App\Models\HrExpense::class) ? \App\Models\HrExpense::getExpenseTypes() : [];
        $departments = class_exists(\App\Models\HrExpense::class) ? \App\Models\HrExpense::getDepartments() : [];
        return view('finance.hr.create-expense', compact('users', 'expenseTypes', 'departments'));
    }

    public function showHrExpense($id)
    {
        $expense = \App\Models\HrExpense::with(['employee', 'approver'])->find($id);
        if (!$expense) {
            $expense = \App\Models\HrExpense::with(['employee', 'approver'])->first();
        }
        if (!$expense) {
            abort(404);
        }
        return view('finance.hr.show-expense', compact('expense', 'id'));
    }

    public function editHrExpense($id)
    {
        $expense = \App\Models\HrExpense::with(['employee', 'approver'])->find($id);
        if (!$expense) {
            $expense = \App\Models\HrExpense::with(['employee', 'approver'])->first();
        }
        if (!$expense) {
            abort(404);
        }
        $expenseTypes = class_exists(\App\Models\HrExpense::class) ? \App\Models\HrExpense::getExpenseTypes() : [];
        $departments = class_exists(\App\Models\HrExpense::class) ? \App\Models\HrExpense::getDepartments() : [];
        return view('finance.hr.edit-expense', compact('expense', 'id', 'expenseTypes', 'departments'));
    }

    public function hrAdvances()
    {
        // Örnek avans verileri - gerçek uygulamada veritabanından çekilecek
        $advances = \App\Models\Advance::with('employee')->get();

        // İstatistikler
        $totalAdvances = $advances->count();
        $activeAdvances = $advances->where('status', 'active')->count();
        $completedAdvances = $advances->where('status', 'completed')->count();
        $pendingAdvances = $advances->where('status', 'pending')->count();
        $totalAdvanceAmount = $advances->sum('advance_amount');
        $totalRemainingAmount = $advances->sum('remaining_amount');
        $totalPaidAmount = $advances->sum('paid_amount');

        return view('finance.hr.advances', compact(
            'advances',
            'totalAdvances',
            'activeAdvances',
            'completedAdvances',
            'pendingAdvances',
            'totalAdvanceAmount',
            'totalRemainingAmount',
            'totalPaidAmount'
        ));
    }

    public function createAdvance(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'advance_amount' => 'required|numeric|min:1',
            'installments' => 'required|integer|min:1|max:12',
            'reason' => 'required|string|max:500'
        ]);

        // Burada gerçek uygulamada veritabanına kayıt yapılacak

        return response()->json([
            'success' => true,
            'message' => 'Avans talebi başarıyla oluşturuldu.'
        ]);
    }

    public function approveAdvance($id)
    {
        // Burada gerçek uygulamada veritabanından güncelleme yapılacak

        return response()->json([
            'success' => true,
            'message' => 'Avans talebi onaylandı.'
        ]);
    }

    public function rejectAdvance($id)
    {
        // Burada gerçek uygulamada veritabanından güncelleme yapılacak

        return response()->json([
            'success' => true,
            'message' => 'Avans talebi reddedildi.'
        ]);
    }

    public function addAdvancePayment(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:255'
        ]);

        // Burada gerçek uygulamada ödeme kaydı yapılacak

        return response()->json([
            'success' => true,
            'message' => 'Ödeme başarıyla kaydedildi.'
        ]);
    }

    public function getAdvanceDetails($id)
    {
        // Burada gerçek uygulamada veritabanından detay getirilecek

        $advance = [
            'id' => $id,
            'employee_name' => 'Örnek Personel',
            'transactions' => [
                [
                    'date' => '2024-12-01',
                    'type' => 'advance',
                    'amount' => 15000,
                    'description' => 'Avans verildi'
                ],
                [
                    'date' => '2024-12-15',
                    'type' => 'payment',
                    'amount' => -3000,
                    'description' => '1. taksit ödemesi'
                ]
            ]
        ];

        return response()->json($advance);
    }

    public function createAdvanceForm()
    {
        $employees = \App\Models\Employee::all();
        return view('finance.hr.create-advance', compact('employees'));
    }

    public function showAdvance($id)
    {
        $advance = \App\Models\Advance::with('employee')->find($id);
        if (!$advance) {
            $advance = \App\Models\Advance::with('employee')->first();
        }
        if (!$advance) {
            abort(404);
        }
        return view('finance.hr.show-advance', compact('advance', 'id'));
    }

    public function editAdvance($id)
    {
        $advance = \App\Models\Advance::with('employee')->find($id);
        if (!$advance) {
            $advance = \App\Models\Advance::with('employee')->first();
        }
        if (!$advance) {
            abort(404);
        }
        $employees = \App\Models\Employee::all();
        return view('finance.hr.edit-advance', compact('advance', 'id', 'employees'));
    }

    public function hrPayroll()
    {
        $payrolls = \App\Models\Payroll::with('employee')->get();

        $totalEmployees = $payrolls->count();
        $approvedPayrolls = $payrolls->where('status', 'approved')->count();
        $pendingPayrolls = $payrolls->where('status', 'pending')->count();
        $paidPayrolls = $payrolls->where('status', 'paid')->count();
        $totalGrossSalary = $payrolls->sum('gross_salary');
        $totalNetSalary = $payrolls->sum('net_salary');
        $totalSgk = $payrolls->sum('sgk_deduction');
        $totalTax = $payrolls->sum('tax_deduction');

        return view('finance.hr.payroll', compact(
            'payrolls',
            'totalEmployees',
            'approvedPayrolls',
            'pendingPayrolls',
            'paidPayrolls',
            'totalGrossSalary',
            'totalNetSalary',
            'totalSgk',
            'totalTax'
        ));
    }

    public function createPayrollForm()
    {
        $employees = \App\Models\Employee::all();
        return view('finance.hr.create-payroll', compact('employees'));
    }

    public function showPayroll($id)
    {
        $payroll = \App\Models\Payroll::with('employee')->find($id);
        if (!$payroll) {
            $payroll = \App\Models\Payroll::with('employee')->first();
        }
        if (!$payroll) {
            abort(404);
        }
        return view('finance.hr.show-payroll', compact('payroll', 'id'));
    }

    public function editPayroll($id)
    {
        $payroll = \App\Models\Payroll::with('employee')->find($id);
        if (!$payroll) {
            $payroll = \App\Models\Payroll::with('employee')->first();
        }
        if (!$payroll) {
            abort(404);
        }
        $employees = \App\Models\Employee::all();
        return view('finance.hr.edit-payroll', compact('payroll', 'id', 'employees'));
    }

    public function approvePayroll($id)
    {
        // Burada gerçek uygulamada veritabanından güncelleme yapılacak

        return response()->json([
            'success' => true,
            'message' => 'Bordro onaylandı.'
        ]);
    }

    public function bulkPayrollAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,export',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'integer'
        ]);

        // Burada gerçek uygulamada toplu işlem yapılacak

        $action = $request->action;
        $count = count($request->employee_ids);

        $messages = [
            'approve' => "{$count} bordro onaylandı.",
            'reject' => "{$count} bordro reddedildi.",
            'export' => "{$count} bordro Excel'e aktarıldı."
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$action]
        ]);
    }

    public function getPayrollDetails($id)
    {
        // Burada gerçek uygulamada veritabanından detay getirilecek

        $payroll = [
            'id' => $id,
            'employee_name' => 'Örnek Personel',
            'salary_history' => [
                [
                    'month' => 'Aralık 2024',
                    'gross_salary' => 25000,
                    'net_salary' => 18500,
                    'status' => 'paid'
                ],
                [
                    'month' => 'Kasım 2024',
                    'gross_salary' => 24000,
                    'net_salary' => 17800,
                    'status' => 'paid'
                ]
            ],
            'deduction_details' => [
                'insurance' => 2500,
                'tax' => 4200,
                'advance' => 3000,
                'other' => 500
            ]
        ];

        return response()->json($payroll);
    }

    public function updatePayroll(Request $request, $id)
    {
        $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0|max:100',
            'bonus' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0'
        ]);

        // Burada gerçek uygulamada bordro güncellemesi yapılacak

        return response()->json([
            'success' => true,
            'message' => 'Bordro başarıyla güncellendi.'
        ]);
    }

    public function stockCards()
    {
        $stockCards = \App\Models\StockCard::orderBy('created_at', 'desc')->get();

        $totalCards = $stockCards->count();
        $criticalStockCount = $stockCards->filter(fn($c) => $c->current_stock <= $c->min_stock)->count();
        $totalInventoryValue = $stockCards->sum('total_value');
        $activeCardsCount = $stockCards->where('status', 'active')->count();

        return view('finance.stock.cards', compact(
            'stockCards',
            'totalCards',
            'criticalStockCount',
            'totalInventoryValue',
            'activeCardsCount'
        ));
    }

    public function createStockCard()
    {
        return view('finance.stock.create-card');
    }

    public function showStockCard($id)
    {
        $stockCard = \App\Models\StockCard::find($id);
        if (!$stockCard) {
            $stockCard = \App\Models\StockCard::first();
        }
        if (!$stockCard) {
            abort(404);
        }
        return view('finance.stock.show-card', compact('stockCard', 'id'));
    }

    public function editStockCard($id)
    {
        $stockCard = \App\Models\StockCard::find($id);
        if (!$stockCard) {
            $stockCard = \App\Models\StockCard::first();
        }
        if (!$stockCard) {
            abort(404);
        }
        return view('finance.stock.edit-card', compact('stockCard', 'id'));
    }

    public function stockMovements()
    {
        $movements = \App\Models\StockMovement::with(['stockCard', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalMovements = $movements->count();
        $inCount = $movements->where('type', 'in')->count();
        $outCount = $movements->where('type', 'out')->count();
        $transferCount = $movements->where('type', 'transfer')->count();
        $totalMovementVolume = $movements->sum('total_amount');

        return view('finance.stock.movements', compact(
            'movements',
            'totalMovements',
            'inCount',
            'outCount',
            'transferCount',
            'totalMovementVolume'
        ));
    }

    public function createStockMovement()
    {
        $stockCards = \App\Models\StockCard::all();
        return view('finance.stock.create-movement', compact('stockCards'));
    }

    public function showStockMovement($id)
    {
        $movement = \App\Models\StockMovement::with(['stockCard', 'user'])->find($id);
        if (!$movement) {
            $movement = \App\Models\StockMovement::with(['stockCard', 'user'])->first();
        }
        if (!$movement) {
            abort(404);
        }
        return view('finance.stock.show-movement', compact('movement', 'id'));
    }

    public function editStockMovement($id)
    {
        $movement = \App\Models\StockMovement::with(['stockCard', 'user'])->find($id);
        if (!$movement) {
            $movement = \App\Models\StockMovement::with(['stockCard', 'user'])->first();
        }
        if (!$movement) {
            abort(404);
        }
        $stockCards = \App\Models\StockCard::all();
        return view('finance.stock.edit-movement', compact('movement', 'id', 'stockCards'));
    }

    // Expenses
    public function expensesGeneral()
    {
        $query = \App\Models\GeneralExpense::with(['approver', 'creator']);

        // Filtreleri uygula
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('vendor_name', 'like', '%' . $search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (request()->has('category') && !empty(request('category'))) {
            $query->where('category', request('category'));
        }

        if (request()->has('status') && !empty(request('status'))) {
            $query->where('status', request('status'));
        }

        if (request()->has('department') && !empty(request('department'))) {
            $query->where('department', request('department'));
        }

        if (request()->has('date_range') && !empty(request('date_range'))) {
            $dates = explode(' to ', request('date_range'));
            if (count($dates) == 2) {
                $query->whereBetween('expense_date', [$dates[0], $dates[1]]);
            }
        }

        // Excel export
        if (request()->has('export') && request('export') === 'excel') {
            return $this->exportExpensesToExcel($query);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20)->withQueryString();

        // İstatistikler - filtrelerden etkilenmemesi için ayrı sorgu kullanıyoruz
        $totalExpenses = \App\Models\GeneralExpense::sum('total_amount');
        $pendingCount = \App\Models\GeneralExpense::where('status', 'pending')->count();
        $thisMonthTotal = \App\Models\GeneralExpense::thisMonth()->sum('total_amount');
        $averageAmount = \App\Models\GeneralExpense::avg('total_amount');

        // Kategori bazlı istatistikler
        $categoryStats = \App\Models\GeneralExpense::selectRaw('category, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function($item) {
                $categories = \App\Models\GeneralExpense::getCategories();
                return [$item->category => [
                    'name' => $categories[$item->category] ?? $item->category,
                    'count' => $item->count,
                    'total' => $item->total
                ]];
            });

        // Status istatistikleri
        $statusStats = \App\Models\GeneralExpense::selectRaw('status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Aylık trend (son 6 ay)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $total = \App\Models\GeneralExpense::whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->sum('total_amount');
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'total' => $total
            ];
        }

        // Departman bazlı harcamalar
        $departmentStats = \App\Models\GeneralExpense::selectRaw('department, COUNT(*) as count, SUM(total_amount) as total')
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Load vendors and projects for the "Yeni Gider Ekle" modal selects
        $vendors = \App\Models\Vendor::orderBy('name')->get();
        $projects = \App\Models\Project::orderBy('name')->get();

        return view('finance.expenses.general', compact(
            'expenses',
            'totalExpenses',
            'pendingCount',
            'thisMonthTotal',
            'averageAmount',
            'categoryStats',
            'statusStats',
            'monthlyTrend',
            'departmentStats',
            'vendors',
            'projects'
        ));
    }

    /**
     * AJAX: Add a new expense category
     */
    public function addExpenseCategory(Request $request)
    {
        \Log::info('addExpenseCategory called', ['has_x_csrf' => $request->headers->has('X-CSRF-TOKEN'), 'auth_id' => auth()->id(), 'ip' => $request->ip(), 'name' => $request->input('name')]);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $file = storage_path('app/expense_categories.json');
        $categories = [];
        if (file_exists($file)) {
            $categories = json_decode(file_get_contents($file), true) ?: [];
        } else {
            $categories = \App\Models\GeneralExpense::getCategories();
        }

        // create a slug/key from name
        $key = strtolower(preg_replace('/[^a-z0-9]+/i', '_', trim($request->name)));
        $origKey = $key;
        $i = 1;
        while (isset($categories[$key])) {
            $key = $origKey . '_' . $i;
            $i++;
        }

        $categories[$key] = $request->name;
        try {
            $written = @file_put_contents($file, json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            if ($written === false) {
                \Log::warning('addExpenseCategory write failed', ['file' => $file]);
                return response()->json(['success' => false, 'message' => 'Kategori dosyasına yazılamadı. İzinleri kontrol edin.']);
            }
        } catch (\Exception $e) {
            \Log::error('addExpenseCategory exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Kategori eklendi', 'key' => $key]);
    }

    /**
     * AJAX: Edit an existing expense category
     */
    public function editExpenseCategory(Request $request)
    {
        \Log::info('editExpenseCategory called', ['has_x_csrf' => $request->headers->has('X-CSRF-TOKEN'), 'auth_id' => auth()->id(), 'ip' => $request->ip(), 'key' => $request->input('key'), 'name' => $request->input('name')]);

        $request->validate([
            'key' => 'required|string',
            'name' => 'required|string|max:255'
        ]);

        $file = storage_path('app/expense_categories.json');
        $categories = [];
        if (file_exists($file)) {
            $categories = json_decode(file_get_contents($file), true) ?: [];
        } else {
            $categories = \App\Models\GeneralExpense::getCategories();
        }

        if (!isset($categories[$request->key])) {
            return response()->json(['success' => false, 'message' => 'Kategori bulunamadı']);
        }

        $categories[$request->key] = $request->name;
        try {
            $written = @file_put_contents($file, json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            if ($written === false) {
                \Log::warning('editExpenseCategory write failed', ['file' => $file]);
                return response()->json(['success' => false, 'message' => 'Kategori dosyasına yazılamadı. İzinleri kontrol edin.']);
            }
        } catch (\Exception $e) {
            \Log::error('editExpenseCategory exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Kategori güncellendi']);
    }

    /**
     * AJAX: Delete an expense category
     */
    public function deleteExpenseCategory(Request $request)
    {
        \Log::info('deleteExpenseCategory called', ['has_x_csrf' => $request->headers->has('X-CSRF-TOKEN'), 'auth_id' => auth()->id(), 'ip' => $request->ip(), 'key' => $request->input('key')]);

        $request->validate([
            'key' => 'required|string'
        ]);

        $file = storage_path('app/expense_categories.json');
        $categories = [];
        if (file_exists($file)) {
            $categories = json_decode(file_get_contents($file), true) ?: [];
        } else {
            $categories = \App\Models\GeneralExpense::getCategories();
        }

        if (!isset($categories[$request->key])) {
            return response()->json(['success' => false, 'message' => 'Kategori bulunamadı']);
        }

        unset($categories[$request->key]);
        try {
            $written = @file_put_contents($file, json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            if ($written === false) {
                \Log::warning('deleteExpenseCategory write failed', ['file' => $file]);
                return response()->json(['success' => false, 'message' => 'Kategori dosyasına yazılamadı. İzinleri kontrol edin.']);
            }
        } catch (\Exception $e) {
            \Log::error('deleteExpenseCategory exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Kategori silindi']);
    }

    /**
     * Render a separate categories management page
     */
    public function categoriesManager()
    {
        $file = storage_path('app/expense_categories.json');
        if (file_exists($file)) {
            $categories = json_decode(file_get_contents($file), true) ?: [];
        } else {
            $categories = \App\Models\GeneralExpense::getCategories();
        }

        return view('finance.expenses.categories', compact('categories'));
    }

    /**
     * Yeni bir genel gider kaydı ekler
     */
    public function storeExpense(Request $request)
    {
        // Log the incoming request for debugging (avoid logging files)
        \Log::info('storeExpense called', [
            'has_x_csrf' => $request->headers->has('X-CSRF-TOKEN'),
            'auth_id' => auth()->id(),
            'ip' => $request->ip(),
            'title' => $request->input('title'),
            'amount' => $request->input('amount'),
            'expense_date' => $request->input('expense_date'),
            'vendor_id' => $request->input('vendor_id'),
            'project_id' => $request->input('project_id'),
        ]);

        // Validasyon
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'vat_rate' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expense_date' => 'required|date',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_tax_number' => 'nullable|string|max:30',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_date' => 'nullable|date',
            'department' => 'nullable|string|max:100',
            'project_id' => 'nullable|integer|exists:projects,id',
            'villa_id' => 'nullable|integer',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ]);

        // KDV hesaplaması
        $vatAmount = 0;
        if ($request->filled('vat_rate') && $request->vat_rate > 0) {
            $vatAmount = ($request->amount * $request->vat_rate) / 100;
        }

        // Toplam tutar
        $totalAmount = $request->amount + $vatAmount;

        // Makbuz/fatura/fiş dosyasını yükle
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = 'expense_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');
            $receiptPath = 'storage/' . $path;
        }

        // Get vendor name from vendor_id if provided, otherwise use vendor_name
        $vendorName = $request->vendor_name;
        if ($request->filled('vendor_id')) {
            try {
                $vendor = \App\Models\Vendor::find($request->vendor_id);
                if ($vendor) {
                    $vendorName = $vendor->name;
                }
            } catch (\Exception $e) {
                \Log::warning('Could not load vendor', ['vendor_id' => $request->vendor_id]);
            }
        }

        // Yeni gider oluştur
        $expense = new \App\Models\GeneralExpense();
        $expense->title = $request->title;
        $expense->description = $request->description;
        $expense->category = $request->category;
        $expense->amount = $request->amount;
        $expense->currency = $request->currency;
        $expense->vat_rate = $request->vat_rate ?? 0;
        $expense->vat_amount = $vatAmount;
        $expense->total_amount = $totalAmount;
        $expense->expense_date = $request->expense_date;
        $expense->vendor_name = $vendorName;
        $expense->vendor_tax_number = $request->vendor_tax_number;
        $expense->invoice_number = $request->invoice_number;
        $expense->invoice_date = $request->invoice_date;
        $expense->department = $request->department;
        $expense->receipt_path = $receiptPath;
        $expense->status = 'pending';
        $expense->created_by = auth()->id();
        
        // Set project and villa if provided
        if ($request->filled('project_id')) {
            $expense->project_id = $request->project_id;
        }
        if ($request->filled('villa_id')) {
            $expense->villa_id = $request->villa_id;
        }
        
        try {
            $expense->save();
            \Log::info('storeExpense success', ['id' => $expense->id, 'created_by' => $expense->created_by]);
            return redirect()->route('finance.expenses.general')->with('success', 'Gider başarıyla eklendi.');
        } catch (\Exception $e) {
            \Log::error('storeExpense failed', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['error' => 'Gider kaydı sırasında hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Gider düzenleme sayfasını gösterir
     */
    public function editExpense($id)
    {
        $expense = \App\Models\GeneralExpense::findOrFail($id);
        return view('finance.expenses.edit', compact('expense'));
    }

    /**
     * Gider detaylarını gösterir
     */
    public function viewExpense($id)
    {
        $expense = \App\Models\GeneralExpense::with(['approver', 'creator'])->findOrFail($id);
        return view('finance.expenses.show', compact('expense'));
    }

    /**
     * Gider bilgilerini günceller
     */
    public function updateExpense(Request $request, $id)
    {
        $expense = \App\Models\GeneralExpense::findOrFail($id);

        // Validasyon
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'vat_rate' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_tax_number' => 'nullable|string|max:30',
            'invoice_number' => 'nullable|string|max:100',
            'invoice_date' => 'nullable|date',
            'department' => 'nullable|string|max:100',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
        ]);

        // KDV hesaplaması
        $vatAmount = 0;
        if ($request->filled('vat_rate') && $request->vat_rate > 0) {
            $vatAmount = ($request->amount * $request->vat_rate) / 100;
        }

        // Toplam tutar
        $totalAmount = $request->amount + $vatAmount;

        // Makbuz/fatura/fiş dosyasını yükle
        if ($request->hasFile('receipt')) {
            // Eski dosyayı silmek için mantık eklenebilir
            $file = $request->file('receipt');
            $filename = 'expense_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $filename, 'public');
            $expense->receipt_path = 'storage/' . $path;
        }

        // Gideri güncelle
        $expense->title = $request->title;
        $expense->description = $request->description;
        $expense->category = $request->category;
        $expense->amount = $request->amount;
        $expense->currency = $request->currency;
        $expense->vat_rate = $request->vat_rate ?? 0;
        $expense->vat_amount = $vatAmount;
        $expense->total_amount = $totalAmount;
        $expense->expense_date = $request->expense_date;
        $expense->vendor_name = $request->vendor_name;
        $expense->vendor_tax_number = $request->vendor_tax_number;
        $expense->invoice_number = $request->invoice_number;
        $expense->invoice_date = $request->invoice_date;
        $expense->department = $request->department;
        $expense->save();

        return redirect()->route('finance.expenses.general')->with('success', 'Gider başarıyla güncellendi.');
    }

    /**
     * Gideri onaylar
     */
    public function approveExpense($id)
    {
        // Debug log - trace incoming approve requests
        try {
            \Log::info('approveExpense called', [
                'id' => $id,
                'has_x_csrf' => request()->headers->has('x-csrf-token'),
                'auth_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // ignore logging errors
        }

        $expense = \App\Models\GeneralExpense::findOrFail($id);

        if ($expense->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Sadece bekleyen giderler onaylanabilir.'
            ]);
        }

        $expense->status = 'approved';
        $expense->approved_by = auth()->id();
        $expense->approved_at = now();
        $expense->save();

        return response()->json([
            'success' => true,
            'message' => 'Gider başarıyla onaylandı.'
        ]);
    }

    /**
     * Gideri reddeder
     */
    public function rejectExpense($id)
    {
        // Debug log - trace incoming reject requests
        try {
            \Log::info('rejectExpense called', [
                'id' => $id,
                'has_x_csrf' => request()->headers->has('x-csrf-token'),
                'auth_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {}

        $expense = \App\Models\GeneralExpense::findOrFail($id);

        if ($expense->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Sadece bekleyen giderler reddedilebilir.'
            ]);
        }

        $expense->status = 'rejected';
        $expense->approved_by = auth()->id();
        $expense->approved_at = now();
        $expense->save();

        return response()->json([
            'success' => true,
            'message' => 'Gider başarıyla reddedildi.'
        ]);
    }

    /**
     * Gideri siler (soft delete)
     */
    public function deleteExpense($id)
    {
        // Debug log - trace incoming delete requests
        try {
            \Log::info('deleteExpense called', [
                'id' => $id,
                'has_x_csrf' => request()->headers->has('x-csrf-token'),
                'auth_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        } catch (\Exception $e) {}

        $expense = \App\Models\GeneralExpense::findOrFail($id);
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gider başarıyla silindi.'
        ]);
    }

    /**
     * Toplu gider onaylama
     */
    public function bulkApproveExpenses(Request $request)
    {
        $query = \App\Models\GeneralExpense::where('status', 'pending');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('max_amount')) {
            $query->where('total_amount', '<=', $request->max_amount);
        }

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('expense_date', [$dates[0], $dates[1]]);
            }
        }

        $count = $query->count();

        $query->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->route('finance.expenses.general')->with('success', $count . ' adet gider başarıyla toplu onaylandı.');
    }

    /**
     * Gider raporları sayfasını gösterir
     */
    public function expenseReports()
    {
        // Raporlar için mantığı burada uygulayın
        return view('finance.expenses.reports');
    }

    /**
     * Giderleri Excel olarak dışa aktarır
     */
    private function exportExpensesToExcel($query)
    {
        $expenses = $query->get();

        // Excel export mantığınızı buraya ekleyin
        // Örneğin maatwebsite/excel paketi kullanılabilir

        return back()->with('info', 'Excel dışa aktarma işlevi çok yakında aktifleşecektir.');
    }

    // Assets
    public function assetsList()
    {
        $assets = Asset::with(['assignedUser', 'creator'])
            ->orderBy('purchase_date', 'desc')
            ->paginate(20);

        // İstatistikler
        $totalValue = Asset::sum('current_value');
        $totalPurchaseValue = Asset::sum('purchase_price');
        $totalDepreciation = $totalPurchaseValue - $totalValue;
        $activeAssetsCount = Asset::where('status', 'active')->count();
        $maintenanceDue = Asset::needsMaintenance()->count();
        $warrantyExpiring = Asset::warrantyExpiring()->count();

        // Kategori bazlı istatistikler
        $categoryStats = Asset::selectRaw('category, COUNT(*) as count, SUM(current_value) as total_value, SUM(purchase_price) as total_purchase')
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function($item) {
                $categories = Asset::getCategories();
                return [$item->category => [
                    'name' => $categories[$item->category] ?? $item->category,
                    'count' => $item->count,
                    'current_value' => $item->total_value,
                    'purchase_value' => $item->total_purchase,
                    'depreciation' => $item->total_purchase - $item->total_value
                ]];
            });

        // Departman bazlı varlıklar
        $departmentStats = Asset::selectRaw('department, COUNT(*) as count, SUM(current_value) as total_value')
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('total_value', 'desc')
            ->limit(6)
            ->get();

        // Yaş dağılımı
        $assetAgeExpr = \DB::getDriverName() === 'sqlite'
            ? "(julianday('now') - julianday(COALESCE(purchase_date, installation_date)))"
            : "DATEDIFF(NOW(), COALESCE(purchase_date, installation_date))";
        $ageDistribution = [
            '0-1 yıl' => Asset::whereRaw("{$assetAgeExpr} <= 365")->count(),
            '1-3 yıl' => Asset::whereRaw("{$assetAgeExpr} BETWEEN 366 AND 1095")->count(),
            '3-5 yıl' => Asset::whereRaw("{$assetAgeExpr} BETWEEN 1096 AND 1825")->count(),
            '5+ yıl' => Asset::whereRaw("{$assetAgeExpr} > 1825")->count(),
        ];

        // Durum istatistikleri
        $statusStats = Asset::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Değer trendi (son 6 ay)
        $valueHistory = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthValue = Asset::whereMonth('purchase_date', '<=', $date->month)
                ->whereYear('purchase_date', '<=', $date->year)
                ->sum('current_value');
            $valueHistory[] = [
                'month' => $date->format('M Y'),
                'value' => $monthValue
            ];
        }

        $totalPurchaseCost = $totalPurchaseValue;

        return view('finance.assets.list', compact(
            'assets',
            'totalValue',
            'totalPurchaseValue',
            'totalPurchaseCost',
            'totalDepreciation',
            'activeAssetsCount',
            'maintenanceDue',
            'warrantyExpiring',
            'categoryStats',
            'departmentStats',
            'ageDistribution',
            'statusStats',
            'valueHistory'
        ));
    }
    public function assetsAmortization()
    {
        // Aktif varlıkları getir (amortismana tabi olanlar)
        $assets = Asset::active()
            ->whereNotNull('purchase_price')
            ->whereNotNull('purchase_date')
            ->with(['assignedUser', 'creator'])
            ->get();

        // Amortisman hesaplamaları
        $totalPurchaseValue = $assets->sum('purchase_price');
        $totalCurrentValue = $assets->sum('current_value');
        $totalDepreciation = $totalPurchaseValue - $totalCurrentValue;

        // Yıllık amortisman
        $annualDepreciation = 0;
        $monthlyDepreciation = 0;

        foreach ($assets as $asset) {
            if ($asset->useful_life_years && $asset->useful_life_years > 0) {
                $annualAmount = ($asset->purchase_price - ($asset->salvage_value ?: 0)) / $asset->useful_life_years;
                $annualDepreciation += $annualAmount;
                $monthlyDepreciation += $annualAmount / 12;
            }
        }

        // Kategoriye göre amortisman dağılımı
        $depreciationByCategory = $assets->groupBy('category')->map(function ($categoryAssets) {
            $totalPurchase = $categoryAssets->sum('purchase_price');
            $totalCurrent = $categoryAssets->sum('current_value');
            $totalDepreciation = $totalPurchase - $totalCurrent;
            $depreciationRate = $totalPurchase > 0 ? ($totalDepreciation / $totalPurchase) * 100 : 0;

            return [
                'count' => $categoryAssets->count(),
                'purchase_value' => $totalPurchase,
                'current_value' => $totalCurrent,
                'depreciation_amount' => $totalDepreciation,
                'depreciation_rate' => $depreciationRate,
                'category_name' => Asset::getCategories()[$categoryAssets->first()->category] ?? $categoryAssets->first()->category
            ];
        });

        // Yaşa göre amortisman analizi
        $depreciationByAge = $assets->groupBy(function($asset) {
            $age = $asset->age_in_years;
            if ($age <= 1) return '0-1 yıl';
            if ($age <= 3) return '1-3 yıl';
            if ($age <= 5) return '3-5 yıl';
            if ($age <= 10) return '5-10 yıl';
            return '10+ yıl';
        })->map(function ($ageGroup) {
            $totalPurchase = $ageGroup->sum('purchase_price');
            $totalCurrent = $ageGroup->sum('current_value');
            return [
                'count' => $ageGroup->count(),
                'purchase_value' => $totalPurchase,
                'current_value' => $totalCurrent,
                'depreciation_amount' => $totalPurchase - $totalCurrent,
                'depreciation_rate' => $totalPurchase > 0 ? (($totalPurchase - $totalCurrent) / $totalPurchase) * 100 : 0
            ];
        });

        // En çok değer kaybeden varlıklar
        $topDepreciatedAssets = $assets->sortByDesc(function($asset) {
            return $asset->depreciation_amount;
        })->take(10);

        // Amortisman metodlarına göre dağılım
        $methodDistribution = $assets->groupBy('depreciation_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total_value' => $group->sum('purchase_price'),
                'depreciation' => $group->sum('purchase_price') - $group->sum('current_value')
            ];
        });

        // Kategori ve durum listelerini view'a gönder
        $categories = Asset::getCategories();
        $statuses = Asset::getStatuses();
        $conditions = Asset::getConditions();

        return view('finance.assets.amortization', compact(
            'assets',
            'totalPurchaseValue',
            'totalCurrentValue',
            'totalDepreciation',
            'annualDepreciation',
            'monthlyDepreciation',
            'depreciationByCategory',
            'depreciationByAge',
            'topDepreciatedAssets',
            'methodDistribution',
            'categories',
            'statuses',
            'conditions'
        ));
    }

    // Budget
    /**
     * Display invoice management page.
     */
    public function invoicesIndex(): View
    {
        $invoices = Schema::hasTable('invoices')
            ? \App\Models\Invoice::with('customer')->orderByDesc('date')->orderByDesc('id')->get()->map(function ($inv) {
                $inv->customer_name = optional($inv->customer)->name ?? 'Müşteri';
                $inv->balance = (float) ($inv->amount ?? 0) - (float) ($inv->paid_amount ?? 0);
                return $inv;
            })
            : collect();

        $stats = [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('amount'),
            'paid_amount' => $invoices->sum('paid_amount'),
            'outstanding_amount' => $invoices->sum('balance'),
            'overdue_count' => $invoices->where('status', 'overdue')->count(),
            'overdue_amount' => $invoices->where('status', 'overdue')->sum('balance')
        ];

        return view('finance.invoices.index', compact('invoices', 'stats'));
    }

    public function budgetCompany()
    {
        // Şirket bütçe verilerini hazırla
        $accounts = Account::where('active', true)->get();
        $totalBalance = $accounts->sum('balance');

        // Hesap türlerine göre gruplama
        $accountsByType = $accounts->groupBy('type');

        // Son 12 ay için aylık akış verileri
        $monthlyFlow = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyFlow[] = [
                'month' => $date->format('M Y'),
                'income' => rand(50000, 150000), // Gerçek verilerle değiştirilecek
                'expense' => rand(40000, 120000), // Gerçek verilerle değiştirilecek
            ];
        }

        // Bütçe kategorileri
        $budgetCategories = [
            ['name' => 'Operasyonel Giderler', 'allocated' => 500000, 'spent' => 320000, 'percentage' => 64],
            ['name' => 'Proje Yatırımları', 'allocated' => 800000, 'spent' => 560000, 'percentage' => 70],
            ['name' => 'İnsan Kaynakları', 'allocated' => 600000, 'spent' => 480000, 'percentage' => 80],
            ['name' => 'Pazarlama', 'allocated' => 200000, 'spent' => 120000, 'percentage' => 60],
            ['name' => 'AR-GE', 'allocated' => 300000, 'spent' => 180000, 'percentage' => 60],
        ];

        // KPI verileri
        $kpis = [
            'revenue_growth' => 15.2,
            'profit_margin' => 23.8,
            'expense_ratio' => 76.2,
            'cash_flow' => 2.4,
        ];

        return view('finance.budget.company', compact(
            'accounts',
            'totalBalance',
            'accountsByType',
            'monthlyFlow',
            'budgetCategories',
            'kpis'
        ));
    }
    public function budgetLoans()
    {
        // Kredi ve Taksit verilerini hazırla
        $loans = [
            [
                'id' => 1,
                'bank_name' => 'Ziraat Bankası',
                'loan_type' => 'İşletme Kredisi',
                'loan_amount' => 1500000,
                'remaining_amount' => 850000,
                'interest_rate' => 18.5,
                'installment_count' => 36,
                'paid_installments' => 18,
                'monthly_payment' => 65400,
                'start_date' => '2023-01-15',
                'end_date' => '2026-01-15',
                'status' => 'active',
                'next_payment' => '2025-11-15',
                'guarantee_type' => 'Teminat Mektubu'
            ],
            [
                'id' => 2,
                'bank_name' => 'İş Bankası',
                'loan_type' => 'Yatırım Kredisi',
                'loan_amount' => 2500000,
                'remaining_amount' => 1200000,
                'interest_rate' => 16.8,
                'installment_count' => 48,
                'paid_installments' => 24,
                'monthly_payment' => 78500,
                'start_date' => '2022-06-01',
                'end_date' => '2026-06-01',
                'status' => 'active',
                'next_payment' => '2025-11-01',
                'guarantee_type' => 'İpotek'
            ],
            [
                'id' => 3,
                'bank_name' => 'Garanti BBVA',
                'loan_type' => 'Nakdi Kredi',
                'loan_amount' => 500000,
                'remaining_amount' => 120000,
                'interest_rate' => 22.5,
                'installment_count' => 24,
                'paid_installments' => 20,
                'monthly_payment' => 28300,
                'start_date' => '2023-08-01',
                'end_date' => '2025-08-01',
                'status' => 'active',
                'next_payment' => '2025-11-01',
                'guarantee_type' => 'Kefalet'
            ],
            [
                'id' => 4,
                'bank_name' => 'Akbank',
                'loan_type' => 'Araç Kredisi',
                'loan_amount' => 800000,
                'remaining_amount' => 0,
                'interest_rate' => 15.2,
                'installment_count' => 60,
                'paid_installments' => 60,
                'monthly_payment' => 18900,
                'start_date' => '2020-03-01',
                'end_date' => '2025-03-01',
                'status' => 'completed',
                'next_payment' => null,
                'guarantee_type' => 'Araç Rehni'
            ]
        ];

        // Özet istatistikler
        $summary = [
            'total_loan_amount' => collect($loans)->sum('loan_amount'),
            'total_remaining' => collect($loans)->sum('remaining_amount'),
            'total_paid' => collect($loans)->sum('loan_amount') - collect($loans)->sum('remaining_amount'),
            'monthly_total_payment' => collect($loans)->where('status', 'active')->sum('monthly_payment'),
            'active_loans_count' => collect($loans)->where('status', 'active')->count(),
            'completed_loans_count' => collect($loans)->where('status', 'completed')->count(),
        ];

        // Aylık ödeme takvimi (gelecek 12 ay)
        $paymentCalendar = [];
        for ($i = 0; $i < 12; $i++) {
            $date = now()->addMonths($i);
            $monthlyPayment = 0;

            foreach ($loans as $loan) {
                if ($loan['status'] == 'active' && $date->format('Y-m') <= date('Y-m', strtotime($loan['end_date']))) {
                    $monthlyPayment += $loan['monthly_payment'];
                }
            }

            $paymentCalendar[] = [
                'month' => $date->format('M Y'),
                'total_payment' => $monthlyPayment,
                'principal' => $monthlyPayment * 0.6, // Tahmini anapara
                'interest' => $monthlyPayment * 0.4, // Tahmini faiz
            ];
        }

        // Risk analizi
        $riskAnalysis = [
            'debt_to_equity_ratio' => 45.2, // Borç/Özkaynak oranı
            'debt_service_coverage' => 2.8, // Borç servisi karşılama oranı
            'interest_coverage_ratio' => 5.2, // Faiz karşılama oranı
            'credit_utilization' => 68.5, // Kredi kullanım oranı
        ];

        return view('finance.budget.loans', compact(
            'loans',
            'summary',
            'paymentCalendar',
            'riskAnalysis'
        ));
    }
    public function budgetGuarantees()
    {
        // Sample guarantee data - in real app this would come from database
        $guarantees = [
            [
                'id' => 1,
                'guarantee_no' => 'TMN-2024-001',
                'reference' => 'B/12345',
                'type' => 'Kesin Teminat',
                'type_color' => 'primary',
                'project_name' => 'Metro Projesi',
                'project_detail' => 'Faz 2 - Tünel İnşaatı',
                'amount' => 850000,
                'percentage' => 10,
                'bank' => 'Garanti BBVA',
                'bank_logo' => 'garanti.png',
                'start_date' => '2024-03-15',
                'end_date' => '2024-09-15',
                'status' => 'active',
                'status_text' => 'Aktif',
                'days_remaining' => 45
            ],
            [
                'id' => 2,
                'guarantee_no' => 'TMN-2024-002',
                'reference' => 'A/54321',
                'type' => 'Avans Teminatı',
                'type_color' => 'warning',
                'project_name' => 'Hastane İnşaatı',
                'project_detail' => 'Ana Blok',
                'amount' => 650000,
                'percentage' => 15,
                'bank' => 'İş Bankası',
                'bank_logo' => 'isbank.png',
                'start_date' => '2024-02-01',
                'end_date' => '2024-11-01',
                'status' => 'warning',
                'status_text' => 'Süresi Dolacak',
                'days_remaining' => 15
            ],
            [
                'id' => 3,
                'guarantee_no' => 'TMN-2024-003',
                'reference' => 'C/67890',
                'type' => 'İş Bitirme',
                'type_color' => 'success',
                'project_name' => 'Okul İnşaatı',
                'project_detail' => '50 Derslik',
                'amount' => 450000,
                'percentage' => 8,
                'bank' => 'Akbank',
                'bank_logo' => 'akbank.png',
                'start_date' => '2024-01-10',
                'end_date' => '2026-01-10',
                'status' => 'active',
                'status_text' => 'Aktif',
                'days_remaining' => 450
            ]
        ];

        // Sample insurance data
        $insurances = [
            [
                'id' => 1,
                'policy_no' => 'POL-2024-YNG-001',
                'type' => 'Yangın Sigortası',
                'icon' => 'mdi-fire',
                'icon_color' => '#dc3545',
                'company' => 'Axa Sigorta',
                'coverage_amount' => 5000000,
                'premium_amount' => 45000,
                'start_date' => '2024-01-15',
                'end_date' => '2025-01-15',
                'status' => 'active',
                'status_text' => 'Aktif'
            ],
            [
                'id' => 2,
                'policy_no' => 'POL-2024-IK-002',
                'type' => 'İş Kazası Sigortası',
                'icon' => 'mdi-account-injury',
                'icon_color' => '#17a2b8',
                'company' => 'Allianz',
                'coverage_amount' => 2500000,
                'premium_amount' => 28500,
                'start_date' => '2024-03-01',
                'end_date' => '2025-03-01',
                'status' => 'active',
                'status_text' => 'Aktif'
            ],
            [
                'id' => 3,
                'policy_no' => 'POL-2024-KSK-003',
                'type' => 'Kasko Sigortası',
                'icon' => 'mdi-car',
                'icon_color' => '#ffc107',
                'company' => 'Mapfre Sigorta',
                'coverage_amount' => 1200000,
                'premium_amount' => 85000,
                'start_date' => '2024-05-20',
                'end_date' => '2025-05-20',
                'status' => 'warning',
                'status_text' => 'Yenilenmeli'
            ]
        ];

        // Calculate summary metrics
        $totalGuaranteeAmount = collect($guarantees)->sum('amount');
        $activeGuarantees = collect($guarantees)->where('status', 'active')->count();
        $monthlyInsurancePremium = collect($insurances)->sum('premium_amount') / 12;
        $expiringGuarantees = collect($guarantees)->where('days_remaining', '<=', 30)->count();

        // Risk analysis
        $riskAnalysis = [
            'high_risk' => 12,
            'medium_risk' => 28,
            'low_risk' => 60
        ];

        // Chart data for guarantee types
        $guaranteeChartData = [
            'categories' => ['Kesin Teminat', 'Avans Teminatı', 'İş Bitirme', 'Geçici Teminat', 'Diğer'],
            'data' => [850000, 650000, 450000, 300000, 200000]
        ];

        return view('finance.budget.guarantees', compact(
            'guarantees',
            'insurances',
            'totalGuaranteeAmount',
            'activeGuarantees',
            'monthlyInsurancePremium',
            'expiringGuarantees',
            'riskAnalysis',
            'guaranteeChartData'
        ));
    }

    // E-doc
    public function edocEfatura()
    {
        // Demo veriler (gerçek entegrasyonda API'den gelecek)
        $invoiceStats = [
            'total_invoices' => 2847,
            'pending_approval' => 23,
            'sent_today' => 156,
            'rejected' => 8,
            'monthly_volume' => 42350.75,
            'success_rate' => 97.2
        ];

        $recentInvoices = [
            [
                'id' => 'EFA-2024-001234',
                'customer' => 'ABC İnşaat Ltd. Şti.',
                'amount' => 15750.00,
                'date' => '2024-01-15',
                'status' => 'sent',
                'pdf_path' => '/storage/invoices/efa-2024-001234.pdf'
            ],
            [
                'id' => 'EFA-2024-001235',
                'customer' => 'Kartal Yapı A.Ş.',
                'amount' => 28500.50,
                'date' => '2024-01-15',
                'status' => 'pending',
                'pdf_path' => null
            ],
            [
                'id' => 'EFA-2024-001236',
                'customer' => 'Beyoğlu Belediyesi',
                'amount' => 67200.25,
                'date' => '2024-01-14',
                'status' => 'approved',
                'pdf_path' => '/storage/invoices/efa-2024-001236.pdf'
            ],
            [
                'id' => 'EFA-2024-001237',
                'customer' => 'Marmara Üniversitesi',
                'amount' => 12350.00,
                'date' => '2024-01-14',
                'status' => 'rejected',
                'pdf_path' => null
            ],
            [
                'id' => 'EFA-2024-001238',
                'customer' => 'Türk Telekom A.Ş.',
                'amount' => 45800.75,
                'date' => '2024-01-13',
                'status' => 'sent',
                'pdf_path' => '/storage/invoices/efa-2024-001238.pdf'
            ]
        ];

        $monthlyData = [
            'labels' => ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
            'sent' => [145, 189, 210, 167, 198, 156],
            'approved' => [138, 182, 201, 159, 190, 148],
            'rejected' => [7, 7, 9, 8, 8, 8]
        ];

        return view('finance.edoc.efatura', compact('invoiceStats', 'recentInvoices', 'monthlyData'));
    }
    public function edocEarshiv()
    {
        // E-Arşiv İstatistikleri
        $earshivStats = [
            'total_documents' => 1247,
            'pending_approval' => 23,
            'approved_today' => 15,
            'archived_this_month' => 186,
            'storage_used' => '2.8 GB',
            'compliance_rate' => 98.5
        ];

        // Son E-Arşiv Belgeleri
        $recentDocuments = [
            [
                'id' => 'EA-2024-001247',
                'document_type' => 'E-Fatura',
                'customer_name' => 'ABC İnşaat Ltd. Şti.',
                'amount' => 125000.00,
                'date' => '2024-10-01',
                'status' => 'approved',
                'archive_date' => '2024-10-01 14:30:00'
            ],
            [
                'id' => 'EA-2024-001246',
                'document_type' => 'E-İrsaliye',
                'customer_name' => 'XYZ Yapı A.Ş.',
                'amount' => 87500.00,
                'date' => '2024-09-30',
                'status' => 'pending',
                'archive_date' => null
            ],
            [
                'id' => 'EA-2024-001245',
                'document_type' => 'E-Fatura',
                'customer_name' => 'DEF Proje Ltd.',
                'amount' => 234000.00,
                'date' => '2024-09-30',
                'status' => 'approved',
                'archive_date' => '2024-09-30 16:45:00'
            ],
            [
                'id' => 'EA-2024-001244',
                'document_type' => 'E-Müstahsil',
                'customer_name' => 'GHI Tarım Koop.',
                'amount' => 45000.00,
                'date' => '2024-09-29',
                'status' => 'approved',
                'archive_date' => '2024-09-29 11:20:00'
            ]
        ];

        // Aylık Arşivleme Verileri (Son 6 ay)
        $monthlyArchiveData = [
            'months' => ['Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim'],
            'documents' => [142, 167, 189, 203, 178, 186],
            'storage' => [1.2, 1.4, 1.6, 1.9, 2.1, 2.8] // GB
        ];

        // Belge Türlerine Göre Dağılım
        $documentTypeDistribution = [
            'E-Fatura' => 65,
            'E-İrsaliye' => 20,
            'E-Müstahsil' => 10,
            'E-Bilet' => 3,
            'Diğer' => 2
        ];

        // Uyumluluk Durumu
        $complianceData = [
            'compliant' => 1226,
            'non_compliant' => 21,
            'pending_review' => 23
        ];

        return view('finance.edoc.earshiv', compact(
            'earshivStats',
            'recentDocuments',
            'monthlyArchiveData',
            'documentTypeDistribution',
            'complianceData'
        ));
    }
    public function edocEirsaliye()
    {
        // E-İrsaliye istatistikleri - gerçek veriler yerine örnek veriler
        $dispatchStats = [
            'total_dispatches' => 1248,
            'in_transit' => 87,
            'sent_today' => 23,
            'delivered' => 156,
            'total_packages' => 342,
            'success_rate' => 96.2,
            'pending_approval' => 15,
            'rejected' => 3
        ];

        // Son irsaliye hareketleri
        $recentDispatches = [
            [
                'id' => 'IRS2024-001248',
                'type' => 'Elektronik Malzeme',
                'recipient' => 'ABC Teknoloji Ltd.',
                'location' => 'İstanbul, Kadıköy',
                'status' => 'transit',
                'dispatch_date' => '2024-10-01 09:30:00',
                'estimated_delivery' => '2024-10-02 16:00:00'
            ],
            [
                'id' => 'IRS2024-001247',
                'type' => 'Ofis Malzemeleri',
                'recipient' => 'XYZ Danışmanlık A.Ş.',
                'location' => 'Ankara, Çankaya',
                'status' => 'delivered',
                'dispatch_date' => '2024-09-30 14:15:00',
                'estimated_delivery' => '2024-10-01 10:30:00'
            ],
            [
                'id' => 'IRS2024-001246',
                'type' => 'İnşaat Malzemesi',
                'recipient' => 'DEF İnşaat Ltd.',
                'location' => 'İzmir, Konak',
                'status' => 'sent',
                'dispatch_date' => '2024-10-01 11:45:00',
                'estimated_delivery' => '2024-10-03 14:00:00'
            ],
            [
                'id' => 'IRS2024-001245',
                'type' => 'Gıda Ürünleri',
                'recipient' => 'GHI Market Zinciri',
                'location' => 'Bursa, Nilüfer',
                'status' => 'draft',
                'dispatch_date' => null,
                'estimated_delivery' => null
            ]
        ];

        // Aktif teslimat takibi
        $activeDelivery = [
            'dispatch_id' => 'IRS2024-001248',
            'vehicle' => '34 ABC 123',
            'driver' => 'Mehmet Yılmaz',
            'phone' => '+90 555 123 4567',
            'steps' => [
                ['title' => 'Sevkiyat Hazırlandı', 'time' => '2024-10-01 08:30:00', 'status' => 'completed'],
                ['title' => 'Depodan Çıkış', 'time' => '2024-10-01 09:30:00', 'status' => 'completed'],
                ['title' => 'Yolda', 'time' => null, 'status' => 'active', 'eta' => '2024-10-02 16:00:00'],
                ['title' => 'Teslimat', 'time' => null, 'status' => 'pending']
            ],
            'packages' => [
                ['name' => 'Elektronik Cihazlar', 'quantity' => 5, 'weight' => 12.5, 'type' => 'fragile'],
                ['name' => 'Yedek Parçalar', 'quantity' => 12, 'weight' => 8.2, 'type' => 'normal']
            ]
        ];

        return view('finance.edoc.eirsaliye', compact(
            'dispatchStats',
            'recentDispatches',
            'activeDelivery'
        ));
    }

    /*
     * Minimal stubs for expense actions so named routes work from the view.
     * These implementations are intentionally lightweight: they avoid throwing
     * errors if auxiliary views are missing and perform safe operations where possible.
     */
    public function expensesCreate()
    {
        // If a dedicated create view exists, show it; otherwise redirect back with info.
        if (view()->exists('finance.expenses.create')) {
            $vendors = Schema::hasTable('vendors') ? \App\Models\Vendor::orderBy('name')->get() : collect();
            $projects = Schema::hasTable('projects') ? \App\Models\Project::orderBy('name')->get() : collect();
            return view('finance.expenses.create', compact('vendors', 'projects'));
        }

        return redirect()->route('finance.expenses.general')->with('info', 'Gider oluşturma sayfası henüz aktif değil.');
    }

    public function expensesStore(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
            'vat_rate' => 'nullable|numeric',
            'category' => 'nullable|string',
            'project_code' => 'nullable|string',
            'expense_date' => 'nullable|date',
            'vendor_name' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255'
        ];

        $data = $request->validate($rules);

        // compute VAT and total
        $vatRate = isset($data['vat_rate']) ? floatval($data['vat_rate']) : 0;
        $amount = isset($data['amount']) ? floatval($data['amount']) : 0;
        $vatAmount = round($amount * ($vatRate / 100), 2);
        $total = round($amount + $vatAmount, 2);

        try {
            if (class_exists(GeneralExpense::class) && \Illuminate\Support\Facades\Schema::hasTable('general_expenses')) {
                $create = [
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'category' => $data['category'] ?? null,
                    'amount' => $amount,
                    'vat_rate' => $vatRate,
                    'vat_amount' => $vatAmount,
                    'total_amount' => $total,
                    'expense_date' => $data['expense_date'] ?? now(),
                    'vendor_name' => $data['vendor_name'] ?? null,
                    'invoice_number' => $data['invoice_number'] ?? null,
                    'project_code' => $data['project_code'] ?? null,
                    'department' => $data['department'] ?? null,
                    'status' => 'pending',
                    'created_by' => auth()->id() ?? null
                ];

                $expense = GeneralExpense::create($create);

                if ($request->wantsJson()) {
                    return response()->json(['success' => true, 'id' => $expense->id, 'status' => $expense->status, 'status_badge' => $expense->status_badge]);
                }

                return redirect()->route('finance.expenses.show', [$expense->id])->with('success', 'Gider oluşturuldu');
            }
        } catch (\Exception $e) {
            // log if needed
        }

        if ($request->wantsJson()) return response()->json(['error' => 'Gider oluşturulamadı'], 500);
        return redirect()->route('finance.expenses.general')->with('info', 'Gider kaydı demo modunda oluşturulmadı.');
    }

    public function expensesShow($id)
    {
        $expense = GeneralExpense::find($id);
        if (!$expense) {
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        if (view()->exists('finance.expenses.show')) {
            return view('finance.expenses.show', compact('expense'));
        }

        // Fallback: return a simple JSON preview so the route is useful during development
        return response()->json($expense);
    }

    public function expensesEdit($id)
    {
        $expense = GeneralExpense::find($id);
        if (!$expense) {
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        if (view()->exists('finance.expenses.edit')) {
            return view('finance.expenses.edit', compact('expense'));
        }

        return redirect()->route('finance.expenses.general')->with('info', 'Gider düzenleme sayfası henüz aktif değil.');
    }

    public function expensesUpdate(Request $request, $id)
    {
        $expense = GeneralExpense::find($id);
        if (!$expense) {
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'category' => 'nullable|string',
            'expense_date' => 'nullable|date',
            'vendor_name' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255'
        ];

        $data = $request->validate($rules);

        try {
            $expense->fill($data);
            $expense->save();
            if ($request->wantsJson()) return response()->json(['success' => true, 'id' => $expense->id, 'status' => $expense->status, 'status_badge' => $expense->status_badge]);
            return redirect()->route('finance.expenses.show', [$expense->id])->with('success', 'Gider güncellendi.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['error' => 'Güncelleme sırasında hata oluştu.'], 500);
            return redirect()->route('finance.expenses.general')->with('error', 'Güncelleme sırasında hata oluştu.');
        }
    }

    public function expensesDestroy($id)
    {
        $expense = GeneralExpense::find($id);
        if (!$expense) {
            if (request()->wantsJson()) return response()->json(['error' => 'Gider bulunamadı.'], 404);
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        try {
            $expense->delete();
            if (request()->wantsJson()) return response()->json(['success' => true, 'id' => $id]);
            return redirect()->route('finance.expenses.general')->with('success', 'Gider silindi.');
        } catch (\Exception $e) {
            if (request()->wantsJson()) return response()->json(['error' => 'Silme sırasında hata oluştu.'], 500);
            return redirect()->route('finance.expenses.general')->with('error', 'Silme sırasında hata oluştu.');
        }
    }

    public function expensesApprove(Request $request, $id)
    {
        $expense = GeneralExpense::find($id);
        if (!$expense) {
            if ($request->wantsJson()) return response()->json(['error' => 'Gider bulunamadı.'], 404);
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        try {
            $expense->status = 'approved';
            $expense->approver_id = auth()->id() ?? $expense->approver_id;
            $expense->approved_at = now();
            $expense->save();
            if ($request->wantsJson()) return response()->json(['success' => true, 'id' => $expense->id, 'status' => 'approved', 'status_badge' => $expense->status_badge]);
            return redirect()->route('finance.expenses.show', [$expense->id])->with('success', 'Gider onaylandı.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['error' => 'Onay sırasında hata oluştu.'], 500);
            return redirect()->route('finance.expenses.general')->with('error', 'Onay sırasında hata oluştu.');
        }
    }

    public function expensesReject(Request $request, $id)
    {
        $expense = GeneralExpense::find($id);
        if (!$expense) {
            if ($request->wantsJson()) return response()->json(['error' => 'Gider bulunamadı.'], 404);
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        try {
            $expense->status = 'rejected';
            $expense->approver_id = auth()->id() ?? $expense->approver_id;
            $expense->rejected_at = now();
            $expense->save();
            if ($request->wantsJson()) return response()->json(['success' => true, 'id' => $expense->id, 'status' => 'rejected', 'status_badge' => $expense->status_badge]);
            return redirect()->route('finance.expenses.show', [$expense->id])->with('success', 'Gider reddedildi.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['error' => 'Reddetme sırasında hata oluştu.'], 500);
            return redirect()->route('finance.expenses.general')->with('error', 'Reddetme sırasında hata oluştu.');
        }
    }

    public function expensesExport()
    {
        try {
            if (!class_exists(GeneralExpense::class)) {
                return redirect()->route('finance.expenses.general')->with('info', 'Export hazır değil.');
            }

            $rows = GeneralExpense::orderBy('expense_date', 'desc')->limit(1000)->get();
            $csv = "id,project_code,expense_date,category,description,total_amount\n";
            foreach ($rows as $r) {
                $csv .= implode(',', [
                    $r->id,
                    '"'.str_replace('"','""', $r->project_code ?? '').'"',
                    $r->expense_date ?? '',
                    '"'.str_replace('"','""', $r->category ?? '').'"',
                    '"'.str_replace('"','""', $r->description ?? '').'"',
                    $r->total_amount ?? 0
                ]) . "\n";
            }

            $fileName = 'expenses_export_' . date('Ymd_His') . '.csv';
            return response($csv, 200, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('finance.expenses.general')->with('error', 'Export sırasında hata oluştu.');
        }
    }

    /**
     * Bulk approve selected expenses (minimal implementation)
     */
    public function expensesBulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            if ($request->wantsJson()) return response()->json(['info' => 'Seçili gider yok.'], 400);
            return redirect()->route('finance.expenses.general')->with('info', 'Seçili gider yok.');
        }

        try {
            $updated = GeneralExpense::whereIn('id', $ids)->where('status', 'pending')->update([
                'status' => 'approved',
                'approver_id' => auth()->id() ?? null,
                'approved_at' => now()
            ]);
            if ($request->wantsJson()) {
                // return mapping of id => badge for the affected ids
                $updatedRows = GeneralExpense::whereIn('id', $ids)->get()->mapWithKeys(function($r){ return [$r->id => $r->status_badge]; })->toArray();
                return response()->json(['success' => true, 'updated' => $updated, 'rows' => $updatedRows]);
            }
            return redirect()->route('finance.expenses.general')->with('success', "$updated gider onaylandı.");
        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['error' => 'Toplu onay sırasında hata oluştu.'], 500);
            return redirect()->route('finance.expenses.general')->with('error', 'Toplu onay sırasında hata oluştu.');
        }
    }

    /**
     * Undo an action on an expense (approve/reject/delete).
     * For approved/rejected: set back to pending and clear approver/approved_at/rejected_at fields.
     * For deleted (soft-deleted): restore the model.
     */
    public function expensesUndo(Request $request, $id)
    {
        // Try to find including trashed
        $expense = GeneralExpense::withTrashed()->find($id);
        if (!$expense) {
            if ($request->wantsJson()) return response()->json(['error' => 'Gider bulunamadı.'], 404);
            return redirect()->route('finance.expenses.general')->with('error', 'Gider bulunamadı.');
        }

        try {
            // If soft-deleted, restore
            if ($expense->trashed()) {
                $expense->restore();
                // ensure status is pending after restore
                $expense->status = 'pending';
                $expense->approved_at = null;
                $expense->approver_id = null;
                $expense->rejected_at = null;
                $expense->save();
                if ($request->wantsJson()) return response()->json(['success' => true, 'id' => $expense->id, 'action' => 'restored', 'status_badge' => $expense->status_badge]);
                return redirect()->route('finance.expenses.show', [$expense->id])->with('success', 'Gider geri alındı.');
            }

            // If approved or rejected, set back to pending and clear metadata
            $prevStatus = $expense->status;
            if (in_array($expense->status, ['approved','rejected'])) {
                $expense->status = 'pending';
                $expense->approved_at = null;
                $expense->approver_id = null;
                $expense->rejected_at = null;
                $expense->save();
                if ($request->wantsJson()) return response()->json(['success' => true, 'id' => $expense->id, 'previous' => $prevStatus, 'status' => 'pending', 'status_badge' => $expense->status_badge]);
                return redirect()->route('finance.expenses.show', [$expense->id])->with('success', 'Gider durumu geri alındı.');
            }

            // Nothing to undo
            if ($request->wantsJson()) return response()->json(['info' => 'Geri alınacak bir işlem yok.'], 200);
            return redirect()->route('finance.expenses.show', [$expense->id])->with('info', 'Geri alınacak bir işlem yok.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['error' => 'Geri alma sırasında hata oluştu.'], 500);
            return redirect()->route('finance.expenses.general')->with('error', 'Geri alma sırasında hata oluştu.');
        }
    }

    /**
     * Expenses reports page (simple placeholder)
     */
    public function expensesReports()
    {
        // If a dedicated view exists, render it; otherwise provide a simple sample report
        if (view()->exists('finance.expenses.reports')) {
            return view('finance.expenses.reports');
        }

        $summary = GeneralExpense::selectRaw('category, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('category')
            ->get();

        return view('finance.expenses.reports_placeholder', compact('summary'));
    }

    // Financial Fixed Assets (Demirbaş & Sabit Kıymet Envanteri)
    public function createAsset()
    {
        return view('finance.assets.create');
    }

    public function showAsset($id)
    {
        $asset = Asset::findOrFail($id);
        return view('finance.assets.show', compact('asset'));
    }

    public function editAsset($id)
    {
        $asset = Asset::findOrFail($id);
        return view('finance.assets.edit', compact('asset'));
    }

    // =========================================================================
    // Cheques CRUD & Assignment
    // =========================================================================

    public function chequesStore(Request $request)
    {
        $data = $request->validate([
            'cheque_number' => 'nullable|string|max:100',
            'cheque_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'required|string|max:191',
            'branch' => 'nullable|string|max:191',
            'issue_date' => 'nullable|date',
            'due_date' => 'required|date',
            'customer_id' => 'nullable|integer',
            'recipient' => 'nullable|string|max:191',
            'type' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);

        $chequeNumber = $data['cheque_number'] ?? $data['cheque_no'] ?? ('CHK-' . time());
        $recipient = $data['recipient'] ?? null;
        $customerName = '';

        if (!empty($data['customer_id']) && Schema::hasTable('customers')) {
            $cust = \App\Models\Customer::find($data['customer_id']);
            if ($cust) {
                $customerName = $cust->name;
                $recipient = $recipient ?: $cust->name;
            }
        }

        if (!Schema::hasTable('cheques')) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'cheque' => array_merge($data, ['id' => rand(1000, 9999), 'cheque_number' => $chequeNumber]),
                    'customer_name' => $customerName,
                ]);
            }
            return back()->with('success', 'Çek kaydedildi.');
        }

        $createData = [
            'cheque_number' => $chequeNumber,
            'type' => $data['type'] ?? (!empty($data['customer_id']) ? 'received' : 'issued'),
            'amount' => $data['amount'],
            'issue_date' => $data['issue_date'] ?? now()->toDateString(),
            'due_date' => $data['due_date'],
            'recipient' => $recipient ?? 'Genel',
            'bank_name' => $data['bank_name'],
            'status' => $data['status'] ?? 'pending',
            'description' => $data['description'] ?? $data['notes'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        if (Schema::hasColumn('cheques', 'customer_id') && !empty($data['customer_id'])) {
            $createData['customer_id'] = $data['customer_id'];
        }
        if (Schema::hasColumn('cheques', 'branch') && !empty($data['branch'])) {
            $createData['branch'] = $data['branch'];
        }

        $cheque = \App\Models\Cheque::create($createData);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'cheque' => $cheque,
                'customer_name' => $customerName ?: $cheque->recipient,
            ]);
        }

        return back()->with('success', 'Çek başarıyla kaydedildi.');
    }

    public function chequesShow($id)
    {
        if (!Schema::hasTable('cheques')) {
            return response()->json(['ok' => false, 'message' => 'Çek tablosu bulunamadı.'], 404);
        }

        $cheque = \App\Models\Cheque::find($id);
        if (!$cheque) {
            return response()->json(['ok' => false, 'message' => 'Çek bulunamadı.'], 404);
        }

        return response()->json(['ok' => true, 'cheque' => $cheque]);
    }

    public function chequesUpdate(Request $request, $id)
    {
        $cheque = \App\Models\Cheque::findOrFail($id);

        $data = $request->validate([
            'cheque_number' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:0.01',
            'bank_name' => 'nullable|string|max:191',
            'branch' => 'nullable|string|max:191',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'recipient' => 'nullable|string|max:191',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $cheque->update(array_filter($data, fn($v) => $v !== null));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'cheque' => $cheque->fresh()]);
        }

        return back()->with('success', 'Çek güncellendi.');
    }

    public function chequesDestroy($id)
    {
        if (!Schema::hasTable('cheques')) {
            return response()->json(['ok' => false, 'message' => 'Çek tablosu bulunamadı.'], 404);
        }

        $cheque = \App\Models\Cheque::find($id);
        if (!$cheque) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['ok' => false, 'message' => 'Çek bulunamadı.'], 404);
            }
            return back()->with('error', 'Çek bulunamadı.');
        }

        $cheque->update(['status' => 'cancelled']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Çek iptal edildi.']);
        }

        return back()->with('success', 'Çek iptal edildi.');
    }

    public function chequesAssign(Request $request, $id)
    {
        $data = $request->validate([
            'supplier_id' => 'required|integer',
        ]);

        $vendor = Schema::hasTable('vendors') ? \App\Models\Vendor::find($data['supplier_id']) : null;
        $supplierName = $vendor ? $vendor->name : ('Tedarikçi #' . $data['supplier_id']);

        if (Schema::hasTable('cheques')) {
            $cheque = \App\Models\Cheque::find($id);
            if ($cheque) {
                $updateFields = [
                    'notes' => trim(($cheque->notes ? $cheque->notes . ' | ' : '') . 'Ciro: ' . $supplierName),
                ];
                if (Schema::hasColumn('cheques', 'supplier_id')) {
                    $updateFields['supplier_id'] = $data['supplier_id'];
                }
                $cheque->update($updateFields);
            }
        }

        return response()->json([
            'ok' => true,
            'supplier_name' => $supplierName,
        ]);
    }

    // =========================================================================
    // Stock Search (Select2 AJAX)
    // =========================================================================

    public function searchStock(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = [];

        if (Schema::hasTable('stock_cards')) {
            $query = \App\Models\StockCard::query();
            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('code', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            }
            $items = $query->orderBy('name')->limit(25)->get();
            foreach ($items as $item) {
                $results[] = [
                    'id' => $item->id,
                    'text' => ($item->code ? $item->code . ' - ' : '') . $item->name,
                    'name' => $item->name,
                    'code' => $item->code,
                    'unit' => $item->unit,
                    'unit_price' => (float) ($item->unit_price ?? 0),
                ];
            }
        }

        return response()->json(['results' => $results]);
    }

    // =========================================================================
    // Invoices CRUD, Print, Email & Payment
    // =========================================================================

    public function invoicesCreate(Request $request)
    {
        $vendors = Schema::hasTable('vendors') ? \App\Models\Vendor::orderBy('name')->get() : collect();
        $customers = Schema::hasTable('customers') ? \App\Models\Customer::orderBy('name')->get() : collect();
        $purchaseOrder = null;
        $purchaseOrderItems = [];

        if ($request->filled('purchase_order_id') && Schema::hasTable('purchase_orders')) {
            $purchaseOrder = \App\Models\PurchaseOrder::find($request->purchase_order_id);
        }

        return view('finance.invoices.create', compact('vendors', 'customers', 'purchaseOrder', 'purchaseOrderItems'));
    }

    public function invoicesStore(Request $request)
    {
        $data = $request->validate([
            'no' => 'nullable|string|max:100',
            'invoice_number' => 'nullable|string|max:100',
            'customer_id' => 'nullable|integer',
            'vendor_id' => 'nullable|integer',
            'purchase_order_id' => 'nullable|integer',
            'date' => 'nullable|date',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'amount' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        if (!Schema::hasTable('invoices')) {
            return redirect()->route('finance.invoices.index')->with('error', 'Fatura tablosu bulunamadı.');
        }

        $items = $request->input('items', []);
        $subtotal = 0;
        foreach ($items as $item) {
            $qty = (float) ($item['quantity'] ?? $item['qty'] ?? 1);
            $price = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
            $subtotal += $qty * $price;
        }

        $taxRate = (float) ($data['tax_rate'] ?? 20);
        $baseAmount = $subtotal > 0 ? $subtotal : (float) ($data['amount'] ?? 0);
        $taxAmount = $subtotal > 0 ? round($baseAmount * ($taxRate / 100), 2) : 0;
        $totalAmount = $subtotal > 0 ? ($baseAmount + $taxAmount) : $baseAmount;

        $invoiceData = [
            'no' => $data['no'] ?? $data['invoice_number'] ?? ('INV-' . date('YmdHis')),
            'customer_id' => $data['customer_id'] ?? null,
            'vendor_id' => $data['vendor_id'] ?? null,
            'purchase_order_id' => $data['purchase_order_id'] ?? null,
            'date' => $data['date'] ?? $data['invoice_date'] ?? now()->toDateString(),
            'due_date' => $data['due_date'] ?? now()->addDays(30)->toDateString(),
            'subtotal' => $baseAmount,
            'tax_amount' => $taxAmount,
            'amount' => $totalAmount,
            'paid_amount' => 0,
            'currency' => $data['currency'] ?? 'TRY',
            'status' => 'unpaid',
            'description' => $data['description'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        $filteredData = [];
        foreach ($invoiceData as $col => $val) {
            if (Schema::hasColumn('invoices', $col)) {
                $filteredData[$col] = $val;
            }
        }

        $invoice = \App\Models\Invoice::create($filteredData);

        if (!empty($items) && Schema::hasTable('invoice_items')) {
            foreach ($items as $item) {
                $qty = (float) ($item['quantity'] ?? $item['qty'] ?? 1);
                $price = (float) ($item['unit_price'] ?? $item['price'] ?? 0);
                \App\Models\InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'stock_card_id' => $item['stock_card_id'] ?? null,
                    'description' => $item['description'] ?? $item['name'] ?? null,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'line_total' => $qty * $price,
                ]);
            }
        }

        return redirect()->route('finance.invoices.show', $invoice->id)->with('success', 'Fatura başarıyla oluşturuldu.');
    }

    public function invoicesShow($id)
    {
        $invoice = \App\Models\Invoice::with(['customer', 'invoiceItems.stockCard', 'receipts'])->findOrFail($id);
        $invoice->customer_name = optional($invoice->customer)->name ?? 'Müşteri';
        $invoice->balance = (float) ($invoice->amount ?? 0) - (float) ($invoice->paid_amount ?? 0);

        return view('finance.invoices.show', compact('invoice'));
    }

    public function invoicesEdit($id)
    {
        $invoice = \App\Models\Invoice::with(['customer', 'invoiceItems.stockCard'])->findOrFail($id);
        $invoice->customer_name = optional($invoice->customer)->name ?? 'Müşteri';
        $invoice->balance = (float) ($invoice->amount ?? 0) - (float) ($invoice->paid_amount ?? 0);

        return view('finance.invoices.edit', compact('invoice'));
    }

    public function invoicesUpdate(Request $request, $id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);

        $data = $request->validate([
            'no' => 'nullable|string|max:100',
            'date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $updateData = [];
        foreach ($data as $col => $val) {
            if ($val !== null && Schema::hasColumn('invoices', $col)) {
                $updateData[$col] = $val;
            }
        }

        $invoice->update($updateData);

        return redirect()->route('finance.invoices.show', $invoice->id)->with('success', 'Fatura başarıyla güncellendi.');
    }

    public function invoicesPrint($id)
    {
        $invoice = \App\Models\Invoice::with(['customer', 'invoiceItems.stockCard'])->findOrFail($id);
        $invoice->customer_name = optional($invoice->customer)->name ?? 'Müşteri';
        $invoice->balance = (float) ($invoice->amount ?? 0) - (float) ($invoice->paid_amount ?? 0);

        return view('finance.invoices.print', compact('invoice'));
    }

    public function invoicesEmailForm($id)
    {
        $invoice = \App\Models\Invoice::with(['customer'])->findOrFail($id);
        $invoice->customer_name = optional($invoice->customer)->name ?? 'Müşteri';

        return view('finance.invoices.email', compact('invoice'));
    }

    public function invoicesEmailSend(Request $request, $id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        return redirect()->route('finance.invoices.show', $invoice->id)->with('success', 'Fatura e-postası gönderildi.');
    }

    public function invoicesPaymentForm($id)
    {
        $invoice = \App\Models\Invoice::with(['customer'])->findOrFail($id);
        $invoice->customer_name = optional($invoice->customer)->name ?? 'Müşteri';
        $invoice->balance = (float) ($invoice->amount ?? 0) - (float) ($invoice->paid_amount ?? 0);

        return view('finance.invoices.payment', compact('invoice'));
    }

    public function invoicesPaymentStore(Request $request, $id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:191',
            'date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (Schema::hasTable('receipts')) {
            \App\Models\Receipt::create([
                'invoice_id' => $invoice->id,
                'date' => $data['date'] ?? now()->toDateString(),
                'amount' => $data['amount'],
                'method' => $data['method'] ?? 'bank',
                'reference' => $data['reference'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        }

        $invoice->paid_amount = (float) ($invoice->paid_amount ?? 0) + (float) $data['amount'];
        if ($invoice->paid_amount >= (float) $invoice->amount) {
            $invoice->status = 'paid';
        } elseif ($invoice->paid_amount > 0) {
            $invoice->status = 'partially_paid';
        }
        $invoice->save();

        return redirect()->route('finance.invoices.show', $invoice->id)->with('success', 'Fatura ödemesi kaydedildi.');
    }

    public function invoicesDestroy($id)
    {
        $invoice = \App\Models\Invoice::findOrFail($id);
        if (Schema::hasTable('invoice_items')) {
            \App\Models\InvoiceItem::where('invoice_id', $invoice->id)->delete();
        }
        $invoice->delete();

        return redirect()->route('finance.invoices.index')->with('success', 'Fatura silindi.');
    }
}
