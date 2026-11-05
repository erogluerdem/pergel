<?php

namespace App\Services;

use App\Models\CurrencyRate;
use App\Models\CashBankTransaction;
use App\Models\Payment;
use App\Models\Voucher;
use Carbon\Carbon;

class MultiCurrencyService
{
    /**
     * Para birimini dönüştür
     */
    public function convert($amount, $fromCurrency, $toCurrency, $date = null): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = CurrencyRate::getRate($fromCurrency, $toCurrency, $date);
        
        if (!$rate) {
            throw new \Exception("Döviz kuru bulunamadı: {$fromCurrency}/{$toCurrency}");
        }

        return $amount * $rate->getAverageRateAttribute();
    }

    /**
     * Tüm işlemleri TRY'ye dönüştür
     */
    public function convertAllToTRY($amount, $currency, $date = null): float
    {
        if ($currency === 'TRY') {
            return $amount;
        }

        return $this->convert($amount, $currency, 'TRY', $date);
    }

    /**
     * Döviz kuru ekle
     */
    public function addRate($fromCurrency, $toCurrency, $rate, $date = null, $source = 'manual'): CurrencyRate
    {
        $date = $date ?? now()->toDateString();

        return CurrencyRate::updateOrCreate(
            [
                'from_currency' => $fromCurrency,
                'to_currency' => $toCurrency,
                'effective_date' => $date,
            ],
            [
                'rate' => $rate,
                'source' => $source,
            ]
        );
    }

    /**
     * Ödemeyi dönüştür
     */
    public function convertPayment(Payment $payment, $targetCurrency): float
    {
        $currency = $payment->currency ?? 'TRY';
        return $this->convert($payment->requested, $currency, $targetCurrency);
    }

    /**
     * İşlemi dönüştür
     */
    public function convertTransaction(CashBankTransaction $transaction, $targetCurrency): float
    {
        $currency = $transaction->currency ?? 'TRY';
        return $this->convert($transaction->amount, $currency, $targetCurrency);
    }

    /**
     * Taşınan miktarı dönüştür
     */
    public function convertVoucher(Voucher $voucher, $targetCurrency): float
    {
        $currency = $voucher->currency ?? 'TRY';
        return $this->convert($voucher->amount, $currency, $targetCurrency);
    }

    /**
     * Çoklu para birimi rapor
     */
    public function getMultiCurrencyReport($transactions)
    {
        $report = [];
        
        foreach ($transactions as $transaction) {
            $currency = $transaction->currency ?? 'TRY';
            
            if (!isset($report[$currency])) {
                $report[$currency] = [
                    'currency' => $currency,
                    'inflow' => 0,
                    'outflow' => 0,
                    'net' => 0,
                    'count' => 0,
                    'inflow_tly' => 0,
                    'outflow_tly' => 0,
                    'net_tly' => 0,
                ];
            }

            $report[$currency]['count']++;

            if ($transaction->direction === 'in') {
                $report[$currency]['inflow'] += $transaction->amount;
                $report[$currency]['inflow_tly'] += $transaction->amount_tl ?? $this->convertToTRY($transaction->amount, $currency, $transaction->transaction_date);
            } else {
                $report[$currency]['outflow'] += $transaction->amount;
                $report[$currency]['outflow_tly'] += $transaction->amount_tl ?? $this->convertToTRY($transaction->amount, $currency, $transaction->transaction_date);
            }
        }

        // Net hesapla
        foreach ($report as $currency => $data) {
            $report[$currency]['net'] = $data['inflow'] - $data['outflow'];
            $report[$currency]['net_tly'] = $data['inflow_tly'] - $data['outflow_tly'];
        }

        return $report;
    }

    /**
     * TRY'ye dönüştür
     */
    private function convertToTRY($amount, $currency, $date): float
    {
        if ($currency === 'TRY') {
            return $amount;
        }

        try {
            return $this->convert($amount, $currency, 'TRY', $date);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Tüm aktif para birimlerini getir
     */
    public function getActiveCurrencies()
    {
        return CurrencyRate::select('from_currency')
            ->distinct()
            ->pluck('from_currency')
            ->merge(
                CurrencyRate::select('to_currency')
                    ->distinct()
                    ->pluck('to_currency')
            )
            ->unique()
            ->sort()
            ->values();
    }
}
