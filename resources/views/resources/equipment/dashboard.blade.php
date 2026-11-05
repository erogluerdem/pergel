@extends('layouts.master')

@section('title', 'Ekipman Parkı - Kontrol Paneli')

@section('topbar-title', 'Ekipman Parkı')

@section('content')

<!-- KPI Kartları -->
<div class="row">
    <style>
    /* KPI avatar tweaks: slightly larger, centered icon, subtle shadow */
    .kpi-avatar {
        width: 56px;
        height: 56px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .kpi-avatar .mdi {
        font-size: 26px;
    }
    </style>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Toplam Ekipman</p>
                        <h4 class="mb-0">{{ $totalEquipment }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center kpi-avatar">
                            <i class="mdi mdi-truck text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Kullanım Oranı</p>
                        <h4 class="mb-0">{{ $usageRate }}%</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center kpi-avatar">
                            <i class="mdi mdi-chart-pie text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Bakımdaki Ekipman</p>
                        <h4 class="mb-0">{{ $maintenanceEquipment }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center kpi-avatar">
                            <i class="mdi mdi-wrench text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Yaklaşan Bakımlar</p>
                        <h4 class="mb-0">{{ $upcomingMaintenance }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-info d-flex align-items-center justify-content-center kpi-avatar">
                            <i class="mdi mdi-calendar-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Harita ve Hızlı Erişim -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Canlı Ekipman Haritası</h4>
            </div>
            <div class="card-body">
                <div id="equipment-map" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Hızlı Erişim</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('resources.equipment.index') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-view-list me-1"></i> Ekipman Listesi
                    </a>
                    <a href="{{ route('resources.equipment.create') }}" class="btn btn-outline-success">
                        <i class="mdi mdi-plus me-1"></i> Yeni Ekipman Ekle
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="showUpcomingMaintenance()">
                        <i class="mdi mdi-calendar-clock me-1"></i> Yaklaşan Bakımlar
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="showMaintenanceReport()">
                        <i class="mdi mdi-file-chart me-1"></i> Bakım Raporu
                    </button>
                </div>
            </div>
        </div>

        <!-- Durum Özeti -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Ekipman Durum Özeti</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <td><span class="badge bg-success">Çalışıyor</span></td>
                                <td class="text-end">{{ $activeEquipment }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-secondary">Boşta</span></td>
                                <td class="text-end">{{ \App\Models\Equipment::where('status', 'Boşta')->count() }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning">Bakımda</span></td>
                                <td class="text-end">{{ $maintenanceEquipment }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Arızalı</span></td>
                                <td class="text-end">{{ \App\Models\Equipment::where('status', 'Arızalı')->count() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Son Aktiviteler -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Son Bakım Aktiviteleri</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap">
                        <thead>
                            <tr>
                                <th>Ekipman</th>
                                <th>Bakım Tipi</th>
                                <th>Tarih</th>
                                <th>Maliyet</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\EquipmentMaintenance::with('equipment')->latest()->limit(5)->get() as $maintenance)
                            <tr>
                                <td>{{ $maintenance->equipment->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $maintenance->type === 'Arıza' ? 'danger' : 'info' }}">
                                        {{ $maintenance->type }}
                                    </span>
                                </td>
                                <td>{{ $maintenance->maintenance_date->format('d.m.Y') }}</td>
                                <td>{{ $maintenance->cost ? number_format($maintenance->cost, 2) . ' ₺' : '-' }}</td>
                                <td><span class="badge bg-success">Tamamlandı</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Henüz bakım kaydı bulunmuyor.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Harita başlatma
    var map = L.map('equipment-map').setView([39.9334, 32.8597], 6); // Türkiye merkez

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Ekipman konumlarını ekle
    @foreach($activeEquipments as $equipment)
    L.marker([{{ $equipment->latitude }}, {{ $equipment->longitude }}])
        .addTo(map)
        .bindPopup(`
            <b>{{ $equipment->name }}</b><br>
            Tip: {{ $equipment->type }}<br>
            Durum: <span class="badge bg-success">Çalışıyor</span>
        `);
    @endforeach

    // Eğer hiç ekipman yoksa varsayılan görünüm
    if ({{ $activeEquipments->count() }} === 0) {
        map.setView([39.9334, 32.8597], 6);
    } else {
        // Tüm marker'ları kapsayacak şekilde zoom
        var group = new L.featureGroup(@foreach($activeEquipments as $equipment)L.marker([{{ $equipment->latitude }}, {{ $equipment->longitude }}]){{ !$loop->last ? ',' : '' }}@endforeach);
        map.fitBounds(group.getBounds().pad(0.1));
    }
});

function showUpcomingMaintenance() {
    // Yaklaşan bakımları gösteren modal veya sayfa yönlendirmesi
    window.location.href = '{{ route("resources.equipment.index") }}?filter=upcoming_maintenance';
}

function showMaintenanceReport() {
    // Bakım raporu - ileride eklenecek
    alert('Bakım raporu özelliği yakında eklenecek.');
}
</script>
@endsection
