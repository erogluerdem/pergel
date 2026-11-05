<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fizibilite Karşılaştırma</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;font-size:12px} table{width:100%;border-collapse:collapse} th,td{border:1px solid #ddd;padding:6px;text-align:left}</style>
</head>
<body>
    <h3>Fizibilite Karşılaştırma</h3>
    <table>
        <thead>
            <tr>
                <th>Metrik</th>
                @foreach($rows as $r)
                    <th>{{ $r['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $metrics=['total_revenue','total_cost','gross_profit','roi','npv','irr','payback_months']; @endphp
            @foreach($metrics as $m)
                @php
                    $vals = array_map(function($r) use ($m){ return isset($r[$m]) && is_numeric($r[$m]) ? $r[$m] : null; }, $rows);
                    $numeric = array_filter($vals, function($v){ return $v !== null; });
                    $min = !empty($numeric) ? min($numeric) : 0;
                    $max = !empty($numeric) ? max($numeric) : 0;
                @endphp
                <tr>
                    <td style="width:18%">{{ $m }}</td>
                    @foreach($rows as $r)
                        @php $val = $r[$m] ?? null; @endphp
                        <td>
                            @if(is_numeric($val) && $max!=$min)
                                @php
                                    $pct = ($val - $min) / ($max - $min) * 100;
                                    $pct = max(0, min(100, $pct));
                                    // color: better-is-higher except for cost/payback where lower is better
                                    $betterLower = in_array($m, ['total_cost','payback_months']);
                                    $color = $betterLower ? ($val==$min ? '#28a745' : ($val==$max ? '#dc3545' : '#6c757d')) : ($val==$max ? '#28a745' : ($val==$min ? '#dc3545' : '#6c757d'));
                                @endphp
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div style="flex:1;background:#eee;height:10px;border-radius:4px;overflow:hidden">
                                        <div style="width:{{ $pct }}%;height:100%;background:{{ $color }}"></div>
                                    </div>
                                    <div style="min-width:80px;text-align:right">{{ number_format($val,2) }}</div>
                                </div>
                            @else
                                {{ $val ?? '' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
