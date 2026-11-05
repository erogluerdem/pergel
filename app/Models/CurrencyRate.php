<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'effective_date',
        'source',
        'rate_bid',
        'rate_ask',
        'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'rate' => 'decimal:8',
        'rate_bid' => 'decimal:8',
        'rate_ask' => 'decimal:8',
    ];

    /**
     * Para birimi çifti (örn: USD/TRY)
     */
    public function getCurrencyPairAttribute(): string
    {
        return "{$this->from_currency}/{$this->to_currency}";
    }

    /**
     * Ortalama kuru (alış ve satış arasında)
     */
    public function getAverageRateAttribute(): float
    {
        if ($this->rate_bid && $this->rate_ask) {
            return ($this->rate_bid + $this->rate_ask) / 2;
        }
        return (float) $this->rate;
    }

    /**
     * Kuru kaynağını etiketle
     */
    public function getSourceLabel(): string
    {
        $labels = [
            'manual' => 'Manuel',
            'api' => 'API',
            'bank' => 'Banka',
        ];
        return $labels[$this->source] ?? $this->source;
    }

    /**
     * Belirli bir tarih için döviz kurunu getir
     */
    public static function getRate($from, $to, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return self::where('from_currency', $from)
            ->where('to_currency', $to)
            ->where('effective_date', '<=', $date)
            ->orderBy('effective_date', 'desc')
            ->first();
    }

    /**
     * Para birimini dönüştür
     */
    public static function convert($amount, $fromCurrency, $toCurrency, $date = null)
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = self::getRate($fromCurrency, $toCurrency, $date);
        
        if (!$rate) {
            throw new \Exception("Döviz kuru bulunamadı: {$fromCurrency}/{$toCurrency}");
        }

        return $amount * $rate->getAverageRateAttribute();
    }
}
