@extends('layouts.master')

@section('title', 'Portföy Yönetimi - Pergel')

@push('styles')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/leaflet/dist/leaflet.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <style>
        /* MAP STYLES */
        #management-map {
            width: 100%;
            height: 480px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            border: 1.5px solid #cbd5e1;
        }

        /* HERO HEADER */
        .portfolio-hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
            border-radius: 20px !important;
            border: 1px solid rgba(245, 158, 11, 0.3) !important;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
            padding: 32px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .portfolio-hero-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
            background-size: 300% 100%;
            animation: portGlow 4s linear infinite;
        }

        @keyframes portGlow {
            0% { background-position: 0% 50%; }
            100% { background-position: 300% 50%; }
        }

        .port-badge {
            background: rgba(245, 158, 11, 0.2) !important;
            border: 1px solid rgba(245, 158, 11, 0.4) !important;
            color: #fef08a !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px !important;
        }

        /* MODERN KPI CARDS */
        .custom-kpi-card-port {
            background: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 16px !important;
            padding: 20px 22px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .custom-kpi-card-port:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
        }

        /* TABLE */
        .port-table {
            vertical-align: middle;
        }

        .port-table th {
            background: #F8FAFC;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            border-bottom: 1.5px solid #cbd5e1;
            padding: 14px 16px;
        }

        .port-table td {
            padding: 16px;
            border-bottom: 1px solid #E2E8F0;
        }

        .port-card-container {
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 18px !important;
            background: #ffffff !important;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER SECTION -->
    <div class="portfolio-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill port-badge">
                        <i class="ri-building-4-fill text-warning fs-5"></i> STRATEJİK PORTFÖY YÖNETİM MERKEZİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        %{{ $portfolioHealth }} Genel Sağlık
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Portföy Yönetim & Şantiye Kontrol Merkezi</h2>
                <p class="text-white-50 mb-0 fs-6" style="max-width: 780px; line-height: 1.6;">
                    Projelerinizin finansal bütçesini, imalat ilerlemesini, harita konumlarını ve risk matrisini canlı izleyen stratejik yönetim platformu.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('portfolio.financials.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-wallet-3-line fs-5"></i>Finansal Portföy
                    </a>
                    <a href="{{ route('projects.index') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-list-check-2 fs-5"></i>Tüm Projeler ({{ $totalProjects }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MODERN KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-port h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM PROJE SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalProjects }} Proje</div>
                        <small class="text-warning fw-bold"><i class="ri-building-line me-1"></i>Aktif Şantiye Sahası</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-building-4-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-port h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM PORTFÖY DEĞERİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalPortfolioValue, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-shield-check-fill me-1"></i>Bütçe Uyumlu Portföy</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-wallet-3-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-port h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">PORTFÖY SAĞLIK SKORU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">%{{ $portfolioHealth }}</div>
                        <small class="text-info fw-bold"><i class="ri-heart-pulse-fill me-1"></i>Genel Şantiye İyilik</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-pulse-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-port h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ZAMANINDA TESLİM ORANI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">%{{ $onTimeCompletionRate }}</div>
                        <small class="text-primary fw-bold"><i class="ri-history-fill me-1"></i>İmalat Takvim Uyumu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-time-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="row g-4 mb-4">
        <!-- PORTFOLIO HEALTH TREND -->
        <div class="col-xl-8">
            <div class="card port-card-container shadow-sm h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-line-chart-fill text-warning fs-5"></i>Portföy Sağlık Trendi (6 Aylık)
                        </h5>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">Aylık Trend</span>
                </div>
                <div class="card-body p-4">
                    <canvas id="healthTrendChart" height="130"></canvas>
                </div>
            </div>
        </div>

        <!-- BUDGET COMPLIANCE -->
        <div class="col-xl-4">
            <div class="card port-card-container shadow-sm h-100">
                <div class="card-header bg-dark text-white p-3.5">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-pie-chart-fill text-warning fs-5"></i>Bütçe Uyumu & Harcama Oranı
                    </h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <canvas id="budgetComplianceChart" height="140"></canvas>
                    <div class="p-3 rounded-4 mt-3" style="background: #F8FAFC; border: 1.5px solid #cbd5e1;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-dark fw-extrabold font-size-13">Bütçe Uyumlu Projeler</span>
                            <strong class="text-success font-monospace font-size-13">%{{ $budgetComplianceRate }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-dark fw-extrabold font-size-13">Bütçe Aşımı Riski</span>
                            <strong class="text-danger font-monospace font-size-13">%{{ 100 - $budgetComplianceRate }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GEOGRAPHIC MAP SECTION -->
    <div class="card port-card-container shadow-sm mb-4">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-map-pin-2-fill text-warning fs-5"></i>Tüm Şantiyeler & Projeler Coğrafi Haritası
                </h5>
            </div>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-extrabold">Canlı Konumlar</span>
        </div>
        <div class="card-body p-4">
            <div id="management-map"></div>
        </div>
    </div>

    <!-- BOTTOM SECTION: CRITICAL PROJECTS & RISKS -->
    <div class="row g-4 mb-4">
        <!-- CRITICAL PROJECTS TABLE -->
        <div class="col-xl-7">
            <div class="card port-card-container shadow-sm h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-alert-fill text-warning fs-5"></i>Öncelikli & Kritik Projeler
                        </h5>
                    </div>
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3.5 py-1.5 fw-extrabold shadow-sm">Tüm Projeler</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table port-table table-hover mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4">Proje Adı</th>
                                    <th>Tamamlanma %</th>
                                    <th>Durum</th>
                                    <th class="pe-4 text-end">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects->take(6) as $p)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-extrabold text-dark fs-6">{{ $p->name }}</div>
                                            <small class="text-secondary fw-semibold">Bütçe: ₺{{ number_format($p->budget ?? 0, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1 rounded-pill" style="height: 6px; background: #E2E8F0;">
                                                    <div class="progress-bar bg-success" style="width: {{ $p->progress_percent ?? 50 }}%;"></div>
                                                </div>
                                                <span class="fw-extrabold text-dark font-monospace" style="font-size: 11.5px;">%{{ $p->progress_percent ?? 50 }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if(($p->progress_percent ?? 50) > 60)
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">Sağlıklı</span>
                                            @elseif(($p->progress_percent ?? 50) > 30)
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Takipte</span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Kritik</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('projects.show', $p->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Detay">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Proje kaydı yükleniyor...</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MILESTONES & RISKS PANEL -->
        <div class="col-xl-5">
            <div class="card port-card-container shadow-sm h-100">
                <div class="card-header bg-dark text-white p-3.5">
                    <ul class="nav nav-pills card-header-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active rounded-pill font-size-13 fw-extrabold px-3 text-dark bg-warning" data-bs-toggle="tab" href="#milestones" role="tab">
                                🚩 Kilometre Taşları
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill font-size-13 fw-extrabold px-3 ms-2 text-white" data-bs-toggle="tab" href="#risks" role="tab">
                                ⚠️ Risk Matrisi
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- MILESTONES TAB -->
                        <div class="tab-pane active" id="milestones" role="tabpanel">
                            <div class="p-3.5 rounded-4 mb-2.5 d-flex align-items-center justify-content-between" style="background: #F8FAFC; border: 1.5px solid #cbd5e1;">
                                <div>
                                    <strong class="fw-extrabold text-dark d-block">🏢 Göktürk C Blok Temel Beton Dökümü</strong>
                                    <small class="text-secondary fw-semibold">Hedef: 25.08.2026 — Yapı Kontrol Onaylı</small>
                                </div>
                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">Zamanında</span>
                            </div>

                            <div class="p-3.5 rounded-4 mb-2.5 d-flex align-items-center justify-content-between" style="background: #F8FAFC; border: 1.5px solid #cbd5e1;">
                                <div>
                                    <strong class="fw-extrabold text-dark d-block">🏭 Bursa OSB Çelik Çatı Makas Montajı</strong>
                                    <small class="text-secondary fw-semibold">Hedef: 10.09.2026 — Üretim Aşamasında</small>
                                </div>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #2563eb; font-size: 11px;">Planlandı</span>
                            </div>

                            <div class="p-3.5 rounded-4 d-flex align-items-center justify-content-between" style="background: #F8FAFC; border: 1.5px solid #cbd5e1;">
                                <div>
                                    <strong class="fw-extrabold text-dark d-block">🏢 Pergel Tower Cam Cephe Kaplama</strong>
                                    <small class="text-secondary fw-semibold">Hedef: 30.09.2026 — Numune Onayında</small>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">İncelemede</span>
                            </div>
                        </div>

                        <!-- RISKS TAB -->
                        <div class="tab-pane" id="risks" role="tabpanel">
                            <div class="p-3.5 rounded-4 mb-2.5 d-flex align-items-center justify-content-between" style="background: #fff1f2; border: 1.5px solid #fecdd3;">
                                <div>
                                    <strong class="text-danger fw-extrabold d-block">⚠️ Nervürlü Demir Fiyat Dalgalanması</strong>
                                    <small class="text-secondary fw-semibold">Maliyet Etkisi: %4.2 Bütçe Toleransı</small>
                                </div>
                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Kritik</span>
                            </div>

                            <div class="p-3.5 rounded-4 d-flex align-items-center justify-content-between" style="background: #fffbeb; border: 1.5px solid #fde68a;">
                                <div>
                                    <strong class="text-warning fw-extrabold d-block">🌧️ Sonbahar Yağış Rüzgar Riski</strong>
                                    <small class="text-secondary fw-semibold">Zaman Etkisi: 3-5 Gün Gecikme Payı</small>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Orta Risk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('build/libs/leaflet/dist/leaflet.js') }}"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Init Leaflet Map
            initPortfolioMap();

            // Init Charts
            initHealthTrendChart();
            initBudgetComplianceChart();
        });

        function initPortfolioMap() {
            const mapContainer = document.getElementById('management-map');
            if (!mapContainer) return;

            const map = L.map('management-map').setView([41.0082, 28.9784], 9);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const sampleLocations = [
                { name: 'Göktürk Kemer Country Genişletme', lat: 41.1825, lng: 28.8912, progress: 85, url: '{{ route("projects.show", 1) }}' },
                { name: 'Pergel Tower İş Merkezi', lat: 41.0782, lng: 29.0115, progress: 62, url: '{{ route("projects.show", 2) }}' },
                { name: 'Bursa OSB Fabrika İnşaatı', lat: 40.2125, lng: 28.9612, progress: 44, url: '{{ route("projects.show", 3) }}' },
                { name: 'Vadi İstanbul Premium Rezidans', lat: 41.1012, lng: 28.9891, progress: 91, url: '{{ route("projects.show", 4) }}' },
                { name: 'Adana Çukurova Kuleleri', lat: 37.0322, lng: 35.3112, progress: 38, url: '{{ route("projects.show", 5) }}' }
            ];

            sampleLocations.forEach(loc => {
                const marker = L.marker([loc.lat, loc.lng]).addTo(map);
                marker.bindPopup(`
                    <div style="padding: 6px;">
                        <h6 style="margin: 0 0 6px 0; font-weight: 800;">${loc.name}</h6>
                        <small style="color: #10B981; font-weight: 700;">İlerleme: %${loc.progress}</small><br>
                        <a href="${loc.url}" class="btn btn-xs btn-primary mt-2 rounded-pill font-size-11" style="padding: 3px 10px; color: #fff;">
                             Proje Detayına Git
                        </a>
                    </div>
                `);
            });
        }

        function initHealthTrendChart() {
            const ctx = document.getElementById('healthTrendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos'],
                    datasets: [
                        {
                            label: 'Sağlıklı Projeler',
                            data: [14, 15, 15, 16, 16, 17],
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Takipteki Projeler',
                            data: [3, 3, 2, 2, 2, 2],
                            borderColor: '#d97706',
                            backgroundColor: 'transparent',
                            tension: 0.4
                        },
                        {
                            label: 'Kritik Projeler',
                            data: [2, 1, 1, 1, 1, 1],
                            borderColor: '#e11d48',
                            backgroundColor: 'transparent',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }

        function initBudgetComplianceChart() {
            const ctx = document.getElementById('budgetComplianceChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Bütçe Uyumlu', 'Bütçe Aşımı Riski'],
                    datasets: [{
                        data: [{{ $budgetComplianceRate }}, {{ 100 - $budgetComplianceRate }}],
                        backgroundColor: ['#16a34a', '#e11d48']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    </script>
@endpush
