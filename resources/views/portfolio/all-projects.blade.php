@extends('layouts.master')

@section('title', 'Tüm Projeler & Şantiye Portföyü')

@push('styles')
<style>
    .project-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .project-hero-banner h2, .project-hero-banner h1 {
        color: #ffffff !important;
        font-weight: 800 !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6) !important;
    }

    .project-hero-banner p {
        color: #f1f5f9 !important;
        font-weight: 500 !important;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5) !important;
    }

    .project-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: projectGlow 4s linear infinite;
    }

    @keyframes projectGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .project-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-project {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-project:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-filter-card-project {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        padding: 20px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
    }

    .custom-filter-input-project {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }
    .custom-filter-input-project:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .project-card-custom {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
        overflow: hidden;
    }
    .project-card-custom:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.12) !important;
        border-color: #d97706 !important;
    }

    .project-card-custom .card-img-wrapper {
        position: relative !important;
        height: 200px !important;
        overflow: hidden !important;
    }
    .project-card-custom .card-img-wrapper img {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
    }
    .project-card-custom .card-img-overlay-gradient {
        position: absolute !important;
        inset: 0 !important;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.3) 60%, transparent 100%) !important;
    }

    .custom-project-table thead,
    .custom-project-table thead tr,
    .custom-project-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-project-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="project-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill project-badge-pill">
                        <i class="ri-building-4-fill text-warning fs-5"></i> CANLI PORTFÖY VE SAHA YÖNETİMİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $projects->total() }} Kayıtlı Proje
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Şantiye & Gayrimenkul Projeleri</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Tüm konut, ticari ve altyapı projelerinizin fiziki ilerleme oranları, nakit akışı, bütçe uyumu ve saha günlüklerini tek bir ekrandan yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('projects.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Proje Oluştur
                    </a>
                    <a href="{{ route('projects.map') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-map-pin-2-fill text-warning fs-5"></i>GIS Harita
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($pergelQuota) && (isset($pergelLicense) ? $pergelLicense->tier() : ($pergelQuota->getUsageSummary()['tier'] ?? 'community')) === 'community')
        @php
            $quotaSummary = $pergelQuota->getUsageSummary();
            $projectQuota = $quotaSummary['projects'];
        @endphp
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-left: 5px solid #f59e0b !important;">
            <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="ri-pie-chart-2-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary text-white font-monospace text-uppercase" style="font-size: 0.65rem;">Topluluk Sürümü</span>
                            <h6 class="fw-bold mb-0 text-dark">Proje Kullanım Kotası</h6>
                        </div>
                        <p class="text-muted small mb-0 mt-0.5">
                            Ücretsiz sürümde aktif <strong>{{ $projectQuota['current'] }} / {{ $projectQuota['limit'] }}</strong> proje kullanılmaktadır. 
                            @if(!$projectQuota['can_create'])
                                <span class="text-danger fw-bold"><i class="ri-error-warning-fill"></i> Proje kotanız doldu!</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 flex-grow-1" style="max-width: 320px;">
                    <div class="progress flex-grow-1 rounded-pill" style="height: 10px; background-color: #cbd5e1;">
                        <div class="progress-bar {{ $projectQuota['percent'] >= 100 ? 'bg-danger' : ($projectQuota['percent'] >= 50 ? 'bg-warning' : 'bg-success') }}" 
                             role="progressbar" 
                             style="width: {{ min(100, $projectQuota['percent']) }}%;" 
                             aria-valuenow="{{ $projectQuota['current'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $projectQuota['limit'] }}"></div>
                    </div>
                    <span class="fw-bold font-monospace small {{ $projectQuota['percent'] >= 100 ? 'text-danger' : 'text-dark' }}">
                        %{{ $projectQuota['percent'] }}
                    </span>
                </div>

                <div>
                    <a href="{{ route('admin.license.upgrade', ['reason' => 'project_quota']) }}" class="btn btn-sm btn-warning text-dark rounded-pill fw-bold px-3 py-1.5 shadow-sm d-inline-flex align-items-center gap-1">
                        <i class="ri-vip-crown-fill"></i> Sınırsıza Yükselt
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-project h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">AKTİF PROJE SAYISI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $activeProjects }} Proje</div>
                        <small class="text-primary fw-bold"><i class="ri-check-double-line me-1"></i>{{ $summary['completed_projects'] ?? 0 }} Tamamlanan Proje</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-building-4-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-project h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM PORTFÖY BÜTÇESİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($totalValue, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-arrow-up-circle-fill me-1"></i>Taahhüt & Yatırım Değeri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-project h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAKVİM UYUM ORANI</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">%{{ $onTimeRate }}</div>
                        <small class="text-info fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>İş Programına Uygun</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-time-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="progress rounded-pill" style="height: 6px; background: #cbd5e1;">
                    <div class="progress-bar bg-info rounded-pill" role="progressbar" style="width: {{ $onTimeRate }}%;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-project h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BÜTÇE GERÇEKLEŞME ORANI</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">%{{ $budgetComplianceRate }}</div>
                        <small class="text-warning fw-bold"><i class="ri-pie-chart-2-fill me-1"></i>Hakediş Kontrolü Sağlıklı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-calculator-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="progress rounded-pill" style="height: 6px; background: #cbd5e1;">
                    <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $budgetComplianceRate }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card custom-filter-card-project mb-4">
        <form action="{{ route('portfolio.all-projects') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-lg-4 col-md-6">
                <input type="text" name="q" class="form-control custom-filter-input-project py-2.5 px-3" placeholder="Proje adı, kod, müdür veya şehir ara..." value="{{ request('q') }}">
            </div>
            <div class="col-lg-2 col-md-3">
                <select name="status" class="form-select custom-filter-input-project py-2.5">
                    <option value="">Tüm Durumlar</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <select name="type" class="form-select custom-filter-input-project py-2.5">
                    <option value="">Tüm Tipler</option>
                    @foreach($typeOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <select name="health" class="form-select custom-filter-input-project py-2.5">
                    <option value="">Tüm Sağlık Durumu</option>
                    @foreach($healthOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('health') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-warning text-dark rounded-pill flex-fill fw-extrabold py-2.5 shadow-sm">
                    <i class="ri-filter-3-fill me-1"></i>Filtrele
                </button>
                <a href="{{ route('portfolio.all-projects') }}" class="btn btn-outline-dark rounded-pill py-2.5 px-3.5 fw-extrabold" title="Sıfırla">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- SECTION HEADER & TOGGLE SWITCH -->
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 1.2rem;">
                <i class="ri-layout-grid-fill text-warning"></i>Şantiye & Proje Cetveli
                <span class="badge bg-dark text-warning rounded-pill px-3 py-1.5 fs-6 font-monospace border border-warning">{{ $projects->total() }} Kayıt</span>
            </h5>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end mt-2 mt-md-0">
            <div class="btn-group p-1 bg-white rounded-pill border border-secondary shadow-sm" role="group" id="viewToggleGroup">
                <button type="button" class="btn btn-sm rounded-pill fw-extrabold btn-warning text-dark px-3.5" id="btnGridView" onclick="switchView('grid')">
                    <i class="ri-grid-fill me-1"></i>Kart Görünümü
                </button>
                <button type="button" class="btn btn-sm rounded-pill fw-extrabold text-dark px-3.5" id="btnTableView" onclick="switchView('table')">
                    <i class="ri-table-line me-1"></i>Liste Görünümü
                </button>
            </div>
        </div>
    </div>

    @php
        $heroImages = [
            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1590381105924-c72589b9ef3f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80',
        ];
    @endphp

    <!-- CONTAINER 1: GRID VIEW -->
    <div id="gridContainer" class="row g-4 mb-4">
        @forelse($projects as $index => $project)
            @php
                $statusColor = 'primary';
                $statusText = 'Devam Ediyor';
                
                if ($project->status === 'completed') { $statusColor = 'success'; $statusText = 'Tamamlandı'; }
                elseif ($project->status === 'planned') { $statusColor = 'info'; $statusText = 'Planlama Aşamasında'; }
                elseif ($project->status === 'on_hold') { $statusColor = 'warning'; $statusText = 'Beklemede'; }
                elseif ($project->status === 'in_progress') { $statusColor = 'primary'; $statusText = 'Devam Ediyor'; }

                $healthBadge = 'bg-success';
                $healthText = 'Sağlıklı';
                if ($project->health_status === 'risky') { $healthBadge = 'bg-warning text-dark'; $healthText = 'Riskli'; }
                elseif ($project->health_status === 'critical') { $healthBadge = 'bg-danger text-white'; $healthText = 'Kritik'; }

                $progress = $project->progress_percent ?? 0;
                $coverUrl = $heroImages[$index % count($heroImages)];
            @endphp
            <div class="col-xl-4 col-md-6">
                <div class="card project-card-custom h-100 d-flex flex-column">
                    <!-- COVER IMAGE BANNER -->
                    <div class="card-img-wrapper">
                        <img src="{{ $coverUrl }}" alt="{{ $project->name }}" loading="lazy">
                        <div class="card-img-overlay-gradient"></div>
                        
                        <!-- TOP OVERLAY BADGES -->
                        <div class="position-absolute top-0 start-0 end-0 p-3 d-flex align-items-center justify-content-between z-2">
                            <span class="badge bg-{{ $statusColor }} text-white rounded-pill px-3 py-1.5 font-monospace fw-bold shadow-sm">
                                ● {{ $statusText }}
                            </span>
                            <span class="badge {{ $healthBadge }} rounded-pill px-3 py-1.5 font-monospace fw-bold shadow-sm">
                                {{ $healthText }}
                            </span>
                        </div>

                        <!-- BOTTOM TITLE OVERLAY -->
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white z-2">
                            <span class="badge bg-dark text-warning border border-warning rounded-pill px-2.5 py-1 mb-1.5 font-monospace fw-bold" style="font-size: 11px;">
                                {{ $project->project_code ?? 'PRJ-' . $project->id }}
                            </span>
                            <h5 class="fw-extrabold text-white mb-0 text-truncate" style="text-shadow: 0 2px 4px rgba(0,0,0,0.8);" title="{{ $project->name }}">{{ $project->name }}</h5>
                        </div>
                    </div>

                    <!-- CARD BODY -->
                    <div class="card-body p-4 flex-grow-1 d-flex flex-column justify-content-between">
                        <!-- Location & Manager Info -->
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2.5">
                            <span class="d-inline-flex align-items-center text-truncate fw-extrabold text-dark" style="max-width: 50%; font-size: 0.88rem;">
                                <i class="ri-map-pin-2-fill text-danger me-1.5 fs-6"></i>{{ $project->region ? $project->region . ' Bölgesi' : 'Lokasyon Yok' }}
                            </span>
                            <span class="d-inline-flex align-items-center text-truncate fw-extrabold text-dark" style="max-width: 48%; font-size: 0.88rem;">
                                <i class="ri-user-star-fill text-primary me-1.5 fs-6"></i>{{ $project->project_manager ?? 'Proje Müdürü' }}
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <small class="fw-extrabold text-dark" style="font-size: 11.5px;">FİZİKİ İLERLEME</small>
                                <span class="fw-extrabold text-primary font-monospace" style="font-size: 15px;">%{{ $progress }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background: #cbd5e1;">
                                <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $progress }}%;"></div>
                            </div>
                        </div>

                        <!-- Budget Grid -->
                        <div class="row g-2 p-3 rounded-3 text-center mb-2 border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block fw-extrabold" style="font-size: 11px;">BÜTÇE</small>
                                <span class="fw-extrabold text-dark font-monospace" style="font-size: 14.5px;">₺{{ number_format($project->budget ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fw-extrabold" style="font-size: 11px;">HARCANAN</small>
                                <span class="fw-extrabold text-success font-monospace" style="font-size: 14.5px;">₺{{ number_format($project->spent ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- CARD ACTIONS FOOTER -->
                    <div class="card-footer bg-white border-top-0 p-3 pt-0 d-flex gap-2">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-warning text-dark flex-fill rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-eye-fill me-1"></i>Detay
                        </a>
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-outline-dark rounded-circle p-2" title="Düzenle">
                            <i class="ri-pencil-line"></i>
                        </a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Projeyi Sil?', 'Bu proje kaydı silinecektir.')" title="Sil">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                    <i class="ri-building-4-line text-warning display-2 mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">Kayıtlı Proje Bulunamadı</h5>
                    <p class="text-muted mb-3">Arama kriterlerinize uygun proje bulunamadı veya henüz proje eklenmedi.</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        <i class="ri-add-line me-1"></i>Yeni Proje Oluştur
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- CONTAINER 2: TABLE VIEW -->
    <div id="tableContainer" class="row d-none mb-4">
        <div class="col-12">
            <div class="card custom-card-container overflow-hidden">
                <div class="table-responsive">
                    <table class="table custom-project-table align-middle mb-0">
                        <thead style="background-color: #0f172a !important;">
                            <tr style="background-color: #0f172a !important;">
                                <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Proje Kodu & Adı</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Bölge / Lokasyon</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Durum</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Proje Müdürü</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İlerleme (%)</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Bütçe (₺)</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Harcanan (₺)</th>
                                <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                @php
                                    $statusColor = 'primary';
                                    $statusText = 'Devam Ediyor';
                                    if ($project->status === 'completed') { $statusColor = 'success'; $statusText = 'Tamamlandı'; }
                                    elseif ($project->status === 'planned') { $statusColor = 'info'; $statusText = 'Planlama'; }
                                    elseif ($project->status === 'on_hold') { $statusColor = 'warning'; $statusText = 'Beklemede'; }
                                @endphp
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle bg-dark text-warning border border-warning d-flex align-items-center justify-content-center fw-extrabold" style="width: 40px; height: 40px;">
                                                <i class="ri-building-4-fill fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-extrabold mb-0 text-dark fs-6">{{ $project->name }}</h6>
                                                <span class="badge bg-dark text-warning font-monospace fw-bold" style="font-size: 10.5px;">{{ $project->project_code ?? 'PRJ-' . $project->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark"><i class="ri-map-pin-2-fill text-danger me-1"></i>{{ $project->region ?? 'Belirtilmedi' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }} text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">👤 {{ $project->project_manager ?? '-' }}</span>
                                    </td>
                                    <td style="width: 150px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-fill rounded-pill" style="height: 8px; background: #cbd5e1;">
                                                <div class="progress-bar bg-warning" style="width: {{ $project->progress_percent ?? 0 }}%;"></div>
                                            </div>
                                            <span class="fw-extrabold font-monospace text-dark">%{{ $project->progress_percent ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="font-monospace fw-extrabold text-dark">₺{{ number_format($project->budget ?? 0, 0, ',', '.') }}</td>
                                    <td class="font-monospace fw-extrabold text-success">₺{{ number_format($project->spent ?? 0, 0, ',', '.') }}</td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Detay">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Projeyi Sil?', 'Bu proje kaydı silinecektir.')" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="row mt-4 mb-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $projects->links() }}
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchView(mode) {
        const grid = document.getElementById('gridContainer');
        const table = document.getElementById('tableContainer');
        const btnGrid = document.getElementById('btnGridView');
        const btnTable = document.getElementById('btnTableView');

        if (mode === 'grid') {
            grid.classList.remove('d-none');
            table.classList.add('d-none');
            btnGrid.className = 'btn btn-sm rounded-pill fw-extrabold btn-warning text-dark px-3.5';
            btnTable.className = 'btn btn-sm rounded-pill fw-extrabold text-dark px-3.5';
        } else {
            grid.classList.add('d-none');
            table.classList.remove('d-none');
            btnTable.className = 'btn btn-sm rounded-pill fw-extrabold btn-warning text-dark px-3.5';
            btnGrid.className = 'btn btn-sm rounded-pill fw-extrabold text-dark px-3.5';
        }
    }
</script>
@endpush
