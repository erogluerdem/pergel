@extends('layouts.master')

@section('title')
    Kontrol Paneli
@endsection

@section('topbar-title')
    Pergel
@endsection

@section('css')
<style>
.radio-wave {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
}

.radio-wave span {
    width: 4px;
    height: 20px;
    background: #28a745;
    border-radius: 2px;
    animation: wave 1.5s ease-in-out infinite;
}

.radio-wave span:nth-child(1) { animation-delay: 0s; }
.radio-wave span:nth-child(2) { animation-delay: 0.1s; }
.radio-wave span:nth-child(3) { animation-delay: 0.2s; }
.radio-wave span:nth-child(4) { animation-delay: 0.3s; }
.radio-wave span:nth-child(5) { animation-delay: 0.4s; }

.radio-wave.active span {
    animation-play-state: running;
}

.radio-wave:not(.active) span {
    animation-play-state: paused;
    opacity: 0.3;
}

@keyframes wave {
    0%, 100% { height: 20px; opacity: 0.4; }
    50% { height: 40px; opacity: 1; }
}

/* Dashboard Özel Stilleri */
.welcome-section {
    background: linear-gradient(135deg, rgba(var(--pt-primary-rgb, 245, 158, 11), 0.12) 0%, rgba(59, 130, 246, 0.05) 100%);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--pt-primary, #F59E0B);
    animation: fadeIn 0.5s ease;
}

.welcome-section h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--pt-heading-color, #334155);
}

