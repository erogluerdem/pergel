<?php

namespace App\Services;

use App\Models\KdvRate;

class FinanceService
{
    /**
     * Get allowed KDV options (strings)
     */
    public static function getAllowedKdvOptions(): array
    {
        try {
            $dbRates = KdvRate::where('active', true)->orderBy('sort')->pluck('rate')->toArray();
            if (count($dbRates)) return array_map('strval', $dbRates);
        } catch (\Exception $e) {
            // ignore and fallback to config
        }

        return array_map('strval', config('finance.kdv_rates', [0,1,10,20]));
    }

    /**
     * Resolve an integer KDV percent from a requested value (string|null) or fallback to default
     */
    public static function resolveKdvPercent($requested): int
    {
        $allowed = self::getAllowedKdvOptions();
        if ($requested === null) {
            $def = config('finance.default_kdv');
            return $def === null ? 0 : intval($def);
        }
        if (in_array((string)$requested, $allowed, true)) return intval($requested);
        // fallback
        $def = config('finance.default_kdv');
        return $def === null ? 0 : intval($def);
    }

    /**
     * Compute KDV amount for a base amount and percent
     */
    public static function kdvAmount(float $amount, int $percent): float
    {
        if ($percent === 0) return 0.0;
        return $amount * ($percent / 100.0);
    }

    /**
     * Apply KDV to an amount and return inclusive amount
     */
    public static function applyKdv(float $amount, int $percent): float
    {
        return $amount * (1 + ($percent / 100.0));
    }
}
