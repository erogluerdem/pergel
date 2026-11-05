<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyRate;

class CurrencyRateController extends Controller
{
    /**
     * Döviz kuru yönetimi sayfası
     */
    public function index(Request $request)
    {
        $fromCurrency = $request->query('from');
        
        $query = CurrencyRate::query();

        if ($fromCurrency) {
            $query->where('from_currency', $fromCurrency);
        }

        $rates = $query->orderBy('effective_date', 'desc')
            ->orderBy('from_currency')
            ->paginate(20);

        $currencies = CurrencyRate::select('from_currency')
            ->distinct()
            ->pluck('from_currency')
            ->sort()
            ->values();

        // İstatistikler
        $stats = [
            'total_currencies' => $currencies->count(),
            'total_rates' => CurrencyRate::count(),
        ];

        // Son güncelleme tarihi
        $lastUpdate = CurrencyRate::orderBy('updated_at', 'desc')->first()?->updated_at;

        return view('finance.currency.index', compact('rates', 'currencies', 'fromCurrency', 'stats', 'lastUpdate'));
    }

    /**
     * Döviz kuru ekleme formu göster
     */
    public function create()
    {
        return view('finance.currency.create');
    }

    /**
     * Tekil döviz kuru göster
     */
    public function show($id)
    {
        $currency = CurrencyRate::findOrFail($id);
        return view('finance.currency.show', compact('currency'));
    }

    /**
     * Döviz kuru kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_currency' => 'required|string|size:3|uppercase',
            'to_currency' => 'required|string|size:3|uppercase',
            'rate' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'source' => 'required|in:manual,api,bank',
            'rate_bid' => 'nullable|numeric|min:0',
            'rate_ask' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            CurrencyRate::create($validated);

            return redirect()->route('finance.currency.index')
                ->with('success', 'Döviz kuru başarıyla kaydedildi!');
        } catch (\Exception $e) {
            return back()->with('error', 'Kayıt başarısız: ' . $e->getMessage());
        }
    }

    /**
     * Döviz kuru düzenle
     */
    public function edit($id)
    {
        $rate = CurrencyRate::findOrFail($id);
        return view('finance.currency.edit', compact('rate'));
    }

    /**
     * Döviz kuru güncelle
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0',
            'source' => 'required|in:manual,api,bank',
            'rate_bid' => 'nullable|numeric|min:0',
            'rate_ask' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $rate = CurrencyRate::findOrFail($id);
        $rate->update($validated);

        return redirect()->route('finance.currency.index')
            ->with('success', 'Döviz kuru başarıyla güncellendi!');
    }

    /**
     * Döviz kuru sil
     */
    public function destroy($id)
    {
        $rate = CurrencyRate::findOrFail($id);
        $rate->delete();

        return redirect()->route('finance.currency.index')
            ->with('success', 'Döviz kuru silinmiştir.');
    }

    /**
     * Toplu döviz kuru ekle (API)
     */
    public function bulkImport(Request $request)
    {
        $validated = $request->validate([
            'rates' => 'required|array',
            'rates.*.from' => 'required|string|size:3',
            'rates.*.to' => 'required|string|size:3',
            'rates.*.rate' => 'required|numeric',
            'rates.*.date' => 'required|date',
        ]);

        $imported = 0;
        foreach ($validated['rates'] as $rateData) {
            try {
                CurrencyRate::create([
                    'from_currency' => strtoupper($rateData['from']),
                    'to_currency' => strtoupper($rateData['to']),
                    'rate' => $rateData['rate'],
                    'effective_date' => $rateData['date'],
                    'source' => 'api',
                ]);
                $imported++;
            } catch (\Exception $e) {
                // İçe aktar hatasını kaydet
            }
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'message' => "$imported döviz kuru başarıyla içe aktarıldı.",
        ]);
    }

    /**
     * Döviz kuru getir (API)
     */
    public function getRate($from, $to, $date = null)
    {
        try {
            $rate = CurrencyRate::getRate($from, $to, $date);

            if (!$rate) {
                return response()->json(['error' => 'Rate not found'], 404);
            }

            return response()->json([
                'from' => $rate->from_currency,
                'to' => $rate->to_currency,
                'rate' => $rate->getAverageRateAttribute(),
                'bid' => $rate->rate_bid,
                'ask' => $rate->rate_ask,
                'date' => $rate->effective_date->toDateString(),
                'source' => $rate->source,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Para dönüştür (API)
     */
    public function convert(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
            'date' => 'nullable|date',
        ]);

        try {
            $amount = CurrencyRate::convert(
                $validated['amount'],
                strtoupper($validated['from']),
                strtoupper($validated['to']),
                $validated['date'] ?? now()->toDateString()
            );

            return response()->json([
                'original_amount' => $validated['amount'],
                'from_currency' => $validated['from'],
                'converted_amount' => round($amount, 2),
                'to_currency' => $validated['to'],
                'date' => $validated['date'] ?? now()->toDateString(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
