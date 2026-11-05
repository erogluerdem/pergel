<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\CurrencyExchange;
use App\Models\Invoice;
use Illuminate\Http\Request;

class CurrencyExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager,finance', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $exchanges = CurrencyExchange::with('invoice')
            ->orderBy('exchange_date', 'desc')
            ->paginate(15);
        
        return view('currency_exchanges.index', compact('exchanges'));
    }

    public function create()
    {
        $currencies = Currency::where('is_active', true)->pluck('code', 'id');
        $invoices = Invoice::whereNull('currency_id')
            ->orWhere('currency_id', '!=', 1)
            ->pluck('invoice_number', 'id');
        
        return view('currency_exchanges.create', compact('currencies', 'invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'from_currency' => 'required|exists:currencies,id',
            'to_currency' => 'required|exists:currencies,id|different:from_currency',
            'from_amount' => 'required|numeric|min:0.01',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'exchange_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['to_amount'] = $validated['from_amount'] * $validated['exchange_rate'];
        if ($validated['commission_percentage'] ?? 0) {
            $validated['commission_amount'] = ($validated['to_amount'] * $validated['commission_percentage']) / 100;
        }

        $exchange = CurrencyExchange::create($validated);
        
        return redirect()->route('currency_exchanges.show', $exchange)->with('success', 'Döviz işlemi kaydedildi.');
    }

    public function show(CurrencyExchange $currencyExchange)
    {
        $currencyExchange->load('invoice');
        
        return view('currency_exchanges.show', compact('currencyExchange'));
    }

    public function edit(CurrencyExchange $currencyExchange)
    {
        $currencies = Currency::where('is_active', true)->pluck('code', 'id');
        
        return view('currency_exchanges.edit', compact('currencyExchange', 'currencies'));
    }

    public function update(Request $request, CurrencyExchange $currencyExchange)
    {
        $validated = $request->validate([
            'exchange_rate' => 'required|numeric|min:0.0001',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'exchange_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['to_amount'] = $currencyExchange->from_amount * $validated['exchange_rate'];
        if ($validated['commission_percentage'] ?? 0) {
            $validated['commission_amount'] = ($validated['to_amount'] * $validated['commission_percentage']) / 100;
        }

        $currencyExchange->update($validated);
        
        return redirect()->route('currency_exchanges.show', $currencyExchange)->with('success', 'Döviz işlemi güncellendi.');
    }

    public function destroy(CurrencyExchange $currencyExchange)
    {
        $currencyExchange->delete();
        
        return redirect()->route('currency_exchanges.index')->with('success', 'Döviz işlemi silindi.');
    }

    public function conversionRate(Request $request)
    {
        $from = Currency::find($request->input('from_currency'));
        $to = Currency::find($request->input('to_currency'));

        if (!$from || !$to) {
            return response()->json(['error' => 'Para birimi bulunamadı.'], 404);
        }

        $rate = $from->to_usd / $to->to_usd;

        return response()->json(['rate' => round($rate, 6)]);
    }
}
