@extends('layouts.master')

@section('title', 'Fizibilite & Senaryo Karşılaştırma Portalı')

@push('styles')
<style>
    .comp-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .comp-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: compGlow 4s linear infinite;
    }

    @keyframes compGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .comp-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .comp-table-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .comp-table thead th,
    .comp-table thead tr,
    .comp-table th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 18px 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border: none !important;
        --bs-table-bg: #0f172a !important;
    }

    .comp-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        font-weight: 700 !important;
    }

    .metric-best {
        background-color: #f0fdf4 !important;
        color: #15803d !important;
        font-weight: 800 !important;
        border-left: 4px solid #16a34a !important;
        --bs-table-bg: #f0fdf4 !important;
    }

    .metric-worst {
        background-color: #fff1f2 !important;
        color: #b91c1c !important;
        font-weight: 800 !important;
        border-left: 4px solid #e11d48 !important;
        --bs-table-bg: #fff1f2 !important;
    }

    .metric-neutral {
        background-color: #ffffff !important;
        color: #0f172a !important;
        --bs-table-bg: #ffffff !important;
    }

    .scenario-card-comp {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .scenario-card-comp:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: #d97706 !important;
    }

    .chart-container-comp {
        padding: 16px;
        height: 280px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="comp-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill comp-badge">
                        <i class="ri-scales-3-line text-warning fs-5"></i> FİZİBİLİTE & YATIRIM SENARYO KIYASLAMA MERKEZİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ count($rows) }} Senaryo Kıyaslanıyor
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Fizibilite & Senaryo Karşılaştırma</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Seçili gayrimenkul senaryolarınızın gelir, maliyet, ROI, NPV net bugünkü değer ve geri ödeme sürelerini yan yana karşılaştırarak en doğru yatırım kararını verin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('feasibility.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-list-check-2 fs-5"></i>Fizibilite İndeksi
                    </a>
                    <a href="{{ route('feasibility.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Senaryo Ekle
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(empty($rows))
        <div class="card comp-table-container p-5 text-center shadow-sm">
            <i class="ri-scales-3-line display-2 text-warning mb-3"></i>
            <h4 class="fw-bold text-dark">Karşılaştırma İçin Senaryo Seçin</h4>
            <p class="text-muted mb-4">Fizibilite senaryolarınızı kıyaslamak için listeden en az 2 senaryo seçip "Kıyasla" butonuna tıklayın.</p>
            <div>
                <a href="{{ route('feasibility.index') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2 fw-extrabold">
                    <i class="ri-arrow-left-line me-1"></i>Fizibilite Listesine Git
                </a>
            </div>
        </div>
    @else

        <!-- COMPARISON TABLE CARD -->
        <div class="comp-table-container shadow-sm mb-4">
            <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-table-line text-warning fs-5"></i>Metrik Bazlı Karşılaştırma Cetveli
                </h5>
                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-extrabold">
                    🏆 En İyi Metrik Yeşil Vurgulu
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table comp-table align-middle mb-0">
                        <thead style="background-color: #0f172a !important;">
                            <tr>
                                <th style="min-width: 230px; background-color: #0f172a !important; color: #fef08a !important;" class="ps-4 text-warning fw-extrabold">
                                    PERFORMANS METRİĞİ
                                </th>
                                @foreach($rows as $r)
                                    <th class="text-center" style="min-width: 220px; background-color: #0f172a !important; color: #ffffff !important;">
                                        <div class="d-flex flex-column align-items-center py-1">
                                            <div class="rounded-circle bg-warning text-dark fw-extrabold d-flex align-items-center justify-content-center mb-1.5 shadow-sm" style="width: 42px; height: 42px; font-size: 15px; border: 2px solid #ffffff;">
                                                {{ strtoupper(substr($r['name'], 0, 2)) }}
                                            </div>
                                            <span class="text-white fw-extrabold font-size-14 d-block text-center" style="color: #ffffff !important; text-shadow: 0 1px 3px rgba(0,0,0,0.8); line-height: 1.3;">
                                                {{ $r['name'] }}
                                            </span>
                                            <span class="badge bg-warning text-dark font-monospace fw-extrabold mt-1.5 px-2 py-0.5" style="font-size: 11px;">
                                                #{{ str_pad($r['id'], 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $metrics = [
                                    'total_revenue' => ['label' => 'Toplam Gelir (₺)', 'icon' => 'ri-money-dollar-circle-fill', 'type' => 'currency', 'higher_better' => true],
                                    'total_cost' => ['label' => 'Toplam Maliyet (₺)', 'icon' => 'ri-coins-fill', 'type' => 'currency', 'higher_better' => false],
                                    'gross_profit' => ['label' => 'Net Kar (₺)', 'icon' => 'ri-wallet-3-fill', 'type' => 'currency', 'higher_better' => true],
                                    'roi' => ['label' => 'ROI Kar Oranı (%)', 'icon' => 'ri-percent-fill', 'type' => 'percentage', 'higher_better' => true],
                                    'npv' => ['label' => 'Net Bugünkü Değer (NPV)', 'icon' => 'ri-calculator-fill', 'type' => 'currency', 'higher_better' => true],
                                    'irr' => ['label' => 'İç Verim Oranı (IRR)', 'icon' => 'ri-pie-chart-fill', 'type' => 'percentage', 'higher_better' => true],
                                    'payback_months' => ['label' => 'Geri Ödeme Süresi (Ay)', 'icon' => 'ri-history-fill', 'type' => 'number', 'higher_better' => false]
                                ];
                            @endphp

                            @foreach($metrics as $metricKey => $metricInfo)
                                <tr>
                                    <td class="ps-4 fw-extrabold text-dark font-size-14" style="color: #0f172a !important;">
                                        <i class="{{ $metricInfo['icon'] }} me-2 text-warning fs-5"></i>
                                        {{ $metricInfo['label'] }}
                                    </td>
                                    @php
                                        $values = array_map(function($r) use ($metricKey){ return $r[$metricKey] ?? null; }, $rows);
                                        $numeric = array_filter($values, function($v){ return is_numeric($v); });
                                        $best = null; $worst = null;
                                        if (!empty($numeric)) {
                                            if (!$metricInfo['higher_better']) {
                                                $best = min($numeric); $worst = max($numeric);
                                            } else {
                                                $best = max($numeric); $worst = min($numeric);
                                            }
                                        }
                                    @endphp
                                    @foreach($rows as $r)
                                        @php
                                            $val = $r[$metricKey] ?? null;
                                            $cls = 'metric-neutral';
                                            if (is_numeric($val) && count($rows) > 1) {
                                                if ($val == $best) $cls = 'metric-best';
                                                elseif ($val == $worst) $cls = 'metric-worst';
                                            }
                                        @endphp
                                        <td class="{{ $cls }} text-center font-monospace" style="font-size: 1.08rem;">
                                            @if(is_numeric($val))
                                                @if($metricInfo['type'] == 'currency')
                                                    ₺{{ number_format($val, 0, ',', '.') }}
                                                @elseif($metricInfo['type'] == 'percentage')
                                                    %{{ number_format($val, 1, ',', '.') }}
                                                @else
                                                    {{ number_format($val, 0, ',', '.') }} Ay
                                                @endif
                                                @if($cls === 'metric-best')
                                                    <span class="badge bg-success text-white ms-1 px-2 py-0.5 font-sans" style="font-size: 10px; font-weight: 800;">EN İYİ</span>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CASH FLOW CHARTS GRID -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h4 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="ri-line-chart-fill text-warning fs-4"></i>Senaryo Nakit Akışı Detay Grafik Tablosu
                </h4>
            </div>

            @foreach($rows as $idx => $r)
                <div class="col-lg-6">
                    <div class="scenario-card-comp shadow-sm h-100">
                        <div class="card-header bg-dark text-white p-3.5 d-flex justify-content-between align-items-center" style="background-color: #0f172a !important;">
                            <div>
                                <h5 class="fw-extrabold text-white mb-0" style="color: #ffffff !important;">{{ $r['name'] }}</h5>
                                <small class="text-warning font-monospace fw-bold">Senaryo #{{ $idx + 1 }} — {{ $r['project_type'] ?? 'Genel' }}</small>
                            </div>
                            <div>
                                @if(isset($r['roi']) && is_numeric($r['roi']))
                                    <span class="badge bg-warning text-dark font-monospace px-3 py-1.5 fw-extrabold fs-6">
                                        ROI: %{{ number_format($r['roi'], 1) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="chart-container-comp p-3">
                            <canvas id="chart_{{ $idx }}" style="width: 100%; height: 100%;"></canvas>
                        </div>
                        <div class="card-footer bg-light p-3 border-top">
                            <div class="row text-center g-2">
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block fw-extrabold font-size-11 text-uppercase">Toplam Gelir</small>
                                    <strong class="text-success font-monospace font-size-14">
                                        ₺{{ isset($r['total_revenue']) ? number_format($r['total_revenue'], 0, ',', '.') : '—' }}
                                    </strong>
                                </div>
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block fw-extrabold font-size-11 text-uppercase">Toplam Maliyet</small>
                                    <strong class="text-danger font-monospace font-size-14">
                                        ₺{{ isset($r['total_cost']) ? number_format($r['total_cost'], 0, ',', '.') : '—' }}
                                    </strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block fw-extrabold font-size-11 text-uppercase">Net Kar</small>
                                    <strong class="text-primary font-monospace font-size-14">
                                        ₺{{ isset($r['gross_profit']) ? number_format($r['gross_profit'], 0, ',', '.') : '—' }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- EXPORT ACTION BAR -->
        <div class="card comp-table-container shadow-sm p-4 text-center mb-4">
            <h5 class="fw-extrabold text-dark mb-3"><i class="ri-download-2-fill text-warning me-2"></i>Karşılaştırma Raporlarını İndir</h5>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <form id="exportCsvForm" method="post" action="{{ route('feasibility.compare.export_csv') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="export_ids_csv" />
                    <button type="submit" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-file-csv-line fs-5"></i>CSV Karşılaştırma Cetveli
                    </button>
                </form>
                <form id="exportPdfForm" method="post" action="{{ route('feasibility.compare.export_pdf') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="export_ids_pdf" />
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 py-2 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-file-pdf-fill fs-5"></i>PDF Karşılaştırma Raporu
                    </button>
                </form>
            </div>
        </div>

    @endif

</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = @json($rows ?? []);

            const ids = rows.map(r => r.id);
            if (document.getElementById('export_ids_csv')) {
                document.getElementById('export_ids_csv').value = ids.join(',');
            }
            if (document.getElementById('export_ids_pdf')) {
                document.getElementById('export_ids_pdf').value = ids.join(',');
            }

            rows.forEach(function(r, idx) {
                const ctx = document.getElementById('chart_' + idx);
                if (!ctx) return;

                const canvas = ctx.getContext('2d');
                const gradient = canvas.createLinearGradient(0, 0, 0, 250);
                gradient.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
                gradient.addColorStop(1, 'rgba(245, 158, 11, 0.01)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: r.cashflow_labels || [],
                        datasets: [{
                            label: 'Nakit Akışı (₺)',
                            data: r.cashflow_values || [],
                            backgroundColor: gradient,
                            borderColor: '#d97706',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#d97706',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(148, 163, 184, 0.15)' },
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('tr-TR', {
                                            style: 'currency',
                                            currency: 'TRY',
                                            maximumFractionDigits: 0
                                        }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
