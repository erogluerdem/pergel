@extends('layouts.master')

@section('title', 'İş Makinesi & Ekipman Parkı Portalı')

@section('topbar-title', 'İş Makinesi & Ekipman Parkı')

@push('styles')
<!-- Leaflet CSS for Interactive Heavy Equipment Fleet Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

<style>
    /* Hero Header Banner */
    .equipment-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .equipment-hero-banner,
    :root:not([data-bs-theme="dark"]) .equipment-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #ea580c 50%, #c2410c 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .equipment-hero-banner {
        background: linear-gradient(135deg, rgba(234, 88, 12, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .equipment-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .equipment-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .equipment-kpi-card,
    :root:not([data-bs-theme="dark"]) .equipment-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .equipment-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .equipment-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .equipment-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .equipment-glass-card,
    :root:not([data-bs-theme="dark"]) .equipment-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .equipment-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    #equipment-fleet-map {
        height: 400px;
        width: 100%;
        border-radius: 16px;
        z-index: 1;
    }
</style>
@endpush

@section('content')

@php
    $totalCount = $equipment->total() > 0 ? $equipment->total() : count($equipment);
    $activeCount = $equipment->where('status', 'Çalışıyor')->count();
    $maintenanceCount = $equipment->where('status', 'Bakımda')->count();
    $idleCount = $equipment->where('status', 'Boşta')->count();
@endphp

<!-- Hero Header Banner -->
<div class="equipment-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-truck-line me-1"></i>Heavy Equipment & Fleet Management
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">İş Makinesi & Şantiye Ekipman Parkı</h1>
            <p class="text-white-50 mb-0 fs-6">Ekskavatör, loder, kule vinç, kamyon makine parkı canlı GPS takibi, yakıt ve çalışma saatleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('resources.equipment.create') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm" style="background:#ea580c; color:#fff !important;">
                <i class="ri-add-circle-line me-1"></i>Yeni Ekipman Kaydı
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="equipment-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Makine Parkı</span>
                    <h2 class="fw-extrabold mb-0 text-orange" style="color:#ea580c;">{{ $totalCount }} Araç/Makine</h2>
                </div>
                <div class="equipment-stat-icon bg-warning bg-opacity-10 text-warning" style="color:#ea580c !important;">
                    <i class="ri-truck-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="equipment-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Saha Aktif Çalışan</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $activeCount }} Makine</h2>
                </div>
                <div class="equipment-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="equipment-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Bakımda / Arızalı</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $maintenanceCount }} Makine</h2>
                </div>
                <div class="equipment-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-tools-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="equipment-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Boşta / Yedek Makine</span>
                    <h2 class="fw-extrabold mb-0 text-info">{{ $idleCount }} Makine</h2>
                </div>
                <div class="equipment-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-pause-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Fleet GPS Location Map Section -->
<div class="equipment-glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                <i class="ri-map-pin-line text-orange me-2" style="color:#ea580c;"></i>Canlı İş Makinesi GPS & Filo Haritası
            </h5>
            <small class="text-muted">Şantiye sahasında görev yapan ağır iş makinelerinin anlık GPS lokasyon haritası.</small>
        </div>
        <span class="badge bg-warning bg-opacity-10 border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold text-dark">
            GPS Aktif
        </span>
    </div>

    <!-- Map Canvas Container -->
    <div id="equipment-fleet-map" class="border shadow-sm"></div>
</div>

<!-- Main Equipment Table Container -->
<div class="equipment-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-list-3-line text-orange me-2" style="color:#ea580c;"></i>Ekipman & Araç Parkı Listesi
        </h5>
        <span class="badge bg-warning bg-opacity-10 border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold text-dark">
            {{ count($equipment) }} Makine Kaydı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Plaka / Seri No</th>
                    <th>Ekipman Adı & Marka/Model</th>
                    <th>Tip</th>
                    <th>Operatör Sorumlusu</th>
                    <th>Çalışma Saati (ÇS)</th>
                    <th>Yakıt (%)</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment as $eq)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $eq->plate_number ?: ($eq->serial_number ?: 'PLK-NO') }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $eq->name }}</strong>
                            <small class="text-muted">{{ $eq->brand ?? '-' }} {{ $eq->model ? '('.$eq->model.')' : '' }} — {{ $eq->ownership === 'Kiralık' ? 'Kiralık' : 'Özmal' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 small">{{ $eq->type }}</span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $eq->operator ?? 'Atanmadı' }}</strong>
                            <small class="text-muted">{{ $eq->currentProject->name ?? 'Genel Park' }}</small>
                        </td>
                        <td>
                            <span class="font-monospace fw-bold text-dark">{{ number_format($eq->working_hours, 0) }} Sa</span>
                        </td>
                        <td style="min-width: 110px;">
                            <div class="d-flex align-items-center gap-1">
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar {{ $eq->fuel_level <= 35 ? 'bg-danger' : 'bg-warning' }}" style="width: {{ $eq->fuel_level ?? 100 }}%;"></div>
                                </div>
                                <span class="small font-monospace fw-bold">%{{ $eq->fuel_level ?? 100 }}</span>
                            </div>
                        </td>
                        <td>
                            @if($eq->status === 'Çalışıyor')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Çalışıyor</span>
                            @elseif($eq->status === 'Bakımda')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Bakımda</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Boşta</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('resources.equipment.show', $eq->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 text-dark">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('resources.equipment.edit', $eq->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-truck-line fs-1 d-block mb-2 text-warning"></i>
                            Henüz kayıtlı iş makinesi bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<!-- Leaflet JS for Interactive Heavy Equipment Fleet Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const equipmentsData = @json($equipment);

        let defaultLat = 41.0085;
        let defaultLng = 28.9782;

        const map = L.map('equipment-fleet-map').setView([defaultLat, defaultLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        equipmentsData.data ? equipmentsData.data.forEach(addMarker) : equipmentsData.forEach(addMarker);

        function addMarker(eq) {
            if (eq.latitude && eq.longitude) {
                const marker = L.marker([parseFloat(eq.latitude), parseFloat(eq.longitude)]).addTo(map);

                const popupContent = `
                    <div style="min-width: 200px;">
                        <span class="badge bg-warning text-dark font-monospace px-2 py-1 mb-1 d-inline-block">${eq.plate_number || eq.serial_number || 'PLK'}</span>
                        <h6 class="fw-bold mb-1 text-dark">${eq.name}</h6>
                        <small class="text-muted d-block mb-1"><strong>Operatör:</strong> ${eq.operator || '-'}</small>
                        <small class="text-muted d-block mb-2"><strong>Çalışma Saati:</strong> ${eq.working_hours || 0} Sa</small>
                        <a href="/resources/equipment/${eq.id}" class="btn btn-sm btn-warning text-dark w-100 rounded-pill font-semibold">
                            <i class="ri-eye-line me-1"></i>Ekipman Detayı
                        </a>
                    </div>
                `;

                marker.bindPopup(popupContent);
            }
        }
    });
</script>
@endpush
