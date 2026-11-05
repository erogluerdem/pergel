<?php

namespace App\Services\Finance;

class KdvCalculator
{
    /**
     * Calculate KDV amount and total for a line amount and rate.
     *
     * @param float $amount net amount (price without KDV)
     * @param float|null $rate percentage (e.g. 18 for %18). null or 0 means no KDV
     * @return array ['kdv_amount' => float, 'total' => float]
     */
    public static function calculate(float $amount, ?float $rate = null): array
    {
        $rate = $rate ?? 0.0;
        $kdvAmount = round($amount * ($rate / 100.0), 2);
        $total = round($amount + $kdvAmount, 2);

        return ['kdv_amount' => $kdvAmount, 'total' => $total];
    }
}
