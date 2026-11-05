<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\CapitalTransaction;
use App\Models\CashBankTransaction;
use App\Models\AccountTransaction;

class PartnerController extends Controller
{

    // API için
    public function index()
    {
        $partners = Partner::with('capitalTransactions')->get();
        return response()->json($partners);
    }

    // Blade için
    public function bladeIndex()
    {
        // Eager-load capital transactions and their cash/bank transaction relation
    $partners = Partner::with(['capitalTransactions.cashBankTransaction'])->get();
    // Get bank accounts for destination selection
    $bankAccounts = \App\Models\Account::where('type', 'bank')->orWhereNotNull('bank_name')->orderBy('name')->get();

    // Get last used exchange rate per currency to prefill the form (if any)
    $lastRates = \App\Models\CashBankTransaction::whereNotNull('exchange_rate')
        ->orderBy('id', 'desc')
        ->get()
        ->unique('currency')
        ->pluck('exchange_rate', 'currency')
        ->toArray();

        // Compute totals per partner (aldıkları = in, verdikleri = out)
        $partnerTotals = [];
        foreach ($partners as $partner) {
            $in = 0; $out = 0;
            foreach ($partner->capitalTransactions as $ct) {
                // cashBankTransaction may be null for legacy records
                $cb = $ct->cashBankTransaction;
                if ($cb) {
                    if ($cb->direction === 'in') $in += $cb->amount;
                    elseif ($cb->direction === 'out') $out += $cb->amount;
                } else {
                    // fallback: if there's no cash/bank record, assume positive amounts are partner->company (out)
                    if ($ct->amount >= 0) $out += $ct->amount;
                    else $in += abs($ct->amount);
                }
            }
            $partnerTotals[$partner->id] = ['in' => $in, 'out' => $out];
        }

    return view('partners.index', compact('partners', 'partnerTotals', 'bankAccounts', 'lastRates'));
    }


    // API için
    public function store(Request $request)
    {
        $partner = Partner::create($request->only(['name', 'share']));
        return response()->json($partner);
    }

