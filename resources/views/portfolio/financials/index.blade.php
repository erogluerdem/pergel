@extends('layouts.master')

@section('title', 'Portföy Finansal Yönetim Paneli - Pergel')

@push('styles')
<style>
    .fin-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .fin-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: finGlow 4s linear infinite;
    }

    @keyframes finGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .fin-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-fin {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-fin:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-select-fin {
        background-color: #ffffff !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 0.88rem !important;
    }

    .fin-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .fin-table th {
        background: #F8FAFC;
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0f172a;
        border-bottom: 1.5px solid #cbd5e1;
        padding: 14px 16px;
    }
    .fin-table td {
        padding: 16px;
        border-bottom: 1px solid #E2E8F0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="fin-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill fin-badge">
                        <i class="ri-coins-line text-warning fs-5"></i> PORTFÖY FİNANSAL KONTROL PANELSİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        <i class="ri-history-line me-1"></i>Güncelleme: {{ now()->format('H:i') }}
                    </span>
                    @if(!empty($usingDemo))
                        <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                            Demo Veri
                        </span>
                    @endif
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Portföy Finansal Yönetim & Risk Analizi</h2>
                <p class="text-white-50 mb-0 fs-6" style="max-width: 780px; line-height: 1.6;">
                    Portföy performansı, nakit akışı öngörüleri, bütçe sapma oranları ve konsolide maliyet dağılımını canlı takip edin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <form method="GET" action="{{ route('portfolio.financials.index') }}" class="d-inline-block">
                        <input type="hidden" name="group_by" value="{{ $groupBy ?? 'type' }}">
                        <select name="kdv" class="form-select custom-select-fin rounded-pill px-3 py-2" onchange="this.form.submit()">
                            <option value="0" {{ ($kdvPercent ?? 0) == 0 ? 'selected' : '' }}>KDV %0 (Hariç)</option>
                            <option value="1" {{ ($kdvPercent ?? 0) == 1 ? 'selected' : '' }}>KDV %1</option>
                            <option value="10" {{ ($kdvPercent ?? 0) == 10 ? 'selected' : '' }}>KDV %10</option>
                            <option value="20" {{ ($kdvPercent ?? 0) == 20 ? 'selected' : '' }}>KDV %20 (Dahil)</option>
                        </select>
                    </form>
                    <a href="{{ route('portfolio.financials.export', request()->all()) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-1.5">
                        <i class="ri-file-csv-line fs-5"></i>CSV İndir
                    </a>
                    <a href="{{ url('portfolio/financials/export-xlsx?'.http_build_query(request()->all())) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-1.5 shadow-sm">
                        <i class="ri-file-excel-2-fill fs-5"></i>Excel İndir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fin h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM SÖZLEŞME DEĞERİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalContractValueKdv ?? $totalContractValue ?? 0, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold">
                            @if(($kdvPercent ?? 0) > 0)
                                <i class="ri-price-tag-3-fill me-1"></i>KDV Dahil (%{{ $kdvPercent }})
                            @else
                                <i class="ri-price-tag-3-line me-1"></i>KDV Hariç Net Tutar
                            @endif
                        </small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-bank-card-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fin h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GERÇEKLEŞEN HARCAMA</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalSpent ?? 0, 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-pie-chart-line me-1"></i>Bütçenin %{{ $totalContractValue > 0 ? round(($totalSpent / $totalContractValue) * 100, 1) : 0 }}'i</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-hand-coin-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fin h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA BÜTÇE SAPMASI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">%{{ $avgCostVariancePercent ?? 0.0 }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Tolerans Dahilinde</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-bar-chart-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fin h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YÜKSEK RİSKLİ PROJELER</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $highRiskProjects ?? 0 }} Proje</div>
                        <small class="text-danger fw-bold"><i class="ri-error-warning-fill me-1"></i>Maliyet & Takvim Riski</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-alert-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS ROW -->
    <div class="row g-4 mb-4">
        <!-- S-CURVE CHART -->
        <div class="col-xl-7">
            <div class="card fin-card-container shadow-sm h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-line-chart-fill text-warning fs-5"></i>Finansal S-Eğrisi (Kumülatif Harcama)
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace px-3 py-1 fw-extrabold">Aylık S-Curve</span>
                </div>
                <div class="card-body p-4">
                    <div id="chart-scurve" style="min-height: 280px;"></div>
                </div>
            </div>
        </div>

        <!-- CASH PROJECTION CHART -->
        <div class="col-xl-5">
            <div class="card fin-card-container shadow-sm h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-money-dollar-box-fill text-warning fs-5"></i>12 Aylık Nakit Akışı Öngörüsü
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace px-3 py-1 fw-extrabold">Tahmini Akış</span>
                </div>
                <div class="card-body p-4">
                    <div id="chart-cash-projection" style="min-height: 280px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card fin-card-container shadow-sm mb-4">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-table-line text-warning fs-5"></i>Konsolide Finansal Özet Tablosu
            </h5>
            <div class="btn-group">
                <a href="{{ route('portfolio.financials.index', array_merge(request()->all(), ['group_by' => 'type'])) }}" class="btn btn-sm {{ ($groupBy ?? 'type') == 'type' ? 'btn-warning text-dark fw-extrabold' : 'btn-outline-light' }}">
                    Proje Türüne Göre
                </a>
                <a href="{{ route('portfolio.financials.index', array_merge(request()->all(), ['group_by' => 'program'])) }}" class="btn btn-sm {{ ($groupBy ?? '') == 'program' ? 'btn-warning text-dark fw-extrabold' : 'btn-outline-light' }}">
                    Programlara Göre
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle fin-table mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th class="ps-4">Kategori / Program</th>
                            <th>Proje Sayısı</th>
                            <th>Sözleşme Bütçesi</th>
                            <th>Harcanan Tutar</th>
                            <th>Kalan Bütçe</th>
                            <th>Kar Marjı</th>
                            <th class="pe-4 text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark fs-6">{{ $group['group'] ?? $group['name'] ?? 'Genel' }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace text-warning px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                        {{ $group['project_count'] }} Proje
                                    </span>
                                </td>
                                <td><strong class="text-dark font-monospace" style="font-size: 1.05rem;">₺{{ number_format($group['contract_value'], 0, ',', '.') }}</strong></td>
                                <td><strong class="text-primary font-monospace" style="font-size: 1.05rem;">₺{{ number_format($group['spent'], 0, ',', '.') }}</strong></td>
                                <td><strong class="text-secondary font-monospace" style="font-size: 1.05rem;">₺{{ number_format($group['remaining'], 0, ',', '.') }}</strong></td>
                                <td>
                                    @if(($group['profit_margin'] ?? 0) >= 30)
                                        <span class="badge bg-success text-white font-monospace rounded-pill px-3 py-1.5 fs-6" style="background: #16a34a !important;">
                                            %{{ number_format($group['profit_margin'] ?? 0, 1) }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark font-monospace rounded-pill px-3 py-1.5 fs-6">
                                            %{{ number_format($group['profit_margin'] ?? 0, 1) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold shadow-sm" onclick="showGroupDetails('{{ $groupBy }}', '{{ $group['group'] ?? $group['name'] ?? '' }}')">
                                        <i class="ri-search-eye-line me-1"></i>Detay Analiz
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Görüntülenecek finansal özet verisi bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- DETAY ANALİZ MODAL -->
<div class="modal fade" id="analyticsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white p-3.5">
                <h5 class="modal-title text-white mb-0 d-flex align-items-center gap-2" id="groupModalTitle">
                    <i class="ri-pie-chart-line text-warning fs-5"></i>Kategori Finansal Detay Analizi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4" id="groupModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="text-muted mt-2 fw-bold">Finansal veriler yükleniyor...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initSCurveChart();
            initCashProjectionChart();
        });

        function initSCurveChart() {
            const options = {
                chart: { type: 'area', height: 280, toolbar: { show: false } },
                colors: ['#2563eb', '#16a34a'],
                series: [
                    { name: 'Planlanan Kumülatif Harcama', data: [150, 320, 580, 890, 1250, 1680, 2100, 2485] },
                    { name: 'Gerçekleşen Nakit Çıkışı', data: [140, 310, 550, 840, 1230, null, null, null] }
                ],
                xaxis: { categories: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos'] },
                yaxis: { labels: { formatter: (val) => val ? '₺' + val + 'M' : '' } },
                stroke: { curve: 'smooth', width: 3 },
                fill: { opacity: 0.15 }
            };
            new ApexCharts(document.querySelector("#chart-scurve"), options).render();
        }

        function initCashProjectionChart() {
            const options = {
                chart: { type: 'bar', height: 280, toolbar: { show: false } },
                colors: ['#d97706'],
                series: [{ name: 'Tahmini Nakit Girişi / Hakediş', data: [180, 220, 210, 260, 310, 290, 340, 380] }],
                xaxis: { categories: ['Eylül', 'Ekim', 'Kasım', 'Aralık', 'Ocak', 'Şubat', 'Mart', 'Nisan'] },
                yaxis: { labels: { formatter: (val) => '₺' + val + 'M' } },
                plotOptions: {
                    bar: { borderRadius: 4, columnWidth: '45%' }
                }
            };
            new ApexCharts(document.querySelector("#chart-cash-projection"), options).render();
        }

        function showGroupDetails(groupType, groupValue) {
            const modal = new bootstrap.Modal(document.getElementById('analyticsModal'));
            document.getElementById('groupModalTitle').innerHTML = `<i class="ri-pie-chart-line text-warning me-2"></i> ${groupValue} — Finansal Detay Analizi`;
            modal.show();

            fetch(`{{ url('portfolio/financials/group-items') }}?group_type=${encodeURIComponent(groupType)}&group_value=${encodeURIComponent(groupValue)}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('groupModalBody').innerHTML = html;
                })
                .catch(err => {
                    document.getElementById('groupModalBody').innerHTML = `<div class="alert alert-danger">Detaylar yüklenirken bir hata oluştu.</div>`;
                });
        }
    </script>
@endpush