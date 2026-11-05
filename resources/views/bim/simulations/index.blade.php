@extends('layouts.master')

@section('title', 'BIM 4D/5D Simülasyon Merkezi')
@section('topbar-title', 'BIM & Dijital İkiz - Simülasyon & Analiz')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & SAPPHIRE/AMBER GLASSMORPHIC THEME */
    .sim-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sim-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: simGlow 4s linear infinite;
    }

    @keyframes simGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sim-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .sim-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .sim-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .sim-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Simulation Card Items */
    .sim-item-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .sim-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
    }

    .sim-table-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .sim-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .sim-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .sim-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="sim-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('bim.models.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>3D Modellere Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill sim-badge-pill">
                        <i class="ri-film-fill text-warning fs-5"></i> 4D ZAMAN & 5D MALİYET SİMÜLASYONLARI
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">BIM 4D/5D Simülasyon & Analiz Merkezi</h2>
                <p class="mb-0 fs-6" style="color: #fef3c7; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye imalat süreçlerini zaman boyutu (4D İş Programı) ve maliyet boyutu (5D Nakit Akışı & Metraj) ile 3D dijital ikiz üzerinde canlı simüle edin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end">
                    <a href="{{ route('bim.simulations.create') }}?type=4D" class="btn btn-emerald text-white rounded-pill py-2.5 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center justify-content-center gap-2" style="background: #047857; border: none;">
                        <i class="ri-time-fill fs-4"></i> + 4D Zaman Simülasyonu Başlat
                    </a>
                    <a href="{{ route('bim.simulations.create') }}?type=5D" class="btn btn-warning text-dark rounded-pill py-2.5 px-4 fw-extrabold shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="ri-coins-fill fs-4"></i> + 5D Maliyet Simülasyonu Başlat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card sim-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM SİMÜLASYON</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $simulations->count() }} <small class="fs-6 text-muted">Model</small></h2>
                    </div>
                    <div class="sim-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-film-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-play-circle-line me-1"></i>Analiz Senaryoları</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Aktif Simülasyon</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sim-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">4D ZAMAN SİMÜLASYONLARI</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $simulations->where('type', '4D')->count() }} <small class="fs-6 text-muted">Zaman</small></h2>
                    </div>
                    <div class="sim-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-time-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-calendar-event-line me-1"></i>İş Programı & Gantt</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Time-Lapse</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sim-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">5D MALİYET SİMÜLASYONLARI</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $simulations->where('type', '5D')->count() }} <small class="fs-6 text-muted">Maliyet</small></h2>
                    </div>
                    <div class="sim-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-money-dollar-circle-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-coins-line me-1"></i>Bütçe & Nakit Akışı</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">5D Cost</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sim-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TAMAMLANAN ANALİZLER</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace">{{ $simulations->where('status', 'done')->count() }} <small class="fs-6 text-muted">Tamamlandı</small></h2>
                    </div>
                    <div class="sim-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-check-double-line me-1"></i>Raporlanabilir</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Hazır Metrik</span>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW TOGGLE & ACTIONS BAR -->
    <div class="card border rounded-4 p-3.5 mb-4" style="border-color: #cbd5e1 !important;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-extrabold text-dark small me-2"><i class="ri-grid-fill text-warning me-1"></i>Görünüm Modu:</span>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-dark active rounded-start-pill px-3 fw-bold" id="btnCardView" onclick="switchView('card')">
                        <i class="ri-grid-line me-1"></i>Kart Görünümü
                    </button>
                    <button type="button" class="btn btn-outline-dark rounded-end-pill px-3 fw-bold" id="btnTableView" onclick="switchView('table')">
                        <i class="ri-table-line me-1"></i>Tablo Görünümü
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('bim.simulations.create') }}" class="btn btn-sm btn-primary rounded-pill px-3.5 fw-extrabold shadow-sm" style="background: #2563eb; border: none;">
                    <i class="ri-add-circle-line me-1"></i> Yeni Simülasyon Oluştur
                </a>
            </div>
        </div>
    </div>

    <!-- CARD VIEW CONTAINER -->
    <div id="cardViewContainer">
        <div class="row g-4 mb-4">
            @forelse($simulations as $s)
                <div class="col-xl-4 col-md-6">
                    <div class="sim-item-card h-100">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                @if($s->type === '4D')
                                    <span class="badge bg-emerald text-white rounded-pill px-3 py-1.5 font-monospace fw-extrabold" style="background: #047857;">
                                        <i class="ri-time-line me-1"></i>4D Zaman
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-extrabold">
                                        <i class="ri-money-dollar-circle-line me-1"></i>5D Maliyet
                                    </span>
                                @endif

                                @php
                                    $statusMap = [
                                        'done' => ['class'=> 'success', 'label'=>'Tamamlandı', 'icon'=>'checkbox-circle-fill'],
                                        'running' => ['class'=> 'primary', 'label'=>'Çalışıyor', 'icon'=>'loader-4-line'],
                                        'pending' => ['class'=> 'warning', 'label'=>'Beklemede', 'icon'=>'time-line'],
                                        'failed' => ['class'=> 'danger', 'label'=>'Hata', 'icon'=>'error-warning-line'],
                                    ];
                                    $st = $statusMap[$s->status] ?? ['class'=>'secondary','label'=> ucfirst($s->status), 'icon'=>'question-line'];
                                @endphp

                                <span class="badge bg-{{ $st['class'] }} bg-opacity-10 text-{{ $st['class'] }} border border-{{ $st['class'] }} border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                    <i class="ri-{{ $st['icon'] }} me-1"></i>{{ $st['label'] }}
                                </span>
                            </div>

                            <h5 class="fw-extrabold text-dark mb-2 text-truncate" title="{{ $s->name }}">{{ $s->name }}</h5>
                            
                            <div class="mb-2 text-secondary small fw-semibold">
                                <i class="ri-building-2-line text-primary me-1"></i>Proje: <strong>{{ $s->project ? $s->project->name : 'Genel Proje Portföyü' }}</strong>
                            </div>

                            <div class="mb-3 text-muted small font-monospace">
                                <i class="ri-calendar-line me-1"></i>Oluşturulma: {{ $s->created_at ? $s->created_at->format('d.m.Y H:i') : '-' }}
                            </div>

                            @if(isset($s->config['progress']))
                                <div class="p-3 bg-light rounded-3 border mb-3" style="border-color: #cbd5e1 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-1 small fw-extrabold">
                                        <span class="text-dark">Simülasyon İlerlemesi:</span>
                                        <span class="text-primary font-monospace">%{{ $s->config['progress'] }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 10px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $s->config['progress'] }}%;"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                <a href="{{ route('bim.simulations.play', ['id'=>$s->id]) }}" class="btn btn-sm btn-primary text-white rounded-pill px-4 py-2 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1.5" style="background: #2563eb; border: none;">
                                    <i class="ri-play-fill fs-5"></i> Simülasyonu Oynat
                                </a>
                                <a href="{{ route('bim.simulations.show', ['id'=>$s->id]) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="card p-5 rounded-4 border border-2 border-dashed" style="border-color: #cbd5e1 !important;">
                        <i class="ri-film-line display-2 text-secondary mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark">Henüz BIM Simülasyonu Bulunmuyor</h5>
                        <p class="text-muted small mb-4">İlk 4D (Zaman) veya 5D (Maliyet) simülasyon senaryonuzu başlatmak için aşağıdaki butonları kullanabilirsiniz.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('bim.simulations.create') }}?type=4D" class="btn btn-emerald text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                                <i class="ri-time-line me-1"></i> 4D Simülasyon Başlat
                            </a>
                            <a href="{{ route('bim.simulations.create') }}?type=5D" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                                <i class="ri-coins-line me-1"></i> 5D Simülasyon Başlat
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- TABLE VIEW CONTAINER (HIDDEN BY DEFAULT) -->
    <div id="tableViewContainer" style="display: none;">
        <div class="sim-table-card">
            <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-table-line text-warning fs-5"></i> Simülasyon Listesi Tablosu
                </h5>
                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">{{ $simulations->count() }} Kayıt</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table sim-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">SİMÜLASYON ADI</th>
                                <th>TÜR</th>
                                <th>İLGİLİ PROJE</th>
                                <th>DURUM</th>
                                <th>TARİH</th>
                                <th class="pe-4 text-end">İŞLEMLER</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($simulations as $s)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <strong class="text-dark fs-6 d-block mb-0.5">{{ $s->name }}</strong>
                                    </td>
                                    <td>
                                        @if($s->type === '4D')
                                            <span class="badge bg-emerald text-white font-monospace px-3 py-1 rounded-pill fw-bold" style="background: #047857;">4D Zaman</span>
                                        @else
                                            <span class="badge bg-dark font-monospace text-warning px-3 py-1 rounded-pill fw-bold">5D Maliyet</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark"><i class="ri-building-2-line text-primary me-1"></i>{{ $s->project ? $s->project->name : 'Genel Proje' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                            {{ $s->status }}
                                        </span>
                                    </td>
                                    <td class="font-monospace fw-bold text-muted small">
                                        {{ $s->created_at ? $s->created_at->format('d.m.Y H:i') : '-' }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('bim.simulations.play', ['id'=>$s->id]) }}" class="btn btn-sm btn-primary text-white rounded-pill px-3.5 fw-extrabold shadow-sm" style="background: #2563eb; border: none;">
                                            <i class="ri-play-fill me-1"></i> Oynat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ri-film-line display-4 text-secondary mb-2 d-block"></i>
                                        <h6 class="fw-bold text-dark">Simülasyon Bulunmuyor</h6>
                                    </td>
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

@push('scripts')
<script>
function switchView(mode) {
    const cardContainer = document.getElementById('cardViewContainer');
    const tableContainer = document.getElementById('tableViewContainer');
    const btnCard = document.getElementById('btnCardView');
    const btnTable = document.getElementById('btnTableView');

    if (mode === 'table') {
        cardContainer.style.display = 'none';
        tableContainer.style.display = 'block';
        btnCard.classList.remove('active');
        btnTable.classList.add('active');
    } else {
        cardContainer.style.display = 'block';
        tableContainer.style.display = 'none';
        btnCard.classList.add('active');
        btnTable.classList.remove('active');
    }
}
</script>
@endpush