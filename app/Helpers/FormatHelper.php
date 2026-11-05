<?php

namespace App\Helpers;

/**
 * FormatHelper - Common formatting utilities
 */
class FormatHelper
{
    /**
     * Format budget/currency to short string like 1.2M, 350k or 1.2B
     * 
     * @param float|int|null $number
     * @return string
     */
    public static function budgetShort($number): string
    {
        if (!is_numeric($number)) {
            return (string)$number;
        }

        $number = (float)$number;

        if ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        }

        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }

        if ($number >= 1000) {
            return round($number / 1000, 1) . 'k';
        }

        return number_format($number, 0, ',', '.');
    }

    /**
     * Format currency with currency symbol and decimals
     * 
     * @param float|int|null $amount
     * @param string $currency Currency code (USD, TRY, EUR)
     * @param int $decimals
     * @return string
     */
    public static function currency($amount, string $currency = 'TRY', int $decimals = 2): string
    {
        if (!is_numeric($amount)) {
            return (string)$amount;
        }

        $symbols = [
            'TRY' => '₺',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
        ];

        $symbol = $symbols[$currency] ?? $currency;
        $formatted = number_format((float)$amount, $decimals, ',', '.');

        return "{$symbol} {$formatted}";
    }

    /**
     * Format percentage with optional symbol
     * 
     * @param float|int|null $value
     * @param int $decimals
     * @param bool $includeSymbol
     * @return string
     */
    public static function percentage($value, int $decimals = 2, bool $includeSymbol = true): string
    {
        if (!is_numeric($value)) {
            return (string)$value;
        }

        $formatted = number_format((float)$value, $decimals, ',', '.');
        
        return $includeSymbol ? $formatted . '%' : $formatted;
    }

    /**
     * Format file size from bytes
     * 
     * @param int $bytes
     * @param int $decimals
     * @return string
     */
    public static function fileSize(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $decimals) . ' ' . $units[$pow];
    }

    /**
     * Truncate text with ellipsis
     * 
     * @param string $text
     * @param int $length
     * @param string $ending
     * @return string
     */
    public static function truncate(string $text, int $length = 100, string $ending = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . $ending;
    }

    /**
     * Format phone number (Turkish format)
     * 
     * @param string $phone
     * @return string Formatted phone number
     */
    public static function phoneNumber(string $phone): string
    {
        $clean = preg_replace('/\D/', '', $phone);
        
        if (strlen($clean) === 10) {
            return sprintf('+90 (%s) %s-%s', 
                substr($clean, 0, 3), 
                substr($clean, 3, 3), 
                substr($clean, 6)
            );
        } elseif (strlen($clean) === 11 && $clean[0] === '0') {
            $clean = substr($clean, 1);
            return sprintf('+90 (%s) %s-%s', 
                substr($clean, 0, 3), 
                substr($clean, 3, 3), 
                substr($clean, 6)
            );
        }

        return $phone;
    }

    /**
     * Format IBAN for display
     * 
     * @param string $iban
     * @return string Formatted IBAN with spaces
     */
    public static function iban(string $iban): string
    {
        $iban = strtoupper(preg_replace('/\s+/', '', $iban));
        return implode(' ', str_split($iban, 4));
    }

    /**
     * Format large numbers with thousands separator
     * 
     * @param float|int $number
     * @param int $decimals
     * @param string $decimalSeparator
     * @param string $thousandsSeparator
     * @return string
     */
    public static function number($number, int $decimals = 0, string $decimalSeparator = ',', string $thousandsSeparator = '.'): string
    {
        if (!is_numeric($number)) {
            return (string)$number;
        }

        return number_format((float)$number, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
