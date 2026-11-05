@extends('layouts.master')

@section('title', 'Türkiye Canlı Şantiye & GIS Haritası')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .map-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: mapGlow 4s linear infinite;
    }

    @keyframes mapGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .map-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-map {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-map:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .project-map-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        transition: all 0.25s ease !important;
        cursor: pointer;
    }
    .project-map-card:hover {
        transform: translateX(4px) !important;
        border-color: #d97706 !important;
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-leaflet-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border-radius: 50%;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
        border: 3px solid #ffffff;
        animation: pulsePin 2s infinite;
    }
    @keyframes pulsePin {
        0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7); }
        70% { box-shadow: 0 0 0 12px rgba(217, 119, 6, 0); }
        100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="map-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill map-badge-pill">
                        <i class="ri-map-pin-2-fill text-warning fs-5"></i> GIS CANLI TÜRKİYE ŞANTİYE HARİTASI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Aktif Harita Pini: {{ count($mapData) }} Proje
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Türkiye Şantiye & Proje Haritası</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Portföyünüzdeki tüm şantiye ve gayrimenkul projelerinin canlı coğrafi konumları, il/ilçe dağılımları ve fiziki ilerleme durumlarını harita üzerinden inceleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('projects.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>Yeni Proje Ekle
                    </a>
                    <a href="{{ route('portfolio.all-projects') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-building-line fs-5"></i>Proje Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-map h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">HARİTADAKİ PROJELER</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ count($mapData) }} Şantiye</div>
                        <small class="text-primary fw-bold"><i class="ri-map-pin-line me-1"></i>Canlı Konum Takibi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-map-pin-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-map h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">DEVAM EDEN İNŞAATLAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ collect($mapData)->where('status', 'Devam Ediyor')->count() }} Proje</div>
                        <small class="text-success fw-bold"><i class="ri-pulse-line me-1"></i>Aktif İmalat Sahası</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-building-4-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-map h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">ORTALAMA İLERLEME</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">%{{ count($mapData) > 0 ? round(collect($mapData)->avg('progress'), 1) : 0 }}</div>
                        <small class="text-info fw-bold"><i class="ri-pie-chart-fill me-1"></i>Fiziki Tamamlanma</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-line-chart-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-map h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BÖLGESEL DAĞILIM</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">Türkiye Geneli</div>
                        <small class="text-warning fw-bold"><i class="ri-earth-fill me-1"></i>GIS Harita Koordinatı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-earth-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAP & SIDE DRAWER GRID -->
    <div class="row g-4 mb-4">
        <!-- Interactive Leaflet Map Column -->
        <div class="col-lg-8 col-xl-9">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-map-2-fill text-warning fs-5"></i>İnteraktif GIS Şantiye Konum Haritası
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold" onclick="switchMapTile('streets')">
                            <i class="ri-map-line me-1"></i>Sokak Görünümü
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold" onclick="switchMapTile('sat')">
                            <i class="ri-earth-line me-1"></i>Uydu Görünümü
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="project-leaflet-map" style="height: 640px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Project Quick Fly Drawer Column -->
        <div class="col-lg-4 col-xl-3">
            <div class="card border shadow-sm rounded-4 bg-white h-100 d-flex flex-column">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h6 class="fw-extrabold text-white mb-0 d-flex align-items-center justify-content-between">
                        <span><i class="ri-building-line text-warning me-2"></i>Projeler Cetveli</span>
                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ count($mapData) }}</span>
                    </h6>
                </div>
                <div class="card-body p-3 flex-grow-1" style="max-height: 600px; overflow-y: auto;">
                    @foreach($mapData as $p)
                        <div class="project-map-card p-3 mb-3 shadow-sm" onclick="flyToProject({{ $p['lat'] }}, {{ $p['lng'] }}, '{{ $p['name'] }}')">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <h6 class="fw-extrabold text-dark mb-0 text-truncate" style="max-width: 170px;" title="{{ $p['name'] }}">{{ $p['name'] }}</h6>
                                <span class="badge rounded-pill text-white fw-bold font-monospace" style="background-color: {{ $p['status_color'] }}; font-size: 10.5px;">
                                    {{ $p['status'] }}
                                </span>
                            </div>
                            <small class="text-muted fw-bold d-block mb-2"><i class="ri-map-pin-2-fill text-danger me-1"></i>{{ $p['location'] }}</small>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <small class="text-dark fw-extrabold" style="font-size: 11px;">İLERLEME</small>
                                <span class="fw-extrabold text-primary font-monospace" style="font-size: 12px;">%{{ $p['progress'] }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 7px; background: #cbd5e1;">
                                <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $p['progress'] }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let markers = {};
let streetTile, satTile;

document.addEventListener('DOMContentLoaded', function() {
    const mapData = @json($mapData);

    map = L.map('project-leaflet-map').setView([39.0000, 35.0000], 6);

    streetTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap | Pergel GIS Portal'
    });

    satTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: 'Esri World Imagery | Pergel GIS Portal'
    });

    streetTile.addTo(map);

    mapData.forEach(p => {
        const customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="custom-leaflet-pin" style="background-color: ${p.status_color}; width: 40px; height: 40px; font-size:18px;">
                    <i class="ri-building-4-fill"></i>
                   </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        const popupContent = `
            <div style="min-width: 220px; padding: 6px;">
                <span style="background: #0f172a; color: #fef08a; padding: 3px 9px; border-radius: 10px; font-size: 10.5px; font-weight:bold; font-family: monospace;">GIS MAP PIN</span>
                <h6 style="font-weight: 800; margin-top: 6px; margin-bottom: 4px; color:#0f172a; font-size: 14.5px;">${p.name}</h6>
                <small style="color: #64748b; display: block; margin-bottom: 8px; font-weight: 700;"><i class="ri-map-pin-fill text-danger"></i> ${p.location}</small>
                <div style="margin-bottom: 8px;">
                    <span style="background-color: ${p.status_color}; color: white; padding: 4px 11px; border-radius: 12px; font-size: 11px; font-weight:bold;">${p.status}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 11.5px; font-weight: bold; margin-bottom: 4px; color: #0f172a;">
                    <span>Fiziki İlerleme</span>
                    <span style="color: #d97706;">%${p.progress}</span>
                </div>
                <div style="background: #cbd5e1; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 12px;">
                    <div style="background: #d97706; width: ${p.progress}%; height: 100%;"></div>
                </div>
                <a href="${p.url}" class="btn btn-sm btn-warning text-dark w-100 fw-extrabold" style="font-size: 11.5px; padding: 7px; border-radius: 20px; background: #f59e0b; border: none;">Proje Detayına Git &rarr;</a>
            </div>
        `;

        const marker = L.marker([p.lat, p.lng], { icon: customIcon }).addTo(map).bindPopup(popupContent);
        markers[p.name] = marker;
    });
});

function switchMapTile(type) {
    if (type === 'sat') {
        map.removeLayer(streetTile);
        satTile.addTo(map);
    } else {
        map.removeLayer(satTile);
        streetTile.addTo(map);
    }
}

function flyToProject(lat, lng, name) {
    if (map) {
        map.flyTo([lat, lng], 13, { duration: 1.5 });
        if (markers[name]) {
            setTimeout(() => { markers[name].openPopup(); }, 1200);
        }
    }
}
</script>
@endpush
