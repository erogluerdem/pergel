<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyExchange;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoCurrencyExchangeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed active currencies if empty
        $currenciesData = [
            ['code' => 'TRY', 'name' => 'Türk Lirası', 'symbol' => '₺', 'exchange_rate' => 1.000000, 'is_active' => true],
            ['code' => 'USD', 'name' => 'ABD Doları', 'symbol' => '$', 'exchange_rate' => 36.450000, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 38.200000, 'is_active' => true],
            ['code' => 'GBP', 'name' => 'İngiliz Sterlini', 'symbol' => '£', 'exchange_rate' => 45.800000, 'is_active' => true],
        ];

        foreach ($currenciesData as $c) {
            Currency::updateOrCreate(['code' => $c['code']], $c);
        }

        $try = Currency::where('code', 'TRY')->first();
        $usd = Currency::where('code', 'USD')->first();
        $eur = Currency::where('code', 'EUR')->first();
        $gbp = Currency::where('code', 'GBP')->first();

        $invoice = Invoice::first();

        $exchanges = [
            [
                'invoice_id' => $invoice ? $invoice->id : null,
                'from_currency' => $usd->id,
                'to_currency' => $try->id,
                'from_amount' => 150000.00,
                'exchange_rate' => 36.4500,
                'to_amount' => 5467500.00,
                'commission_percentage' => 0.25,
                'commission_amount' => 13668.75,
                'exchange_date' => now()->subDays(2),
                'notes' => 'Aksu İnşaat hakediş ödemesi için İş Bankası USD/TRY bozum işlemi.',
            ],
            [
                'invoice_id' => $invoice ? $invoice->id : null,
                'from_currency' => $eur->id,
                'to_currency' => $try->id,
                'from_amount' => 85000.00,
                'exchange_rate' => 38.2000,
                'to_amount' => 3247000.00,
                'commission_percentage' => 0.20,
                'commission_amount' => 6494.00,
                'exchange_date' => now()->subDays(5),
                'notes' => 'Almanya ithal Chiller grubu avans ödemesi için EUR/TRY döviz alım işlemi.',
            ],
            [
                'invoice_id' => $invoice ? $invoice->id : null,
                'from_currency' => $usd->id,
                'to_currency' => $eur->id,
                'from_amount' => 200000.00,
                'exchange_rate' => 0.9542,
                'to_amount' => 190840.00,
                'commission_percentage' => 0.15,
                'commission_amount' => 286.26,
                'exchange_date' => now()->subDays(10),
                'notes' => 'Uluslararası taşeron sözleşmesi gereği USD -> EUR çapraz kur çevrim işlemi.',
            ],
            [
                'invoice_id' => $invoice ? $invoice->id : null,
                'from_currency' => $gbp->id,
                'to_currency' => $try->id,
                'from_amount' => 45000.00,
                'exchange_rate' => 45.8000,
                'to_amount' => 2061000.00,
                'commission_percentage' => 0.30,
                'commission_amount' => 6183.00,
                'exchange_date' => now()->subDays(14),
                'notes' => 'İngiltere merkezli mimari danışmanlık firması ödemesi döviz bozumu.',
            ],
        ];

        foreach ($exchanges as $ex) {
            CurrencyExchange::create($ex);
        }
    }
}
