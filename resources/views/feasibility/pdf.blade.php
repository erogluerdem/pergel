<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fizibilite Raporu - {{ $s->name }}</title>
    <style>
        body{font-family: Arial, Helvetica, sans-serif; font-size:12px;}
        .header{margin-bottom:20px}
        .metrics{width:100%;border-collapse:collapse}
        .metrics td, .metrics th{border:1px solid #ddd;padding:6px}
        .chart{width:100%;height:200px}
        @media print { .no-print{display:none} }
    </style>
</head>
<body>
    <div class="header">
        <h2>Fizibilite Raporu</h2>
        <p><strong>Senaryo:</strong> {{ $s->name }}<br>
        <strong>Tarih:</strong> {{ now()->format('Y-m-d') }}</p>
    </div>

    <h4>Özet Finansal Metrikler</h4>
    <table class="metrics">
    <tr><th>Toplam Gelir</th><td>{{ number_format($results['total_revenue'],2,',','.') }} ₺</td></tr>
    <tr><th>Toplam Maliyet</th><td>{{ number_format($results['total_cost'],2,',','.') }} ₺</td></tr>
    <tr><th>Brüt Kar</th><td>{{ number_format($results['gross_profit'],2,',','.') }} ₺</td></tr>
        <tr><th>ROI (%)</th><td>{{ $results['roi'] }}</td></tr>
    <tr><th>NPV</th><td>{{ number_format($results['npv'],2,',','.') }} ₺</td></tr>
    <tr><th>IRR (%)</th><td>{{ $results['irr'] }}%</td></tr>
        <tr><th>Geri Ödeme (Ay)</th><td>{{ $results['payback_months'] ?? '—' }}</td></tr>
    </table>

    <h4>Aylık Nakit Akışları</h4>
    <div class="chart">
        <table style="width:100%;border-collapse:collapse">
            <tr>
                @foreach($results['cashflow_labels'] as $lbl)
                    <th style="border:1px solid #ddd;padding:6px">{{ $lbl }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($results['cashflow_values'] as $v)
                    <td style="border:1px solid #ddd;padding:6px;text-align:right">{{ number_format($v,2) }}</td>
                @endforeach
            </tr>
        </table>
    </div>

    <p class="no-print">Bu sayfa tarayıcıdan yazdırılarak PDF olarak kaydedilebilir. Eğer sunucu tarafı PDF çıktısı isterseniz lütfen gerekli PDF kütüphanesini yükleyin.</p>
</body>
</html>