    // Blade için
    public function bladeStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'share' => 'required|numeric|min:0|max:100',
        ]);

        $partner = Partner::create($validated);

        if ($request->wantsJson()) {
            return response()->json($partner, 201);
        }

        return redirect()->route('partners.index')->with('success', 'Ortak başarıyla eklendi!');
    }


    // API için
    public function addCapital(Request $request, $partnerId)
    {
        $partner = Partner::findOrFail($partnerId);
        $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|in:cash,bank',
            'transaction_date' => 'required|date',
            'bank_account_id' => 'nullable|required_if:source,bank|exists:accounts,id',
            'currency' => 'required|string|in:TRY,USD,EUR',
            'exchange_rate' => 'nullable|numeric',
        ]);
        $capital = $partner->capitalTransactions()->create($request->only(['amount', 'source', 'description', 'transaction_date']));
        $exchangeRate = $request->input('exchange_rate');
        $currency = $request->input('currency') ?: 'TRY';
        $amountTl = null;
        if ($currency !== 'TRY') {
            // require exchange rate for non-TRY
            if (! $exchangeRate) {
                return response()->json(['error' => 'exchange_rate required for foreign currency'], 422);
            }
            $amountTl = round($request->amount * $exchangeRate, 2);
        }

        CashBankTransaction::create([
            'type' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
            // Partner is providing capital to the company -> counted as 'out' for the partner
            'direction' => 'out',
            'capital_transaction_id' => $capital->id,
            'account_id' => $request->input('bank_account_id') ?: null,
            'currency' => $currency,
            'exchange_rate' => $exchangeRate ?: null,
            'amount_tl' => $amountTl,
        ]);
        // For API calls also ensure target bank or currency cash account exists and post account transaction
        $cur = $request->input('currency') ?: 'TRY';
        $targetAccount = null;
        if ($request->source === 'bank' && !empty($request->bank_account_id)) {
            $targetAccount = \App\Models\Account::find($request->bank_account_id);
        }
        if (! $targetAccount) {
            $targetAccount = \App\Models\Account::where('type', 'cash')->where('currency', $cur)->first();
        }
        if (! $targetAccount) {
            $targetAccount = \App\Models\Account::create([
                'name' => 'Merkez Kasa' . ($cur !== 'TRY' ? ' - ' . $cur : ''),
                'type' => 'cash',
                'currency' => $cur,
                'balance' => 0,
                'active' => 1,
                'opening_date' => now()->toDateString(),
                'created_by' => auth()->id() ?? null,
            ]);
        }

        \App\Models\AccountTransaction::create([
            'account_id' => $targetAccount->id,
            'date' => $request->transaction_date,
            'description' => 'Ortak sermaye girişi: ' . $partner->name . ' - ' . $request->description,
            'debit' => $request->amount,
            'credit' => 0,
            'balance' => ($targetAccount->balance + $request->amount),
            'created_by' => auth()->id() ?? null,
        ]);

        $targetAccount->balance += $request->amount;
        $targetAccount->save();
        return response()->json($capital);
    }

    // Blade için
    public function bladeAddCapital(Request $request, $partnerId)
    {
        $partner = Partner::findOrFail($partnerId);
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|in:cash,bank',
            'transaction_date' => 'required|date',
            'bank_account_id' => 'nullable|required_if:source,bank|exists:accounts,id',
            'currency' => 'required|string|in:TRY,USD,EUR',
            'exchange_rate' => 'nullable|numeric|required_if:currency,USD,EUR',
        ]);

        $capital = $partner->capitalTransactions()->create($request->only(['amount', 'source', 'description', 'transaction_date']));
        
        $exchangeRate = $request->input('exchange_rate');
        $currency = $request->input('currency') ?: 'TRY';
        $amountTl = null;
        if ($currency !== 'TRY') {
            if (! $exchangeRate) {
                // This case should be caught by validation, but as a fallback:
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Kur bilgisi gerekli'], 422);
                }
                return redirect()->back()->withErrors(['exchange_rate' => 'Kur bilgisi gerekli'])->withInput();
            }
            $amountTl = round($request->amount * $exchangeRate, 2);
        }

        CashBankTransaction::create([
            'type' => $request->source,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date,
            // Partner is providing capital to the company -> counted as 'out' for the partner
            'direction' => 'out',
            'capital_transaction_id' => $capital->id,
            'account_id' => $request->input('bank_account_id') ?: null,
            'currency' => $currency,
            'exchange_rate' => $exchangeRate ?: null,
            'amount_tl' => $amountTl,
        ]);

        // Post transaction to the selected bank account when source === 'bank', or to the currency-specific cash account
        $cur = $request->input('currency') ?: 'TRY';
        $targetAccount = null;
        if ($request->source === 'bank' && !empty($request->bank_account_id)) {
            $targetAccount = \App\Models\Account::find($request->bank_account_id);
        }
        if (! $targetAccount) {
            $targetAccount = \App\Models\Account::where('type', 'cash')->where('currency', $cur)->first();
        }
        if (! $targetAccount) {
            $targetAccount = \App\Models\Account::create([
                'name' => 'Merkez Kasa' . ($cur !== 'TRY' ? ' - ' . $cur : ''),
                'type' => 'cash',
                'currency' => $cur,
                'balance' => 0,
                'active' => 1,
                'opening_date' => now()->toDateString(),
                'created_by' => auth()->id() ?? null,
            ]);
        }

        \App\Models\AccountTransaction::create([
            'account_id' => $targetAccount->id,
            'date' => $request->transaction_date,
            'description' => 'Ortak sermaye girişi: ' . $partner->name . ' - ' . $request->description,
            'debit' => $request->amount,
            'credit' => 0,
            'balance' => ($targetAccount->balance + $request->amount),
            'created_by' => auth()->id() ?? null,
        ]);

        // Update the target account balance
        $targetAccount->balance += $request->amount;
        $targetAccount->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Sermaye başarıyla eklendi.'], 200);
        }

        return redirect()->route('partners.index')->with('success', 'Sermaye başarıyla eklendi.');
    }

    // Genel işlemler sayfası (server-side pagination + filtreler)
    public function transactionsIndex(Request $request)
    {
        $query = CapitalTransaction::with(['partner', 'cashBankTransaction.account']);

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('transaction_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('transaction_date', '<=', $request->to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(25)->withQueryString();
        $partners = Partner::orderBy('name')->get();

        return view('partners.transactions', compact('transactions', 'partners'));
    }

    // Düzenleme formu
    public function transactionEdit($id)
    {
        $tx = CapitalTransaction::with('cashBankTransaction', 'partner')->findOrFail($id);
        return view('partners.transaction-edit', compact('tx'));
    }

    // Güncelle
    public function transactionUpdate(Request $request, $id)
    {
        $tx = CapitalTransaction::findOrFail($id);
        $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|in:cash,bank',
            'transaction_date' => 'required|date',
            'bank_account_id' => 'nullable|required_if:source,bank|exists:accounts,id',
        ]);
        $data = $request->only(['amount', 'source', 'description', 'transaction_date']);
        $tx->update($data);

        // sync cash bank transaction if exists
        if ($tx->cashBankTransaction) {
            $tx->cashBankTransaction->update([
                'type' => $request->source,
                'amount' => $request->amount,
                'description' => $request->description,
                'transaction_date' => $request->transaction_date,
                'account_id' => $request->input('bank_account_id') ?: $tx->cashBankTransaction->account_id,
                'currency' => $request->input('currency') ?: $tx->cashBankTransaction->currency,
            ]);
        }

        return redirect()->route('partners.transactions')->with('success', 'İşlem güncellendi.');
    }

    // Sil
    public function transactionDelete($id)
    {
        $tx = CapitalTransaction::with('cashBankTransaction')->findOrFail($id);
        if ($tx->cashBankTransaction) {
            $tx->cashBankTransaction->delete();
        }
        $tx->delete();

        return redirect()->route('partners.transactions')->with('success', 'İşlem silindi.');
    }

    public function bladeUpdate(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'share' => 'required|numeric|min:0|max:100',
        ]);

        $partner->update($validated);

        if ($request->wantsJson()) {
            return response()->json($partner);
        }

        return redirect()->route('partners.index')->with('success', 'Ortak güncellendi.');
    }

    public function bladeDestroy(Request $request, Partner $partner)
    {
        // Optional: Check if partner has transactions and prevent deletion if so
        if ($partner->capitalTransactions()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Bu ortağın sermaye hareketleri olduğu için silinemez.'], 422);
            }
            return redirect()->route('partners.index')->with('error', 'Bu ortağın sermaye hareketleri olduğu için silinemez.');
        }

        $partner->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Ortak başarıyla silindi.']);
        }

        return redirect()->route('partners.index')->with('success', 'Ortak silindi.');
    }
}
