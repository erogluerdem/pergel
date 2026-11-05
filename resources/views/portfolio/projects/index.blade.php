@extends('layouts.master')

@section('title', 'Tüm Projeler')

@push('styles')
<style>
/* Portfolio Features Carousel */
.portfolio-features-carousel {
    position: relative;
    height: 50px;
    overflow: hidden;
}

.portfolio-features-carousel .features-carousel-container {
    position: relative;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.portfolio-feature {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 500;
    color: #495057;
    opacity: 0;
    transform: translateY(8px) scale(0.98);
    transition: opacity 450ms ease, transform 450ms cubic-bezier(0.2, 0, 0, 1);
    pointer-events: none;
    z-index: 1;
    white-space: nowrap;
}

.portfolio-feature.show {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
    z-index: 2;
}

.portfolio-feature.exit {
    opacity: 0;
    transform: translateY(-8px) scale(0.98);
    z-index: 1;
    pointer-events: none;
}

.portfolio-feature i {
    font-size: 20px;
}

.text-purple {
    color: #6f42c1 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .portfolio-features-carousel {
        height: 45px;
    }

    .portfolio-features-carousel .features-carousel-container {
        height: 45px;
    }

    .portfolio-feature {
        font-size: 14px;
    }

    .portfolio-feature i {
        font-size: 18px;
    }
}
</style>
@endpush

@section('content')
@php
    // Aggregates and helpers
    $totalProjects = $projects->count();
    $totalPortfolioValue = $projects->sum('total_budget') ?? 0;
    $totalSpent = $projects->sum('budget_spent') ?? 0;
    $profitabilityPercent = $totalPortfolioValue > 0 ? round((($totalPortfolioValue - $totalSpent) / $totalPortfolioValue) * 100, 1) : 0;
    $riskCount = $projects->filter(function($p){
        $h = strtolower($p->health ?? '');
        return in_array($h, ['riskli','kritik','kritik']);
    })->count();

    function format_budget_short($n){
        if($n >= 1000000) return round($n/1000000,1) . 'M';
        if($n >= 1000) return round($n/1000,0) . 'K';
        return number_format($n,0,'',',');
    }

    // Prepare markers for map (filter out missing coords)
    $markers = $projects->map(function($p){
        $loc = $p->location ?? null;
        $lat = null; $lng = null;
        if(is_array($loc)){
            $lat = $loc['lat'] ?? null; $lng = $loc['lng'] ?? null;
        } elseif(is_object($loc)){
            $lat = $loc->lat ?? null; $lng = $loc->lng ?? null;
        }
        return [
            'id' => $p->id ?? null,
            'name' => $p->name ?? '',
            'code' => $p->code ?? '',
            'lat' => $lat,
            'lng' => $lng,
            'status' => $p->status ?? '',
        ];
    })->filter(function($m){
        return $m['lat'] !== null && $m['lng'] !== null;
    })->values();
@endphp

