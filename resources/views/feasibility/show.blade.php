@extends('layouts.master')

@section('title', $s->name . ' - Fizibilite Analiz Detayı')

@push('styles')
<style>
    .feas-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .feas-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: feasShowGlow 4s linear infinite;
    }

    @keyframes feasShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .feas-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-feas {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-feas:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-feas-card-show {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="feas-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill feas-badge">
                        <i class="ri-calculator-fill text-warning fs-5"></i> FİZİBİLİTE & SERMAYE ANALİZİ #{{ $s->id }}
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        {{ $s->project_type ?? 'Genel Proje' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $s->name }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Finansal getiri oranları (ROI/IRR), nakit akış simülasyonu (S-Curve) ve maliyet parametre kırılımları.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('feasibility.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Listeye Dön
                    </a>
                    <a href="{{ route('feasibility.edit', $s->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2 fw-extrabold shadow-sm">
                        <i class="ri-pencil-line me-1"></i>Düzenle
                    </a>
                    <form method="POST" action="{{ route('feasibility.duplicate', $s->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                            <i class="ri-file-copy-line me-1"></i>Kopyala
                        </button>
                    </form>
                    <a href="{{ route('feasibility.export_pdf', $s->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-file-pdf-2-line me-1"></i>PDF İndir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-success border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">HESAPLANAN TOPLAM GELİR</small>
                <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($results['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                <small class="text-success fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Tahmini Satış Hasılatı</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-danger border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM PROJE MALİYETİ</small>
                <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">₺{{ number_format($results['total_cost'] ?? 0, 0, ',', '.') }}</div>
                <small class="text-danger fw-bold"><i class="ri-calculator-line me-1"></i>Arsa + İnşaat + Yumuşak</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-warning border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHMİNİ BRÜT KAR & ROI</small>
                <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($results['gross_profit'] ?? (($results['total_revenue'] ?? 0) - ($results['total_cost'] ?? 0)), 0, ',', '.') }}</div>
                <small class="text-warning fw-bold"><i class="ri-pulse-line me-1"></i>Yatırım Getirisi (ROI): %{{ $results['roi'] ?? 0 }}</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">NPV (NET BUGÜNKÜ DEĞER)</small>
                <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">₺{{ number_format($results['npv'] ?? 0, 0, ',', '.') }}</div>
                <small class="text-primary fw-bold"><i class="ri-percent-line me-1"></i>İç Verim Oranı (IRR): %{{ $results['irr'] ?? 0 }}</small>
            </div>
        </div>
    </div>

    <!-- ASSUMPTIONS CARD -->
    <div class="card custom-feas-card-show mb-4">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-file-info-line text-warning fs-5"></i>Girdi Varsayımları & Metraj Parametreleri
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Proje Tipi</small>
                    <span class="fw-extrabold text-dark fs-6">🏗️ {{ $s->project_type ?? 'Belirtilmemiş' }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Toplam İnşaat Alanı</small>
                    <span class="fw-extrabold text-dark font-monospace fs-6">{{ number_format($s->total_area ?: 0, 0, ',', '.') }} m²</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Birim Satış Fiyatı</small>
                    <span class="fw-extrabold text-success font-monospace fs-6">₺{{ number_format($s->unit_sale_price ?: 0, 0, ',', '.') }} / m²</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Arsa Maliyeti</small>
                    <span class="fw-extrabold text-danger font-monospace fs-6">₺{{ number_format($s->land_cost ?: 0, 0, ',', '.') }}</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Birim İnşaat Maliyeti</small>
                    <span class="fw-extrabold text-dark font-monospace fs-6">₺{{ number_format($s->construction_cost_per_m2 ?: 0, 0, ',', '.') }} / m²</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">İnşaat Süresi</small>
                    <span class="fw-extrabold text-dark font-monospace fs-6">⏱️ {{ $s->construction_months ?: 12 }} Ay</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Satış Süresi</small>
                    <span class="fw-extrabold text-dark font-monospace fs-6">📈 {{ $s->sales_months ?: 12 }} Ay</span>
                </div>
                <div class="col-md-3">
                    <small class="text-muted fw-bold d-block">Payback (Geri Dönüş Süresi)</small>
                    <span class="fw-extrabold text-primary font-monospace fs-6">⏳ {{ $results['payback_months'] ?? '—' }} Ay</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS & CASH FLOW ROW -->
    <div class="row g-4 mb-4">
        <!-- COST BREAKDOWN COLUMN -->
        <div class="col-lg-5">
            <div class="card custom-feas-card-show h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-pie-chart-2-line text-warning fs-5"></i>Maliyet Dağılım Kırılımı
                    </h5>
                    <button id="exportCostCsv" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">
                        <i class="ri-file-excel-2-line me-1"></i>CSV
                    </button>
                </div>
                <div class="card-body p-4">
                    <div style="height: 160px;" class="mb-3">
                        <canvas id="costBarChart" style="width:100%;height:100%;display:block"></canvas>
                    </div>

                    @php
                        $c = $results['construction_cost'] ?? 0;
                        $sft = $results['soft_costs'] ?? 0;
                        $l = $results['land_cost'] ?? 0;
                        $cmat = $results['construction_materials'] ?? 0;
                        $clab = $results['construction_labor'] ?? 0;
                        $ceng = $results['construction_engineering'] ?? 0;
                        $ccon = $results['construction_contingency'] ?? 0;
                        $sperm = $results['soft_permits'] ?? 0;
                        $smark = $results['soft_marketing'] ?? 0;
                        $sfees = $results['soft_fees'] ?? 0;
                        $tot = ($c + $sft + $l) ?: 1;
                        $items = [
                            ['label'=>'İnşaat - Malzeme','value'=>$cmat,'color'=>'#3b82f6','id'=>'construction_materials'],
                            ['label'=>'İnşaat - İşçilik','value'=>$clab,'color'=>'#2563eb','id'=>'construction_labor'],
                            ['label'=>'İnşaat - Mühendislik','value'=>$ceng,'color'=>'#8b5cf6','id'=>'construction_engineering'],
                            ['label'=>'İnşaat - Rezerv','value'=>$ccon,'color'=>'#1d4ed8','id'=>'construction_contingency'],
                            ['label'=>'Yumuşak - Ruhsat/İzin','value'=>$sperm,'color'=>'#16a34a','id'=>'soft_permits'],
                            ['label'=>'Yumuşak - Pazarlama','value'=>$smark,'color'=>'#059669','id'=>'soft_marketing'],
                            ['label'=>'Yumuşak - Ücretler','value'=>$sfees,'color'=>'#64748b','id'=>'soft_fees'],
                            ['label'=>'Arazi / Arsa','value'=>$l,'color'=>'#d97706','id'=>'land_cost'],
                        ];
                    @endphp

                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table align-middle table-sm mb-0">
                            <tbody>
                                @foreach($items as $it)
                                    @php $val = $it['value']; $pct = round($val/$tot*100,1); @endphp
                                    <tr>
                                        <td style="width: 8%;"><span style="display:inline-block;width:12px;height:12px;background:{{ $it['color'] }};border-radius:3px;"></span></td>
                                        <td class="fw-bold text-dark" style="width: 48%;">{{ $it['label'] }}</td>
                                        <td class="text-end font-monospace fw-extrabold text-dark" style="width: 24%;">₺{{ number_format($val, 0, ',', '.') }}</td>
                                        <td class="text-end font-monospace fw-bold text-secondary" style="width: 20%;">%{{ $pct }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td colspan="3">
                                            <div class="progress mb-2" style="height: 6px; background: #e2e8f0;">
                                                <div class="progress-bar" style="width: {{ $pct }}%; background: {{ $it['color'] }};"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CASH FLOW S-CURVE COLUMN -->
        <div class="col-lg-7">
            <div class="card custom-feas-card-show h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-line-chart-line text-warning fs-5"></i>Aylık Nakit Akışı & Kümülatif (S-Curve)
                    </h5>
                    <form method="GET" action="" class="d-flex align-items-center gap-2">
                        <label class="mb-0 text-white small fw-bold">KDV Oranı %</label>
                        <select name="kdv" onchange="this.form.submit()" class="form-select form-select-sm custom-input-feas py-1" style="width: 100px;"> 
                            <option value="" @if(empty($kdv)) selected @endif>— (Varsayılan)</option>
                            <option value="0" @if(isset($kdv) && $kdv==='0') selected @endif>%0</option>
                            <option value="1" @if($kdv==1) selected @endif>%1</option>
                            <option value="10" @if($kdv==10) selected @endif>%10</option>
                            <option value="20" @if($kdv==20) selected @endif>%20</option>
                        </select>
                    </form>
                </div>
                <div class="card-body p-4">
                    <div style="height: 380px;">
                        <canvas id="cfChart" style="width:100%;height:100%;display:block"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const exportBtn = document.getElementById('exportCostCsv');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(){
            const items = [
                ['İnşaat - Malzeme', {{ $results['construction_materials'] ?? 0 }}],
                ['İnşaat - İşçilik', {{ $results['construction_labor'] ?? 0 }}],
                ['İnşaat - Mühendislik', {{ $results['construction_engineering'] ?? 0 }}],
                ['İnşaat - Rezerv', {{ $results['construction_contingency'] ?? 0 }}],
                ['Yumuşak - Ruhsat/İzin', {{ $results['soft_permits'] ?? 0 }}],
                ['Yumuşak - Pazarlama', {{ $results['soft_marketing'] ?? 0 }}],
                ['Yumuşak - Ücretler', {{ $results['soft_fees'] ?? 0 }}],
                ['Arazi', {{ $results['land_cost'] ?? 0 }}]
            ];
            const total = items.reduce((a,b)=>a+parseFloat(b[1]||0),0)||1;
            const rows = [['Kalem','Tutar','Yüzde']];
            items.forEach(i=> rows.push([i[0], i[1], ((i[1]/total*100)||0).toFixed(1)+'%']));
            const csv = rows.map(r=> r.map(c=> '"'+String(c).replace(/"/g,'""')+'"').join(',')).join('\n');
            const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a'); a.href = url; a.download = 'cost_breakdown_{{ date("Ymd_His") }}.csv'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
        });
    }

    const cfLabels = @json($results['cashflow_labels'] ?? []);
    const monthly = @json($results['cashflow_values'] ?? []);
    const cumulative = monthly.reduce((acc, v, i) => { if(i===0) acc.push(v); else acc.push(acc[i-1]+v); return acc; }, []);

    const ctx = document.getElementById('cfChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            data: {
                labels: cfLabels,
                datasets: [
                    { type: 'bar', label: 'Aylık Net Nakit', data: monthly, backgroundColor: 'rgba(217,119,6,0.85)', borderRadius: 4, yAxisID: 'y' },
                    { type: 'line', label: 'Kümülatif S-Curve', data: cumulative, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.15)', fill: true, tension:0.3, borderWidth: 3, yAxisID: 'y' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(ctx){
                                return ctx.dataset.label + ': ' + new Intl.NumberFormat('tr-TR', {style:'currency', currency:'TRY'}).format(ctx.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks:{ callback: function(v){ return new Intl.NumberFormat('tr-TR', {style:'currency', currency:'TRY'}).format(v); } } }
                }
            }
        });
    }

    const barCtx = document.getElementById('costBarChart');
    if (barCtx) {
        const barData = [
            {{ $results['construction_cost'] ?? 0 }},
            {{ $results['soft_costs'] ?? 0 }},
            {{ $results['land_cost'] ?? 0 }}
        ];
        const labels = ['İnşaat','Yumuşak','Arazi'];
        const total = barData.reduce((a,b)=>a+b,0) || 1;
        new Chart(barCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Maliyet Dağılımı',
                    data: barData,
                    backgroundColor: ['#2563eb','#16a34a','#d97706'],
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { ticks: { callback: function(v){ return new Intl.NumberFormat('tr-TR', {style:'currency', currency:'TRY'}).format(v); } } } }
            }
        });
    }
});
</script>
@endpush
