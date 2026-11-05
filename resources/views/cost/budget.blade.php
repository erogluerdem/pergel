@extends('layouts.master')

@section('title', 'Bütçe ve Maliyet Kontrolü')
@section('page-title', 'Bütçe ve Maliyet Kontrolü')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Bütçe Panosu</h4>
                <div>
                    <a href="#" class="btn btn-sm btn-outline-primary">Excel'e Aktar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="card border-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Orijinal Bütçe</h6>
                                <h4>{{ number_format($originalBudget,0,',','.') }} ₺</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Revize Bütçe</h6>
                                <h4>{{ number_format($revisedBudget,0,',','.') }} ₺</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Taahhüt Edilen</h6>
                                <h4>{{ number_format($committed,0,',','.') }} ₺</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Gerçekleşen</h6>
                                <h4>{{ number_format($actualSpent,0,',','.') }} ₺</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Tahmini Tamamlanma Maliyeti</h6>
                                <h4>{{ number_format($estimatedFinishCost,0,',','.') }} ₺</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card border-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted">Bütçe Sapması</h6>
                                <h4 class="{{ $variance < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($variance,0,',','.') }} ₺</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <div id="budget-distribution" style="height:300px;"></div>
                    </div>
                    <div class="col-6">
                        <div id="s-curve" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cost Ledger -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Detaylı Bütçe Cetveli (Cost Ledger)</h4>
                <div>
                    <a href="#" class="btn btn-sm btn-outline-secondary">E-Tabloya Aktar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kod/İsim</th>
                                <th>Orijinal Bütçe</th>
                                <th>Onaylanan Değişiklikler</th>
                                <th>Revize Bütçe</th>
                                <th>Taahhüt Edilen</th>
                                <th>Gerçekleşen</th>
                                <th>Kalan Taahhüt</th>
                                <th>TTM</th>
                                <th>Sapma</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($costLedger as $row)
                            @php
                                $remainingCommit = ($row['committed'] ?? 0) - ($row['actual'] ?? 0);
                                $varianceRow = ($row['revised'] ?? 0) - ($row['ttm'] ?? 0);
                            @endphp
                            <tr class="{{ $varianceRow < 0 ? 'table-danger' : '' }}">
                                <td><strong>{{ $row['code'] }} - {{ $row['name'] }}</strong></td>
                                <td>{{ number_format($row['original'] ?? 0,2,',','.') }}</td>
                                <td>{{ number_format($row['changes'] ?? 0,2,',','.') }}</td>
                                <td>{{ number_format($row['revised'] ?? 0,2,',','.') }}</td>
                                <td><a href="#">{{ number_format($row['committed'] ?? 0,2,',','.') }}</a></td>
                                <td><a href="#">{{ number_format($row['actual'] ?? 0,2,',','.') }}</a></td>
                                <td>{{ number_format($remainingCommit,2,',','.') }}</td>
                                <td>{{ number_format($row['ttm'] ?? 0,2,',','.') }}</td>
                                <td>{{ number_format($varianceRow,2,',','.') }}</td>
                            </tr>
                                @if(!empty($row['children']))
                                    @foreach($row['children'] as $child)
                                    @php
                                        $rem = ($child['committed'] ?? 0) - ($child['actual'] ?? 0);
                                        $varc = ($child['revised'] ?? 0) - ($child['ttm'] ?? 0);
                                    @endphp
                                    <tr class="table-light {{ $varc < 0 ? 'table-danger' : '' }}">
                                        <td style="padding-left:30px;">{{ $child['code'] }} - {{ $child['name'] }}</td>
                                        <td>{{ number_format($child['original'] ?? 0,2,',','.') }}</td>
                                        <td>{{ number_format($child['changes'] ?? 0,2,',','.') }}</td>
                                        <td>{{ number_format($child['revised'] ?? 0,2,',','.') }}</td>
                                        <td><a href="#">{{ number_format($child['committed'] ?? 0,2,',','.') }}</a></td>
                                        <td><a href="#">{{ number_format($child['actual'] ?? 0,2,',','.') }}</a></td>
                                        <td>{{ number_format($rem,2,',','.') }}</td>
                                        <td>{{ number_format($child['ttm'] ?? 0,2,',','.') }}</td>
                                        <td>{{ number_format($varc,2,',','.') }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Order Log -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Ek Talep ve Bütçe Değişikliği Yönetimi</h4>
                <div>
                    <button class="btn btn-sm btn-success" id="new-co">➕ Yeni Ek Talep Oluştur</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Konu</th>
                                <th>Neden</th>
                                <th>Durum</th>
                                <th>Maliyet Etkisi (TL)</th>
                                <th>Onay Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($changeOrders as $co)
                            <tr>
                                <td>{{ $co['no'] }}</td>
                                <td>{{ $co['subject'] }}</td>
                                <td>{{ $co['reason'] }}</td>
                                <td>{{ $co['status'] }}</td>
                                <td>{{ number_format($co['cost'],0,',','.') }}</td>
                                <td>{{ $co['approved_at'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Charts: normalize ledger numbers and add formatters
    var ledger = @json($costLedger);
    // Normalize numbers and create short labels
    var normalized = ledger.map(function(r){
        return {
            code: r.code || '',
            name: r.name || '',
            nameShort: (r.name && r.name.length > 18) ? r.name.substring(0,17) + '…' : (r.name || ''),
            revised: Number(r.revised || 0),
            actual: Number(r.actual || 0)
        };
    });

    // PIE (budget distribution)
    var pieSeries = normalized.map(function(r){ return r.revised; });
    var pieLabels = normalized.map(function(r){ return r.nameShort + ' (' + r.code + ')'; });
    var pieTotal = pieSeries.reduce(function(s,v){ return s + (isFinite(v) ? v : 0); }, 0);

    if (pieTotal <= 0) {
        // show friendly message if no data
        document.querySelector('#budget-distribution').innerHTML = '<div class="d-flex h-100 align-items-center justify-content-center text-muted">Veri yok</div>';
    } else {
        var pieOptions = {
            series: pieSeries,
            chart: { type: 'donut', height: 300 },
            labels: pieLabels,
            legend: { position: 'bottom' },
            tooltip: { y: { formatter: function(val){ return new Intl.NumberFormat('tr-TR').format(Math.round(val)) + ' ₺'; } } },
            dataLabels: { formatter: function(val, opts){ var v = pieSeries[opts.seriesIndex]; return opts.w.config.labels[opts.seriesIndex] + '\n' + new Intl.NumberFormat('tr-TR').format(Math.round(v)) + ' ₺'; } }
        };
        var pie = new ApexCharts(document.querySelector('#budget-distribution'), pieOptions);
        pie.render();
    }

    // S-curve: cumulative planned (revised) vs actual
    var categories = normalized.map(function(r){ return r.nameShort + ' (' + r.code + ')'; });
    var planned = [];
    var actual = [];
    var cumP = 0, cumA = 0;
    normalized.forEach(function(r){
        cumP += (isFinite(r.revised) ? r.revised : 0);
        cumA += (isFinite(r.actual) ? r.actual : 0);
        planned.push(Math.round(cumP));
        actual.push(Math.round(cumA));
    });

    var sOptions = {
        series: [
            { name: 'Planlanan', data: planned },
            { name: 'Gerçekleşen', data: actual }
        ],
        chart: { type: 'line', height: 300, toolbar: { show: true } },
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4 },
        xaxis: { categories: categories, labels: { rotate: -30, hideOverlappingLabels: true } },
        yaxis: { labels: { formatter: function(val){ return new Intl.NumberFormat('tr-TR').format(Math.round(val)); } } },
        tooltip: { y: { formatter: function(val){ return new Intl.NumberFormat('tr-TR').format(val) + ' ₺'; } } },
        legend: { position: 'bottom' },
        grid: { borderColor: '#eee' }
    };
    var sChart = new ApexCharts(document.querySelector('#s-curve'), sOptions);
    sChart.render();

    // New Change Order button (opens a simple prompt for demo)
    document.getElementById('new-co').addEventListener('click', function(){
        var subject = prompt('Ek talep konusu:');
        if (!subject) return;
        alert('Yeni ek talep oluşturuldu (demo): ' + subject + '. Gerçek uygulamada bu form backend ile entegre edilecek.');
    });
});
</script>
@endsection
