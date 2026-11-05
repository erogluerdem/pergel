@extends('layouts.master')

@section('title', 'Tesis Yönetimi & Bina İşletme Portalı')

@section('topbar-title', 'Tesis Yönetimi - Ana Pano')

@push('styles')
<!-- Leaflet CSS for Interactive Facility Asset Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

<style>
    /* Hero Header Banner */
    .facility-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .facility-hero-banner,
    :root:not([data-bs-theme="dark"]) .facility-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .facility-hero-banner {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .facility-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .facility-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .facility-kpi-card,
    :root:not([data-bs-theme="dark"]) .facility-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .facility-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .facility-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .facility-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .facility-glass-card,
    :root:not([data-bs-theme="dark"]) .facility-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .facility-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    /* Map Container Style */
    #facility-asset-map {
        height: 420px;
        width: 100%;
        border-radius: 16px;
        z-index: 1;
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="facility-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-building-4-line me-1"></i>Facility & Asset Operations
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tesis Yönetimi & Bina İşletme Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">HVAC iklimlendirme, jeneratör, asansör bakımları ve canlı harita konum takibi.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('facility.assets') }}" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#059669;">
                <i class="ri-search-eye-line me-1"></i>Varlık Parkını İncele
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="facility-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Açık İş Emirleri</span>
                    <h2 class="fw-extrabold mb-0 text-emerald" style="color:#059669;">{{ $openWorkOrders }} Emri</h2>
                </div>
                <div class="facility-stat-icon bg-emerald bg-opacity-10 text-emerald" style="color:#059669;">
                    <i class="ri-tools-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="facility-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Geciken Bakımlar</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $overdueMaintenance }} Ekipman</h2>
                </div>
                <div class="facility-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-alarm-warning-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="facility-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aylık Servis Talepleri</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ $monthlyRequests }} Talep</h2>
                </div>
                <div class="facility-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-customer-service-2-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="facility-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Kayıtlı Varlık</span>
                    <h2 class="fw-extrabold mb-0 text-info">{{ $totalAssets }} Varlık</h2>
                </div>
                <div class="facility-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-layout-grid-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Facility Asset Location Map Section -->
<div class="facility-glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="ri-map-pin-2-line text-emerald me-2" style="color:#059669;"></i>Canlı Tesis & Ekipman Lokasyon Haritası
            </h5>
            <small class="text-muted">Şantiye ve bina lokasyonlarındaki tüm aktif ekipman ve cihazların coğrafi yerleşimi.</small>
        </div>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($mapAssets) }} Harita İşaretçisi
        </span>
    </div>

    <!-- Map Canvas Container -->
    <div id="facility-asset-map" class="border shadow-sm"></div>
</div>

<div class="row g-3">
    <!-- Yaklaşan Bakımlar Kartı -->
    <div class="col-lg-7">
        <div class="facility-glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ri-calendar-check-line text-emerald me-2" style="color:#059669;"></i>Yaklaşan Periyodik Bakımlar
                </h5>
                <a href="{{ route('facility.maintenance') }}" class="btn btn-sm btn-outline-emerald rounded-pill px-3" style="color:#059669; border-color:#059669;">
                    Tümünü Gör
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Varlık Kodu</th>
                            <th>Ekipman Tanımı</th>
                            <th>Lokasyon</th>
                            <th>Sonraki Bakım Tarihi</th>
                            <th class="text-end pe-3">Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingMaintenances as $um)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                        {{ $um->asset_code }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $um->name }}</strong>
                                    <small class="text-muted">{{ $um->category }}</small>
                                </td>
                                <td>
                                    <span class="text-dark small">{{ $um->location ?? 'Genel Alan' }}</span>
                                </td>
                                <td>
                                    <span class="text-dark small fw-bold">{{ $um->next_maintenance_date ? \Carbon\Carbon::parse($um->next_maintenance_date)->format('d.m.Y') : '-' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Planlandı</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Yaklaşan bakım kaydı bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Son Aktiviteler & Sistem Durumu -->
    <div class="col-lg-5">
        <div class="facility-glass-card">
            <h5 class="fw-bold mb-3 text-dark">
                <i class="ri-pulse-line text-emerald me-2" style="color:#059669;"></i>Sistem Durumu & Sensörler
            </h5>

            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4">
                <div class="row g-2 text-center">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block mb-1">Çalışma Süresi (Uptime)</small>
                        <strong class="fs-5 text-success">{{ $systemStatus['uptime'] ?? '99.9%' }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block mb-1">Ortam Sıcaklığı</small>
                        <strong class="fs-5 text-primary">{{ $systemStatus['temperature'] ?? '22°C' }}</strong>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Son Tesis Aktiviteleri</h6>
            <div class="list-group list-group-flush">
                @foreach($recentActivities as $act)
                    <div class="list-group-item bg-transparent px-0 py-2 border-bottom border-opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-dark small">{{ $act['title'] }}</strong>
                            <small class="text-muted font-monospace">{{ $act['created_at']->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted d-block">{{ $act['description'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Leaflet JS for Interactive Facility Asset Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assetsData = @json($mapAssets);

        // Center on Istanbul / default location if assets exist
        let defaultLat = 41.0082;
        let defaultLng = 28.9784;

        if (assetsData.length > 0 && assetsData[0].latitude && assetsData[0].longitude) {
            defaultLat = parseFloat(assetsData[0].latitude);
            defaultLng = parseFloat(assetsData[0].longitude);
        }

        const map = L.map('facility-asset-map').setView([defaultLat, defaultLng], 14);

        // OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers for all facility assets
        assetsData.forEach(function (asset) {
            if (asset.latitude && asset.longitude) {
                const marker = L.marker([parseFloat(asset.latitude), parseFloat(asset.longitude)]).addTo(map);

                const popupContent = `
                    <div style="min-width: 200px;">
                        <span class="badge bg-primary font-monospace px-2 py-1 mb-1 d-inline-block">${asset.asset_code}</span>
                        <h6 class="fw-bold mb-1 text-dark">${asset.name}</h6>
                        <small class="text-muted d-block mb-1"><strong>Kategori:</strong> ${asset.category || '-'}</small>
                        <small class="text-muted d-block mb-2"><strong>Lokasyon:</strong> ${asset.location || '-'}</small>
                        <a href="/facility/assets/${asset.id}" class="btn btn-sm btn-success text-white w-100 rounded-pill font-semibold">
                            <i class="ri-eye-line me-1"></i>Varlık Detayı
                        </a>
                    </div>
                `;

                marker.bindPopup(popupContent);
            }
        });
    });
</script>
@endpush