<!-- Top Panel: Overview -->
<div class="row">
    <div class="col-lg-7">
        <!-- Portfolio Özellikleri Carousel -->
        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="portfolio-features-carousel">
                    <div class="features-carousel-container">
                        <div class="portfolio-feature show">
                            <i class="mdi mdi-chart-line text-primary me-2"></i>
                            <span><strong>{{ $totalProjects }}</strong> Aktif Proje</span>
                        </div>
                        <div class="portfolio-feature">
                            <i class="mdi mdi-currency-usd text-success me-2"></i>
                            <span><strong>{{ format_budget_short($totalPortfolioValue) }}</strong> Toplam Değer</span>
                        </div>
                        <div class="portfolio-feature">
                            <i class="mdi mdi-trending-up text-info me-2"></i>
                            <span><strong>%{{ $profitabilityPercent }}</strong> Kârlılık</span>
                        </div>
                        <div class="portfolio-feature">
                            <i class="mdi mdi-shield-check text-warning me-2"></i>
                            <span><strong>{{ $projects->count() - $riskCount }}</strong> Güvenli Proje</span>
                        </div>
                        <div class="portfolio-feature">
                            <i class="mdi mdi-account-group text-purple me-2"></i>
                            <span>Uzman Ekipler</span>
                        </div>
                        <div class="portfolio-feature">
                            <i class="mdi mdi-clock-check text-success me-2"></i>
                            <span>Zamanında Teslimat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Proje Konumları</h5>
                <small class="text-muted">Harita üzerinde projelerin konumları</small>
            </div>
            <div class="card-body">
                <div id="project-map" style="height: 350px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="row gy-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Toplam Proje Sayısı</small>
                            <h3 class="mb-0">{{ $totalProjects }}</h3>
                        </div>
                        <div><i class="fas fa-project-diagram fa-2x text-primary"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Toplam Portföy Değeri</small>
                            <h4 class="mb-0">{{ format_budget_short($totalPortfolioValue) }} TL</h4>
                        </div>
                        <div><i class="fas fa-wallet fa-2x text-success"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Genel Kârlılık Durumu</small>
                            @php $profitClass = $profitabilityPercent >= 0 ? 'text-success' : 'text-danger'; @endphp
                            <h4 class="mb-0 {{ $profitClass }}">{{ $profitabilityPercent }}%</h4>
                        </div>
                        <div><i class="fas fa-chart-line fa-2x {{ $profitClass }}"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted">Riskli Proje Sayısı</small>
                            <h3 class="mb-0 text-warning">{{ $riskCount }}</h3>
                        </div>
                        <div><i class="fas fa-exclamation-triangle fa-2x text-warning"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Projects list -->
<div class="card mt-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0">Proje Listesi</h5>
            <small class="text-muted">Tüm projeleri görüntüleyin, filtreleyin ve yönetin</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ url('projects/create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i> Yeni Proje Ekle</a>
            <button class="btn btn-secondary" id="export-btn"><i class="fas fa-download me-1"></i> Dışa Aktar</button>
        </div>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="search" name="q" class="form-control" placeholder="Proje adı veya kodu ara..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tüm Durumlar</option>
                    <option value="Planlama" {{ request('status') == 'Planlama' ? 'selected' : '' }}>Planlama</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tamamlandı" {{ request('status') == 'Tamamlandı' ? 'selected' : '' }}>Tamamlandı</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="manager" class="form-select">
                    <option value="">Tüm Müdürler</option>
                    @foreach($projects->pluck('manager')->filter()->unique('name') as $mgr)
                        @if(is_object($mgr))
                            <option value="{{ $mgr->name }}" {{ request('manager') == $mgr->name ? 'selected' : '' }}>{{ $mgr->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-grid">
                <button class="btn btn-primary">Filtrele</button>
            </div>
        </form>

        <!-- Projects Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Proje Adı</th>
                        <th>Proje Müdürü</th>
                        <th>Durum</th>
                        <th>Proje Sağlığı</th>
                        <th>İlerleme (%)</th>
                        <th>Bütçe Durumu</th>
                        <th>Zaman Çizelgesi</th>
                        <th class="text-end">Aksiyonlar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        @php
                            $status = strtolower($project->status ?? '');
                            $badge = 'bg-secondary';
                            if(str_contains($status,'aktif')) $badge = 'bg-primary';
                            if(str_contains($status,'tamam')) $badge = 'bg-success';
                            if(str_contains($status,'plan')) $badge = 'bg-info';

                            $health = strtolower($project->health ?? '');
                            $healthClass = 'text-muted';
                            if($health == 'iyi') $healthClass = 'text-success';
                            if($health == 'riskli') $healthClass = 'text-warning';
                            if($health == 'kritik' || $health == 'kritik') $healthClass = 'text-danger';

                            $progress = intval($project->progress_percentage ?? 0);
                            $spent = $project->budget_spent ?? 0;
                            $total = $project->total_budget ?? 0;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('projects.show', $project) }}" class="fw-semibold">{{ $project->name }}</a>
                                <div class="text-muted small">{{ $project->code ?? '' }}</div>
                            </td>
                            <td>{{ optional($project->manager)->name ?? '-' }}</td>
                            <td><span class="badge {{ $badge }}">{{ $project->status }}</span></td>
                            <td><i class="fas fa-circle {{ $healthClass }} me-1" style="font-size:.7rem"></i> {{ ucfirst($project->health ?? '-') }}</td>
                            <td style="width:200px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height:8px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div style="width:50px; text-align:right;"><strong>{{ $progress }}%</strong></div>
                                </div>
                            </td>
                            <td>
                                @if($total > 0)
                                    {{ format_budget_short($spent) }} / {{ format_budget_short($total) }} TL
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $t = strtolower($project->timeline_status ?? '');
                                    $timeClass = 'text-muted';
                                    if(str_contains($t,'gecik')) $timeClass = 'text-danger';
                                    if(str_contains($t,'zaman') || str_contains($t,'on time')) $timeClass = 'text-success';
                                @endphp
                                <span class="{{ $timeClass }}">{{ $project->timeline_status ?? '-' }}</span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="actions-{{ $project->id }}" data-bs-toggle="dropdown" aria-expanded="false">İşlemler</button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="actions-{{ $project->id }}">
                                        <li><a class="dropdown-item" href="{{ route('projects.show', $project) }}"><i class="fas fa-eye me-2"></i> Detaylar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('projects.edit', $project) }}"><i class="fas fa-edit me-2"></i> Düzenle</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="post" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Projeyi arşivlemek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit"><i class="fas fa-archive me-2"></i> Arşivle</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Kayıtlı proje bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($projects, 'links'))
            <div class="d-flex justify-content-end mt-3">{{ $projects->links() }}</div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<!-- Leaflet CSS/JS recommended (include in layout or here) -->