.welcome-section .text-primary {
    color: var(--pt-primary, #F59E0B) !important;
}

/* Stat Cards İyileştirmesi */
.stat-card-primary { border-left: 4px solid var(--pt-primary, #F59E0B) !important; }
.stat-card-success { border-left: 4px solid var(--pt-success, #10B981) !important; }
.stat-card-info { border-left: 4px solid var(--pt-accent, #3B82F6) !important; }
.stat-card-warning { border-left: 4px solid var(--pt-warning, #F59E0B) !important; }

.stat-card-primary .card-body::before {
    content: '';
    position: absolute;
    right: 0;
    bottom: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--pt-primary-subtle, rgba(245, 158, 11, 0.1)) 0%, transparent 100%);
    border-radius: 50% 0 0 0;
}

.stat-card-success .card-body::before {
    content: '';
    position: absolute;
    right: 0;
    bottom: 0;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, transparent 100%);
    border-radius: 50% 0 0 0;
}

/* Chart Container Stilleri */
.chart-card {
    transition: all 0.3s ease;
}

.chart-card:hover {
    transform: translateY(-2px);
}

.chart-card .card-header {
    background: var(--pt-card-header-bg, transparent);
    border-bottom: 1px solid var(--pt-card-border, rgba(226, 232, 240, 0.5));
}

/* Timeline İyileştirmesi */
.timeline-timed {
    position: relative;
    padding-left: 100px;
}

.timeline-timed .timeline-item {
    position: relative;
    padding: 0.75rem 0;
    border-left: 2px solid var(--pt-card-border, #E2E8F0);
    padding-left: 1.5rem;
    margin-left: 0;
}

.timeline-timed .timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 1rem;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--pt-primary, #F59E0B);
    border: 2px solid var(--pt-card-bg, #fff);
    box-shadow: 0 0 0 3px var(--pt-primary-subtle, rgba(245, 158, 11, 0.2));
}

.timeline-timed .timeline-time {
    position: absolute;
    left: -90px;
    top: 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--pt-text-muted, #64748B);
    background: var(--pt-table-stripe-bg, #F1F5F9);
    padding: 2px 8px;
    border-radius: 4px;
}

/* Notification Rich List */
.rich-list-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-radius: 12px;
    transition: all 0.2s ease;
    margin-bottom: 0.5rem;
    background: var(--pt-table-stripe-bg, rgba(241, 245, 249, 0.5));
    border: 1px solid var(--pt-card-border, transparent);
    flex-wrap: nowrap;
}

.rich-list-item:hover {
    background: var(--pt-dropdown-item-hover-bg, rgba(59, 130, 246, 0.08));
    transform: translateX(4px);
}

.rich-list-prepend {
    margin-right: 1rem;
    flex-shrink: 0;
}

.rich-list-content {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.rich-list-title {
    font-weight: 600;
    color: var(--pt-heading-color, #334155);
    font-size: 0.9rem;
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
    line-height: 1.4;
}

.rich-list-subtitle {
    font-size: 0.75rem;
    color: var(--pt-text-muted, #64748B);
    white-space: nowrap;
}

.rich-list-append {
    margin-left: 1rem;
    text-align: right;
    flex-shrink: 0;
    max-width: 50%;
}

/* Avatar Label Variations */
.avatar-label-primary {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
}

.avatar-label-success {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
}

.avatar-label-info {
    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
    color: white;
}

.avatar-label-warning {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
}

.avatar-label-danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
}

/* Quotes Carousel Geçiş */
#quotesCarousel .carousel-item {
    transition: opacity 0.6s ease-in-out;
}

#quotesCarousel .carousel-item p {
    font-style: italic;
    color: #64748B;
}

/* Progress Bars Mini */
.progress-xs {
    height: 4px;
}

.progress-xs .progress-bar {
    border-radius: 4px;
}

/* Number Counter Animation */
.counter-value {
    display: inline-block;
}

@keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.counter-animated {
    animation: countUp 0.6s ease forwards;
}

/* Metric Change Indicator */
.metric-change {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.metric-change.up {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.metric-change.down {
    background: rgba(239, 68, 68, 0.1);
    color: #DC2626;
}

/* Hover Cards */
.hover-card {
    position: relative;
    overflow: hidden;
}

.hover-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.hover-card:hover::after {
    left: 100%;
}
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fs-16 fw-semibold mb-1 mb-md-2">{{ $greeting ?? 'Günaydın' }}, <span class="text-primary">{{ $user->name ?? 'User' }}!</span></h4>
                    <div id="quotesCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($quotes as $index => $quote)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <p class="text-muted mb-0">{{ $quote }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $formatCurrency = function ($value) {
            return '₺' . number_format((float) $value, 2, ',', '.');
        };

        $formatPercent = function ($value) {
            return number_format((float) $value, 1, ',', '.');
        };

        $totalSummary = $salesSummary['total'] ?? ['value' => 0, 'collected' => 0, 'collectionRate' => 0];
        $currentInvoices = $salesSummary['currentMonthInvoices'] ?? ['value' => 0, 'change' => ['direction' => 'up', 'value' => 0]];
        $currentCollections = $salesSummary['currentMonthCollections'] ?? ['value' => 0, 'change' => ['direction' => 'up', 'value' => 0]];
        $collectionRate = $totalSummary['collectionRate'] ?? 0;
        $outstanding = max(0, ($totalSummary['value'] ?? 0) - ($totalSummary['collected'] ?? 0));

        $invoiceChange = $currentInvoices['change'] ?? ['direction' => 'up', 'value' => 0];
        $collectionChange = $currentCollections['change'] ?? ['direction' => 'up', 'value' => 0];
        $invoiceChangeIcon = $invoiceChange['direction'] === 'down' ? 'mdi mdi-menu-down text-danger' : 'mdi mdi-menu-up text-success';
        $collectionChangeIcon = $collectionChange['direction'] === 'down' ? 'mdi mdi-menu-down text-danger' : 'mdi mdi-menu-up text-success';

        $projectTotal = collect($projectHealthLegend ?? [])->sum('count');

        $projectTypeItems = collect(data_get($dashboardCharts, 'projectTypes.labels', []))
            ->zip(data_get($dashboardCharts, 'projectTypes.series', []))
            ->map(function ($pair) {
                [$label, $value] = $pair;
                return ['label' => $label, 'value' => (float) $value];
            })
            ->values();

        $voucherItems = collect(data_get($dashboardCharts, 'voucherBar.categories', []))
            ->zip(data_get($dashboardCharts, 'voucherBar.series', []))
            ->map(function ($pair) {
                [$label, $value] = $pair;
                return ['label' => $label, 'value' => (float) $value];
            })
            ->values();

        $progressItems = collect(data_get($dashboardCharts, 'progressRadar.categories', []))
            ->zip(data_get($dashboardCharts, 'progressRadar.series.0.data', []))
            ->map(function ($pair) {
                [$label, $value] = $pair;
                return ['name' => $label, 'progress' => (float) $value];
            })
            ->values();

        $timelineItems = collect($timelineFeed ?? []);
        $notificationItems = collect($notificationsFeed ?? []);
    @endphp

    <!-- High-Contrast Ultra-Modern KPI Grid -->
    <div class="row g-3">
        <div class="col-xxl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative border-start border-warning border-4" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-extrabold text-secondary tracking-wider d-block" style="font-size: 11px;">TOPLAM FATURA HACMİ</span>
                        <h3 class="fw-extrabold text-dark mb-1 mt-1 font-monospace" style="font-size: 1.65rem;">{{ $formatCurrency($totalSummary['value'] ?? 0) }}</h3>
                        <small class="text-success fw-bold d-inline-flex align-items-center" style="font-size: 12px;">
                            <i class="ri-checkbox-circle-fill me-1"></i>Tahsilat %{{ $formatPercent($collectionRate) }} ({{ $formatCurrency($totalSummary['collected'] ?? 0) }})
                        </small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-file-text-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative border-start border-success border-4" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-extrabold text-secondary tracking-wider d-block" style="font-size: 11px;">BU AY KESİLEN FATURA</span>
                        <h3 class="fw-extrabold text-dark mb-1 mt-1 font-monospace" style="font-size: 1.65rem;">{{ $formatCurrency($currentInvoices['value'] ?? 0) }}</h3>
                        <small class="metric-change {{ $invoiceChange['direction'] === 'down' ? 'down' : 'up' }} fw-bold">
                            <i class="{{ $invoiceChangeIcon }} me-1"></i>%{{ $formatPercent($invoiceChange['value'] ?? 0) }} geçen aya göre
                        </small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-calendar-event-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative border-start border-primary border-4" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-extrabold text-secondary tracking-wider d-block" style="font-size: 11px;">BU AY GERÇEKLEŞEN TAHSİLAT</span>
                        <h3 class="fw-extrabold text-dark mb-1 mt-1 font-monospace" style="font-size: 1.65rem;">{{ $formatCurrency($currentCollections['value'] ?? 0) }}</h3>
                        <small class="metric-change {{ $collectionChange['direction'] === 'down' ? 'down' : 'up' }} fw-bold">
                            <i class="{{ $collectionChangeIcon }} me-1"></i>%{{ $formatPercent($collectionChange['value'] ?? 0) }} geçen aya göre
                        </small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-cash-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative border-start border-danger border-4" style="box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-extrabold text-secondary tracking-wider d-block" style="font-size: 11px;">BEKLEYEN ALACAK / TAHSİLAT</span>
                        <h3 class="fw-extrabold text-danger mb-1 mt-1 font-monospace" style="font-size: 1.65rem;">{{ $formatCurrency($outstanding) }}</h3>
                        <small class="text-primary fw-bold d-inline-flex align-items-center" style="font-size: 12px;">
                            <i class="ri-building-4-line me-1"></i>Aktif Proje: {{ $projectTotal }}
                        </small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-alarm-warning-line fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1 mt-xxl-3">
        <div class="col-xxl-8">
            <div class="card h-100 chart-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="card-title mb-1"><i class="mdi mdi-chart-line me-2 text-primary"></i>Satış ve Tahsilat Trendleri</h4>
                        <p class="text-muted mb-0">Son 12 ayın fatura ve tahsilat performansı</p>
                    </div>
                    <div class="text-end">
                        <span class="metric-change {{ $invoiceChange['direction'] === 'down' ? 'down' : 'up' }}">
                            <i class="{{ $invoiceChangeIcon }} me-1"></i>%{{ $formatPercent($invoiceChange['value'] ?? 0) }}
                        </span>
                        <p class="text-muted mb-0 small">Faturalar - bu ay</p>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-sales-figures" class="apex-charts" style="min-height: 340px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card h-100 chart-card">
                <div class="card-header">
                    <h4 class="card-title mb-1"><i class="mdi mdi-heart-pulse me-2 text-success"></i>Proje Sağlığı</h4>
                    <p class="text-muted mb-0">Toplam {{ $projectTotal }} proje</p>
                </div>
                <div class="card-body">
                    <div id="chart-project-health" class="apex-charts" style="min-height: 260px;"></div>
                    <div class="mt-3">
                        @forelse($projectHealthLegend as $item)
                            <div class="d-flex align-items-center mb-3 p-2 rounded" style="background: rgba(241, 245, 249, 0.5); transition: all 0.2s ease;" onmouseover="this.style.background='rgba(59, 130, 246, 0.05)'" onmouseout="this.style.background='rgba(241, 245, 249, 0.5)'">
                                <div class="flex-shrink-0 me-3">
                                    <span class="avatar-title rounded-circle border border-2" style="color: {{ $item['color'] ?? '#0ab39c' }}; border-color: {{ $item['color'] ?? '#0ab39c' }}; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-checkbox-blank-circle"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">{{ $item['label'] ?? 'Durum' }}</span>
                                        <span class="text-muted">{{ $item['count'] ?? 0 }} proje</span>
                                    </div>
                                    <div class="progress progress-sm mt-1" style="height: 6px; border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $item['percentage'] ?? 0 }}%; background: linear-gradient(90deg, {{ $item['color'] ?? '#0ab39c' }}, {{ $item['color'] ?? '#0ab39c' }}88); border-radius: 10px;"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Proje verisi bulunamadı.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1 mt-xxl-3">
        <div class="col-xl-6">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-bottom-0 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="card-title mb-1 fw-bold text-dark"><i class="ri-bank-card-line me-2 text-primary"></i>Nakit Akışı & Bakiye Analizi</h4>
                        <p class="text-muted mb-0 small">Son 12 ayın toplam tahsilat, ödeme ve net farkı</p>
                    </div>
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill font-monospace" style="font-size: 13px;">
                            Net Bakiye: {{ $formatCurrency($dashboardCharts['cashFlow']['netBalance'] ?? 0) }}
                        </span>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <!-- Cash Flow Summary Metric Pills -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded-3 text-center" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                <small class="text-muted d-block font-size-11 fw-bold text-uppercase">Tahsilatlar</small>
                                <span class="fw-extrabold text-success font-size-13 font-monospace">{{ $formatCurrency($dashboardCharts['cashFlow']['totalCollections'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded-3 text-center" style="background: #fef2f2; border: 1px solid #fecaca;">
                                <small class="text-muted d-block font-size-11 fw-bold text-uppercase">Ödemeler</small>
                                <span class="fw-extrabold text-danger font-size-13 font-monospace">{{ $formatCurrency($dashboardCharts['cashFlow']['totalPayments'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded-3 text-center" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                                <small class="text-muted d-block font-size-11 fw-bold text-uppercase">Net Bakiye</small>
                                <span class="fw-extrabold text-primary font-size-13 font-monospace">{{ $formatCurrency($dashboardCharts['cashFlow']['netBalance'] ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <div id="chart-cash-flow" class="apex-charts" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Proje Türlerine Göre Bütçe</h4>
                    <span class="text-muted small">İlk {{ $projectTypeItems->count() }}</span>
                </div>
                <div class="card-body">
                    <div id="chart-project-types" class="apex-charts" style="min-height: 300px;"></div>
                    <div class="mt-3">
                        @forelse($projectTypeItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $item['label'] }}</span>
                                <span class="fw-semibold">{{ $formatCurrency($item['value']) }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Proje türü verisi bulunamadı.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1 mt-xxl-3">
        <div class="col-xxl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Son Tahsilatlar</h4>
                    <span class="text-muted small">İlk {{ min(10, $voucherItems->count()) }}</span>
                </div>
                <div class="card-body">
                    <div id="chart-voucher-bar" class="apex-charts" style="min-height: 280px;"></div>
                    <div class="mt-3 table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Belge</th>
                                    <th class="text-end">Tutar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($voucherItems as $item)
                                    <tr>
                                        <td>{{ $item['label'] }}</td>
                                        <td class="text-end fw-semibold">{{ $formatCurrency($item['value']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Tahsilat kaydı bulunamadı.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Günlük Akış</h4>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-timed">
                        @forelse($timelineItems as $item)
                            <div class="timeline-item mb-3">
                                <span class="timeline-time">{{ $item['time'] ?? '--:--' }}</span>
                                <div class="timeline-pin"><i class="marker marker-circle text-{{ $item['status'] ?? 'primary' }}"></i></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $item['title'] ?? 'Etkinlik' }}</h6>
                                    <p class="text-muted mb-0 small">{{ $item['description'] ?? '' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Henüz zaman çizelgesi kaydı yok.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1 mt-xxl-3">
        <div class="col-xxl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="card-title mb-1">Proje İlerlemesi</h4>
                        <p class="text-muted mb-0">Tamamlanma yüzdelerine göre en iyi projeler</p>
                    </div>
                    <span class="badge bg-soft-info text-info rounded-pill">{{ $progressItems->count() }} proje</span>
                </div>
                <div class="card-body">
                    <div id="chart-progress-radar" class="apex-charts" style="min-height: 320px;"></div>
                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Proje</th>
                                        <th class="text-end">Tamamlanma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($progressItems as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-end">
                                                <span class="fw-semibold">%{{ $formatPercent($item['progress']) }}</span>
                                                <div class="progress progress-xs mt-1">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, max(0, $item['progress'])) }}%;"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Proje ilerleme verisi bulunamadı.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Bildirimler</h4>
                    <span class="badge bg-soft-secondary text-muted">{{ $notificationItems->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="rich-list rich-list-bordered rich-list-action">
                        @forelse($notificationItems as $item)
                            <div class="rich-list-item">
                                <div class="rich-list-prepend">
                                    <div class="avatar avatar-label-{{ $item['priority'] ?? 'primary' }}" style="width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                </div>
                                <div class="rich-list-content">
                                    <h5 class="rich-list-title mb-1">{{ $item['title'] ?? 'Bildirim' }}</h5>
                                    <p class="rich-list-subtitle mb-0 text-muted">{{ $item['time'] ?? '' }}</p>
                                </div>
                                <div class="rich-list-append">
                                    <span class="text-muted small" style="display: block; word-wrap: break-word;">{{ $item['message'] ?? '' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Görüntülenecek bildirim bulunamadı.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ambient Music Player -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon text-muted">
                        <i class="fas fa-music"></i>
                    </div>
                    <h4 class="card-title mb-0">Odaklanma Müzikleri & Radyo</h4>
                    <div class="card-addon">
                        <button class="btn btn-sm btn-label-secondary" id="toggleMusicPlayer">
                            <i class="fas fa-play"></i> Aç
                        </button>
                    </div>
                </div>
                <div class="card-body" id="musicPlayerContainer" style="display: none;">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#ambient-sounds" role="tab" aria-selected="true">
                                <i class="fas fa-volume-up me-1"></i>Ambient Sesler
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#radio-stations" role="tab" aria-selected="false">
                                <i class="fas fa-radio me-1"></i>Canlı Radyo
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Ambient Sounds Tab -->
                        <div class="tab-pane active" id="ambient-sounds" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="ambientSelect" class="form-label">Ambient Ses Seçin</label>
                                        <select class="form-select" id="ambientSelect">
                                            @foreach($ambientSounds as $sound)
                                                <option value="{{ $sound['url'] }}" data-icon="{{ $sound['icon'] }}">
                                                    {{ $sound['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn btn-outline-primary btn-sm" id="playAmbientBtn">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" id="pauseAmbientBtn">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" id="stopAmbientBtn">
                                            <i class="fas fa-stop"></i>
                                        </button>
                                        <div class="flex-grow-1">
                                            <input type="range" class="form-range" id="volumeAmbientControl" min="0" max="1" step="0.1" value="0.5">
                                        </div>
                                        <span id="currentAmbientName" class="text-muted small">Yağmur Sesi</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div id="ambientIcon" class="mb-2">
                                            <i class="fas fa-cloud-rain fa-2x text-primary"></i>
                                        </div>
                                        <small class="text-muted">Odaklanma için ambient sesler</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Radio Stations Tab -->
                        <div class="tab-pane" id="radio-stations" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="radioSelect" class="form-label">Radyo İstasyonu Seçin</label>
                                        <select class="form-select" id="radioSelect">
                                            @foreach($radioStations as $station)
                                                <option value="{{ $station['url'] }}" data-genre="{{ $station['genre'] }}" data-country="{{ $station['country'] }}">
                                                    {{ $station['name'] }} - {{ $station['genre'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn btn-outline-success btn-sm" id="playRadioBtn">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" id="pauseRadioBtn">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" id="stopRadioBtn">
                                            <i class="fas fa-stop"></i>
                                        </button>
                                        <div class="flex-grow-1">
                                            <input type="range" class="form-range" id="volumeRadioControl" min="0" max="1" step="0.1" value="0.5">
                                        </div>
                                        <span id="currentRadioName" class="text-muted small">RADYO ODTÜ CANLI</span>
                                    </div>
                                    <div class="mt-2">
                                        <small id="radioInfo" class="text-muted">Çeşitli • Türkiye</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div id="radioIcon" class="mb-2">
                                            <i class="fas fa-broadcast-tower fa-2x text-success"></i>
                                        </div>
                                        <small class="text-muted">Canlı radyo yayını</small>
                                        <div class="mt-2">
                                            <div class="d-flex justify-content-center">
                                                <div class="radio-wave" id="radioWave">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <audio id="ambientAudio" preload="none"></audio>
                    <audio id="radioAudio" preload="none"></audio>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const charts = @json($dashboardCharts);
            const projectHealthLegend = @json($projectHealthLegend);

            const rootStyles = getComputedStyle(document.documentElement);
            const getCssVar = (name, fallback) => {
                if (!name) {
                    return fallback || '#0d6efd';
                }
                if (!name.startsWith('--')) {
                    return name;
                }
                return rootStyles.getPropertyValue(name)?.trim() || fallback || name;
            };

            const renderChart = (selector, options) => {
                const element = document.querySelector(selector);
                if (!element) {
                    return null;
                }
                const chart = new ApexCharts(element, options);
                chart.render();
                return chart;
            };

            if (charts?.salesFigures?.series?.length) {
                const series = charts.salesFigures.series.map((item, index) => ({
                    name: item.name || `Seri ${index + 1}`,
                    data: (item.data || []).map(Number),
                }));

                renderChart('#chart-sales-figures', {
                    chart: {
                        type: 'area',
                        height: 340,
                        toolbar: { show: false },
                    },
                    series,
                    xaxis: {
                        categories: charts.salesFigures.categories || [],
                        labels: { rotate: -45 },
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3,
                    },
                    dataLabels: { enabled: false },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 90, 100],
                        },
                    },
                    colors: [getCssVar('--bs-primary'), getCssVar('--bs-success'), getCssVar('--bs-info')],
                    grid: {
                        strokeDashArray: 4,
                        padding: { left: 10, right: 10 },
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                    },
                    tooltip: {
                        y: {
                            formatter(value) {
                                return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 2 }).format(value || 0);
                            },
                        },
                    },
                });
            }

            if (charts?.cashFlow?.series?.length) {
                const monthCats = (charts.cashFlow.monthLabels || []).length ? charts.cashFlow.monthLabels : (charts.cashFlow.categories || []);
                renderChart('#chart-cash-flow', {
                    chart: {
                        type: 'line',
                        height: 300,
                        toolbar: { show: false },
                    },
                    series: [
                        {
                            name: 'Tahsilatlar (Giriş)',
                            type: 'column',
                            data: (charts.cashFlow.collections || []).map(Number),
                        },
                        {
                            name: 'Ödemeler (Çıkış)',
                            type: 'column',
                            data: (charts.cashFlow.payments || []).map(Number),
                        },
                        {
                            name: 'Net Nakit Farkı',
                            type: 'line',
                            data: (charts.cashFlow.series || []).map(Number),
                        }
                    ],
                    colors: ['#10B981', '#F43F5E', '#6366F1'],
                    stroke: {
                        width: [0, 0, 3],
                        curve: 'smooth'
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '45%',
                            borderRadius: 4,
                        },
                    },
                    xaxis: {
                        categories: monthCats,
                        labels: {
                            rotate: -45,
                            style: { fontWeight: 600 }
                        },
                    },
                    yaxis: {
                        labels: {
                            formatter(value) {
                                return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 0 }).format(value || 0);
                            },
                        },
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                    },
                    dataLabels: { enabled: false },
                    grid: { strokeDashArray: 4 },
                    tooltip: {
                        y: {
                            formatter(value) {
                                return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 2 }).format(value || 0);
                            },
                        },
                    },
                });
            }

            if (charts?.projectHealth?.series?.length) {
                const colors = (projectHealthLegend || []).map(item => item.color || getCssVar('--bs-primary'));
                renderChart('#chart-project-health', {
                    chart: {
                        type: 'donut',
                        height: 260,
                    },
                    series: charts.projectHealth.series,
                    labels: charts.projectHealth.labels,
                    colors: colors.length ? colors : [getCssVar('--bs-primary')],
                    legend: {
                        position: 'bottom',
                        offsetY: 0,
                    },
                    stroke: { width: 0 },
                    dataLabels: {
                        enabled: true,
                        formatter(value) {
                            return `${value.toFixed(1)}%`;
                        },
                    },
                });
            }

            if (charts?.projectTypes?.series?.length) {
                renderChart('#chart-project-types', {
                    chart: {
                        type: 'donut',
                        height: 300,
                    },
                    series: charts.projectTypes.series,
                    labels: charts.projectTypes.labels,
                    legend: {
                        position: 'bottom',
                    },
                    colors: [getCssVar('--bs-primary'), getCssVar('--bs-success'), getCssVar('--bs-warning'), getCssVar('--bs-danger'), getCssVar('--bs-info'), getCssVar('--bs-secondary')],
                    dataLabels: {
                        enabled: true,
                        formatter(value) {
                            return `${value.toFixed(1)}%`;
                        },
                    },
                });
            }

            if (charts?.voucherBar?.series?.length) {
                renderChart('#chart-voucher-bar', {
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: { show: false },
                    },
                    series: [{
                        name: 'Tahsilat',
                        data: (charts.voucherBar.series || []).map(Number),
                    }],
                    xaxis: {
                        categories: charts.voucherBar.categories || [],
                        labels: { rotate: -45 },
                    },
                    yaxis: {
                        labels: {
                            formatter(value) {
                                return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 0 }).format(value || 0);
                            },
                        },
                    },
                    colors: [getCssVar('--bs-danger')],
                    plotOptions: {
                        bar: {
                            columnWidth: '40%',
                            borderRadius: 6,
                        },
                    },
                    dataLabels: { enabled: false },
                    grid: { strokeDashArray: 4 },
                    tooltip: {
                        y: {
                            formatter(value) {
                                return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 2 }).format(value || 0);
                            },
                        },
                    },
                });
            }

            if (charts?.progressRadar?.series?.length) {
                const barSeries = charts.progressRadar.series.map((serie, index) => ({
                    name: serie.name || `Seri ${index + 1}`,
                    data: (serie.data || []).map(Number),
                }));

                renderChart('#chart-progress-radar', {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false },
                    },
                    series: barSeries,
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 4,
                            barHeight: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        textAnchor: 'start',
                        style: {
                            colors: ['#fff']
                        },
                        formatter: function (val, opt) {
                            return opt.w.globals.labels[opt.dataPointIndex] + ":  %" + val
                        },
                        offsetX: 0,
                    },
                    xaxis: {
                        categories: charts.progressRadar.categories || [],
                        max: 100,
                        labels: {
                            formatter: function (val) {
                                return "%" + val
                            }
                        }
                    },
                    yaxis: {
                        show: false, // hide the default yaxis labels because we show them inside the bars
                    },
                    colors: [getCssVar('--bs-primary')],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            type: "horizontal",
                            shadeIntensity: 0.5,
                            gradientToColors: [getCssVar('--bs-info')],
                            inverseColors: true,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 100]
                        }
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter(value) {
                                return `%${Number(value || 0).toFixed(1)}`;
                            },
                        },
                    },
                });
            }
        });
    </script>
    <!-- Quotes Carousel -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var carousel = new bootstrap.Carousel(document.getElementById('quotesCarousel'), {
                interval: 5000, // 5 saniyede bir geçiş
                ride: 'carousel'
            });
        });
    </script>
    <!-- Ambient Music Player -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambient Audio Elements
            const ambientAudio = document.getElementById('ambientAudio');
            const playAmbientBtn = document.getElementById('playAmbientBtn');
            const pauseAmbientBtn = document.getElementById('pauseAmbientBtn');
            const stopAmbientBtn = document.getElementById('stopAmbientBtn');
            const volumeAmbientControl = document.getElementById('volumeAmbientControl');
            const ambientSelect = document.getElementById('ambientSelect');
            const currentAmbientName = document.getElementById('currentAmbientName');
            const ambientIcon = document.getElementById('ambientIcon');

            // Radio Audio Elements
            const radioAudio = document.getElementById('radioAudio');
            const playRadioBtn = document.getElementById('playRadioBtn');
            const pauseRadioBtn = document.getElementById('pauseRadioBtn');
            const stopRadioBtn = document.getElementById('stopRadioBtn');
            const volumeRadioControl = document.getElementById('volumeRadioControl');
            const radioSelect = document.getElementById('radioSelect');
            const currentRadioName = document.getElementById('currentRadioName');
            const radioInfo = document.getElementById('radioInfo');
            const radioWave = document.getElementById('radioWave');

            // Common Elements
            const toggleMusicPlayer = document.getElementById('toggleMusicPlayer');
            const musicPlayerContainer = document.getElementById('musicPlayerContainer');

            // Toggle music player visibility
            toggleMusicPlayer.addEventListener('click', function() {
                if (musicPlayerContainer.style.display === 'none') {
                    musicPlayerContainer.style.display = 'block';
                    toggleMusicPlayer.innerHTML = '<i class="fas fa-pause"></i> Gizle';
                } else {
                    musicPlayerContainer.style.display = 'none';
                    toggleMusicPlayer.innerHTML = '<i class="fas fa-play"></i> Aç';
                    ambientAudio.pause();
                    radioAudio.pause();
                    stopRadioWave();
                }
            });

            // Ambient Sound Controls
            ambientSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const soundUrl = this.value;
                const soundName = selectedOption.text;
                const iconClass = selectedOption.getAttribute('data-icon');

                ambientAudio.src = soundUrl;
                currentAmbientName.textContent = soundName;
                ambientIcon.innerHTML = `<i class="${iconClass} fa-2x text-primary"></i>`;
            });

            playAmbientBtn.addEventListener('click', function() {
                if (ambientAudio.src) {
                    radioAudio.pause(); // Stop radio if playing
                    stopRadioWave();
                    ambientAudio.play();
                    playAmbientBtn.innerHTML = '<i class="fas fa-play text-success"></i>';
                    pauseAmbientBtn.innerHTML = '<i class="fas fa-pause"></i>';
                }
            });

            pauseAmbientBtn.addEventListener('click', function() {
                ambientAudio.pause();
                playAmbientBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseAmbientBtn.innerHTML = '<i class="fas fa-pause text-warning"></i>';
            });

            stopAmbientBtn.addEventListener('click', function() {
                ambientAudio.pause();
                ambientAudio.currentTime = 0;
                playAmbientBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseAmbientBtn.innerHTML = '<i class="fas fa-pause"></i>';
            });

            volumeAmbientControl.addEventListener('input', function() {
                ambientAudio.volume = this.value;
            });

            // Radio Controls
            radioSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const stationUrl = this.value;
                const stationName = selectedOption.text.split(' - ')[0];
                const genre = selectedOption.getAttribute('data-genre');
                const country = selectedOption.getAttribute('data-country');

                radioAudio.src = stationUrl;
                currentRadioName.textContent = stationName;
                radioInfo.textContent = `${genre} • ${country}`;
            });

            playRadioBtn.addEventListener('click', function() {
                if (radioAudio.src) {
                    ambientAudio.pause(); // Stop ambient if playing
                    radioAudio.play();
                    playRadioBtn.innerHTML = '<i class="fas fa-play text-success"></i>';
                    pauseRadioBtn.innerHTML = '<i class="fas fa-pause"></i>';
                    startRadioWave();
                }
            });

            pauseRadioBtn.addEventListener('click', function() {
                radioAudio.pause();
                playRadioBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseRadioBtn.innerHTML = '<i class="fas fa-pause text-warning"></i>';
                stopRadioWave();
            });

            stopRadioBtn.addEventListener('click', function() {
                radioAudio.pause();
                radioAudio.currentTime = 0;
                playRadioBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseRadioBtn.innerHTML = '<i class="fas fa-pause"></i>';
                stopRadioWave();
            });

            volumeRadioControl.addEventListener('input', function() {
                radioAudio.volume = this.value;
            });

            // Radio Wave Animation
            function startRadioWave() {
                radioWave.classList.add('active');
            }

            function stopRadioWave() {
                radioWave.classList.remove('active');
            }

            // Audio Events
            ambientAudio.addEventListener('ended', function() {
                playAmbientBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseAmbientBtn.innerHTML = '<i class="fas fa-pause"></i>';
            });

            radioAudio.addEventListener('ended', function() {
                playRadioBtn.innerHTML = '<i class="fas fa-play"></i>';
                pauseRadioBtn.innerHTML = '<i class="fas fa-pause"></i>';
                stopRadioWave();
            });

            // Initialize
            ambientAudio.volume = volumeAmbientControl.value;
            radioAudio.volume = volumeRadioControl.value;
            ambientSelect.dispatchEvent(new Event('change'));
            radioSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
