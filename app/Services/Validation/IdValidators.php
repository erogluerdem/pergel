<?php

namespace App\Services\Validation;

class IdValidators
{
    /**
     * Validate Turkish TCKN (11 digits) algorithmically.
     */
    public static function validateTckn(?string $tckn): bool
    {
        if (!$tckn) return false;
        $t = preg_replace('/\D/', '', $tckn);
        if (strlen($t) !== 11) return false;
        if ($t[0] === '0') return false;

        $digits = array_map('intval', str_split($t));

        $sumOdd = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $sumEven = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        $check10 = ((($sumOdd * 7) - $sumEven) % 10 + 10) % 10; // ensure positive
        if ($digits[9] !== $check10) return false;

        $sumFirst10 = array_sum(array_slice($digits, 0, 10));
        $check11 = $sumFirst10 % 10;
        if ($digits[10] !== $check11) return false;

        return true;
    }

    /**
     * Validate VKN (Vergi Kimlik Numarası) - 10 digits numeric
     * This implements a common checksum used for VKN validation.
     */
    public static function validateVkn(?string $vkn): bool
    {
        if (!$vkn) return false;
        $v = preg_replace('/\D/', '', $vkn);
        if (strlen($v) !== 10) return false;

        $digits = array_map('intval', str_split($v));

        // Simple algorithm: weighted sum mod 11 (common approach)
        $sum = 0;
        $weights = [6,5,4,3,2,7,6,5,4];
        for ($i = 0; $i < 9; $i++) {
            $sum += $digits[$i] * $weights[$i];
        }
        $remainder = $sum % 11;
        $check = ($remainder < 2) ? $remainder : (11 - $remainder);

        return $digits[9] === $check;
    }

    /**
     * Validate TR IBAN using mod97
     */
    public static function validateIban(?string $iban): bool
    {
        if (!$iban) return false;
        $i = strtoupper(preg_replace('/\s+/', '', $iban));
        if (substr($i,0,2) !== 'TR') return false;
        if (strlen($i) !== 26) return false;

        // Move first 4 chars to end
        $rearranged = substr($i, 4) . substr($i, 0, 4);
        // Convert letters to numbers (A=10, B=11...)
        $converted = '';
        foreach (str_split($rearranged) as $ch) {
            if (ctype_alpha($ch)) {
                $converted .= (ord($ch) - 55);
            } else {
                $converted .= $ch;
            }
        }

        // Perform mod97 in chunks
        $checksum = intval(substr($converted, 0, 1));
        $num = $converted;
        $remainder = 0;
        while (strlen($num) > 0) {
            $block = substr($num, 0, 9);
            $num = substr($num, 9);
            $remainder = intval($remainder . $block) % 97;
        }
        return $remainder === 1;
    }
}