<!--
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
-->

<script>
    // Prepare markers data from PHP
    const PROJECT_MARKERS = @json($markers);

    // Initialize the map: place this code inside your page scripts after Leaflet is loaded
    // Example initialization (uncomment and adapt when Leaflet is available):
    /*
    document.addEventListener('DOMContentLoaded', function(){
        const map = L.map('project-map').setView([39.0, 35.0], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const markers = [];
        PROJECT_MARKERS.forEach(m => {
            const marker = L.marker([m.lat, m.lng]).addTo(map);
            marker.bindPopup(`<strong>${m.name}</strong><br/>${m.code}`);
            markers.push(marker);
        });

        if(markers.length){
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
    });
    */

    // Export button placeholder
    document.getElementById('export-btn')?.addEventListener('click', function(){
        // Implement export (CSV/Excel) - you can call an endpoint or client-side generate CSV from table rows
        alert('Dışa aktarma işlevi henüz uygulanmadı.');
    });

    // Portfolio Features Carousel
    setTimeout(() => {
        const features = document.querySelectorAll('.portfolio-features-carousel .portfolio-feature');
        const container = document.querySelector('.portfolio-features-carousel .features-carousel-container');

        console.log('Portfolio features carousel: Found features:', features.length);

        if (!features.length || !container) return;

        let currentIndex = 0;
        const slideDelay = 2500; // 2.5 seconds between slides

        // Ensure first feature is visible
        features.forEach((f, i) => {
            f.classList.remove('show', 'exit');
            if (i === 0) f.classList.add('show');
        });

        function rotateFeature() {
            const prev = features[currentIndex];
            const nextIndex = (currentIndex + 1) % features.length;
            const next = features[nextIndex];

            console.log('Rotating portfolio feature from', currentIndex, 'to', nextIndex);

            if (prev && prev.classList) {
                prev.classList.remove('show');
                prev.classList.add('exit');
                setTimeout(() => {
                    try { prev.classList.remove('exit'); } catch (e) {}
                }, 500);
            }

            if (next && next.classList) {
                next.classList.add('show');
            }

            currentIndex = nextIndex;
        }

        // Start rotation
        console.log('Starting portfolio features carousel rotation');
        setInterval(rotateFeature, slideDelay);

    }, 700);
</script>
@endpush
