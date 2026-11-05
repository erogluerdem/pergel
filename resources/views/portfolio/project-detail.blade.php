@extends('layouts.master')

@section('title', $project->name . ' — Proje Detayı')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .project-detail-hero {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        min-height: 380px;
        background: #0f172a;
        border: 1px solid rgba(245, 158, 11, 0.4);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4);
        margin-bottom: 24px;
    }

    .project-detail-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: projectDetailGlow 4s linear infinite;
        z-index: 3;
    }

    @keyframes projectDetailGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .project-detail-hero img.hero-bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
    }

    .project-detail-overlay {
        position: relative;
        z-index: 2;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.3) 0%, rgba(15, 23, 42, 0.95) 100%);
        min-height: 380px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 36px;
    }

    .custom-kpi-detail-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-detail-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .detail-section-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
    }

    .info-label-custom {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }

    .info-value-custom {
        font-size: 0.98rem;
        font-weight: 800;
        color: #0f172a;
    }

    .spec-box-custom {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 12px !important;
        padding: 14px 16px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="row">
        <div class="col-12">
            <div class="project-detail-hero">
                @php
                    $cover = !empty($project->cover_image) ? asset('storage/' . $project->cover_image) : 'https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?auto=format&fit=crop&w=1200&q=80';
                    
                    $statusColor = 'primary';
                    $statusText = 'Devam Ediyor';
                    if ($project->status === 'completed') { $statusColor = 'success'; $statusText = 'Tamamlandı'; }
                    elseif ($project->status === 'planned') { $statusColor = 'info'; $statusText = 'Planlama Aşamasında'; }
                    elseif ($project->status === 'on_hold') { $statusColor = 'warning'; $statusText = 'Beklemede'; }

                    $healthBadge = 'bg-success';
                    $healthText = 'Sağlıklı';
                    if ($project->health_status === 'risky') { $healthBadge = 'bg-warning text-dark'; $healthText = 'Riskli'; }
                    elseif ($project->health_status === 'critical') { $healthBadge = 'bg-danger text-white'; $healthText = 'Kritik'; }
                @endphp
                <img src="{{ $cover }}" alt="{{ $project->name }}" class="hero-bg">
                <div class="project-detail-overlay">
                    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                <span class="badge bg-dark text-warning border border-warning px-3 py-1.5 rounded-pill font-monospace fw-bold" style="font-size: 11px;">
                                    PROJE KODU: {{ $project->project_code ?? 'PRJ-' . $project->id }}
                                </span>
                                <span class="badge bg-{{ $statusColor }} text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="font-size: 11px;">
                                    ● {{ $statusText }}
                                </span>
                                <span class="badge {{ $healthBadge }} rounded-pill px-3 py-1.5 font-monospace fw-bold" style="font-size: 11px;">
                                    SAĞLIK: {{ $healthText }}
                                </span>
                            </div>
                            <h1 class="display-5 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ $project->name }}</h1>
                            <p class="text-white-50 mb-0 fs-6 fw-medium" style="max-width: 750px;">
                                <i class="ri-map-pin-2-fill text-danger me-1 fs-5"></i>
                                {{ $project->province ? $project->province . ' / ' . $project->district : ($project->location ?? 'Lokasyon Belirtilmedi') }}
                                @if($project->region) • <span class="text-warning fw-bold">{{ $project->region }} Bölgesi</span> @endif
                            </p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                                <i class="ri-pencil-line fs-5"></i>Düzenle
                            </a>
                            <a href="{{ route('site_diaries.index', ['project_id' => $project->id]) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                                <i class="ri-camera-3-line text-warning fs-5"></i>Saha Günlüğü
                            </a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-pill px-3.5 py-2.5 fw-bold d-inline-flex align-items-center gap-2" onclick="return confirmDelete(this, 'Projeyi Sil?', 'Bu proje kaydı tamamen silinecektir.')">
                                    <i class="ri-delete-bin-line fs-5"></i>Sil
                                </button>
                            </form>
                            <a href="{{ route('portfolio.all-projects') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                                <i class="ri-arrow-left-line fs-5"></i>Listeye Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-detail-card h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="info-label-custom">PROJE BÜTÇESİ</span>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">₺{{ number_format($project->budget ?? 0, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-wallet-3-line me-1"></i>Planlanan Yatırım</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-wallet-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-detail-card h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="info-label-custom">HARCANAN TUTAR</span>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($project->spent ?? ($project->budget * 0.6), 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Hakediş & İmalat Harcaması</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-detail-card h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <span class="info-label-custom">FİZİKİ İLERLEME</span>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.7rem;">%{{ intval($project->progress_percent ?? 0) }}</div>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-pie-chart-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="progress rounded-pill" style="height: 8px; background: #cbd5e1;">
                    <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ intval($project->progress_percent ?? 0) }}%;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-detail-card h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="info-label-custom">MÜŞTERİ / İŞVEREN</span>
                        <div class="fw-extrabold text-dark text-truncate" style="font-size: 1.2rem; max-width: 170px;">{{ $project->client_name ?? $project->employer_name ?? 'Belirtilmedi' }}</div>
                        <small class="text-warning fw-bold"><i class="ri-user-star-fill me-1"></i>Sözleşme Tarafı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-user-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAIL MATRIX GRID -->
    <div class="row g-4 mb-4">
        <!-- Left Side: Overview, Cadastre, Docs -->
        <div class="col-lg-8">
            
            <!-- Proje Açıklaması & Genel Kapsam -->
            <div class="card detail-section-card p-4 mb-4">
                <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                    <i class="ri-file-text-line text-warning fs-4"></i>Proje Kapsamı & Genel Açıklama
                </h5>
                <div class="text-dark fw-semibold fs-6 p-3.5 rounded-3 mb-3" style="line-height: 1.7; background: #f8fafc; border: 1.5px solid #cbd5e1;">
                    {!! nl2br(e($project->description ?? 'Bu proje için henüz detaylı açıklama metni girilmemiştir.')) !!}
                </div>

                @if(!empty($project->tags))
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <small class="fw-extrabold text-muted me-2">ETİKETLER:</small>
                        @foreach(explode(',', $project->tags) as $tag)
                            <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1.5 font-monospace fw-bold">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Ruhsat, Tapu & İmar Matrisi -->
            <div class="card detail-section-card p-4 mb-4">
                <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                    <i class="ri-government-line text-warning fs-4"></i>Ruhsat, Tapu & İmar Matrisi
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">YAPI RUHSAT NO</span>
                            <span class="info-value-custom font-monospace text-primary">{{ $project->building_permit_number ?? $project->construction_license_number ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">RUHSAT VERİLİŞ TARİHİ</span>
                            <span class="info-value-custom">{{ $project->permit_issue_date ? \Carbon\Carbon::parse($project->permit_issue_date)->format('d.m.Y') : 'Belirtilmedi' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">TAPU PAFTA</span>
                            <span class="info-value-custom font-monospace">{{ $project->tapu_pafta ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">TAPU ADA</span>
                            <span class="info-value-custom font-monospace">{{ $project->tapu_ada ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">TAPU PARSEL</span>
                            <span class="info-value-custom font-monospace">{{ $project->tapu_parsel ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">BRÜT ALAN (m²)</span>
                            <span class="info-value-custom font-monospace text-success">{{ $project->gross_area ? number_format($project->gross_area, 2, ',', '.') . ' m²' : '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">NET ALAN (m²)</span>
                            <span class="info-value-custom font-monospace text-success">{{ $project->net_area ? number_format($project->net_area, 2, ',', '.') . ' m²' : '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">KAT SAYISI</span>
                            <span class="info-value-custom font-monospace">{{ $project->floor_count ? $project->floor_count . ' Kat' : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teknik Yapı Özellikleri -->
            <div class="card detail-section-card p-4 mb-4">
                <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                    <i class="ri-ruler-2-line text-warning fs-4"></i>Teknik Yapı & Şantiye Özellikleri
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">BİNA KULLANIM AMACI</span>
                            <span class="info-value-custom">{{ ucfirst($project->building_usage ?? 'Konut & Karma') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">YAPI SİSTEMİ</span>
                            <span class="info-value-custom">{{ ucfirst($project->structure_system ?? 'Betonarme / Çelik') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">BAŞLANGIÇ TARİHİ</span>
                            <span class="info-value-custom font-monospace">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d.m.Y') : 'Belirtilmedi' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="spec-box-custom">
                            <span class="info-label-custom">HEDEF BİTİŞ TARİHİ</span>
                            <span class="info-value-custom font-monospace text-danger">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d.m.Y') : 'Belirtilmedi' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proje Belgeleri -->
            @if($project->documents && $project->documents->count() > 0)
                <div class="card detail-section-card p-4">
                    <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                        <i class="ri-file-pdf-line text-danger fs-4"></i>Proje Dijital Belgeleri
                    </h5>
                    <div class="row g-2">
                        @foreach($project->documents as $doc)
                            <div class="col-md-6">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="p-3 rounded-3 border d-flex align-items-center gap-3 text-decoration-none" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                                    <div class="rounded-circle bg-danger bg-opacity-15 text-danger p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                        <i class="ri-file-pdf-fill fs-4"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-extrabold text-dark mb-0 text-truncate" style="font-size: 0.92rem;">{{ $doc->name }}</h6>
                                        <small class="text-muted fw-bold">PDF / Dijital Ek</small>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Side: Team, Contract & Mini Map -->
        <div class="col-lg-4">
            
            <!-- Saha & Yönetici Ekip -->
            <div class="card detail-section-card p-4 mb-4">
                <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                    <i class="ri-team-line text-warning fs-4"></i>Saha & Yönetici Ekip
                </h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-2.5 bg-transparent border-bottom">
                        <span class="info-label-custom">PROJE YÖNETİCİSİ</span>
                        <span class="info-value-custom text-primary"><i class="ri-user-star-fill text-warning me-1"></i>{{ $project->project_manager ?? 'Atanmadı' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-2.5 bg-transparent border-bottom">
                        <span class="info-label-custom">SORUMLU MÜHENDİS</span>
                        <span class="info-value-custom"><i class="ri-tools-fill text-primary me-1"></i>{{ $project->responsible_engineer_name ?? 'Belirtilmedi' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-2.5 bg-transparent border-bottom">
                        <span class="info-label-custom">SORUMLU MİMAR</span>
                        <span class="info-value-custom"><i class="ri-draft-line text-success me-1"></i>{{ $project->responsible_architect_name ?? 'Belirtilmedi' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-2.5 bg-transparent">
                        <span class="info-label-custom">YÜKLENİCİ FİRMA</span>
                        <span class="info-value-custom"><i class="ri-building-line text-secondary me-1"></i>{{ $project->contractor_name ?? 'Belirtilmedi' }}</span>
                    </li>
                </ul>
            </div>

            <!-- Finansal & Sözleşme Detayı -->
            <div class="card detail-section-card p-4 mb-4">
                <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                    <i class="ri-bank-card-line text-warning fs-4"></i>Sözleşme & Finans Detayı
                </h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-2.5 bg-transparent border-bottom">
                        <span class="info-label-custom">SÖZLEŞME NUMARASI</span>
                        <span class="info-value-custom font-monospace">{{ $project->contract_number ?? '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-2.5 bg-transparent border-bottom">
                        <span class="info-label-custom">FİNANSMAN KAYNAĞI</span>
                        <span class="info-value-custom">{{ ucfirst($project->financing_source ?? 'Özkaynak') }}</span>
                    </li>
                    <li class="list-group-item px-0 py-2.5 bg-transparent border-bottom">
                        <span class="info-label-custom">YÜKLENİCİ VKN</span>
                        <span class="info-value-custom font-monospace">{{ $project->contractor_vkn ?? '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-2.5 bg-transparent">
                        <span class="info-label-custom">PROJE İRTİBAT TELEFONU</span>
                        <span class="info-value-custom text-success"><i class="ri-phone-fill me-1"></i>{{ $project->project_contact_phone ?? $project->contact_phone ?? 'Telefon Yok' }}</span>
                    </li>
                </ul>

                <div class="mt-4">
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-pencil-line me-1"></i>Proje Bilgilerini Düzenle
                    </a>
                </div>
            </div>

            <!-- Mini GIS Harita Kartı -->
            @php
                $initialLat = null; $initialLng = null;
                $locVal = $project->location ?? '';
                if (is_string($locVal) && $locVal) {
                    $decoded = json_decode($locVal, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $initialLat = $decoded['lat'] ?? $decoded['latitude'] ?? null;
                        $initialLng = $decoded['lng'] ?? $decoded['longitude'] ?? null;
                    }
                } elseif (is_array($locVal)) {
                    $initialLat = $locVal['lat'] ?? $locVal['latitude'] ?? null;
                    $initialLng = $locVal['lng'] ?? $locVal['longitude'] ?? null;
                }
                if (!$initialLat) $initialLat = $project->latitude ?? 39.0;
                if (!$initialLng) $initialLng = $project->longitude ?? 35.0;
            @endphp
            <div class="card detail-section-card p-4">
                <h5 class="fw-extrabold text-dark mb-3 border-bottom pb-3 d-flex align-items-center gap-2">
                    <i class="ri-map-pin-2-line text-warning fs-4"></i>Coğrafi Konum (GIS)
                </h5>
                <div id="mini-project-map" style="width: 100%; height: 220px; border-radius: 12px; border: 1.5px solid #cbd5e1; overflow: hidden;"></div>
                <div class="mt-3">
                    <a href="{{ route('projects.map') }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-extrabold">
                        <i class="ri-map-2-line me-1"></i>GIS Haritada Göster
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = {{ $initialLat ?? 39.0 }};
    const lng = {{ $initialLng ?? 35.0 }};
    
    const miniMap = L.map('mini-project-map').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(miniMap);

    const customIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color: #d97706; width: 36px; height: 36px; border-radius: 50%; display:flex; align-items:center; justify-content:center; color:white; border: 2.5px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                <i class="ri-building-4-fill"></i>
               </div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 18]
    });

    L.marker([lat, lng], { icon: customIcon }).addTo(miniMap)
        .bindPopup('<b>{{ e($project->name) }}</b><br>{{ e($project->province ?? "Lokasyon") }}')
        .openPopup();
});
</script>
@endpush
