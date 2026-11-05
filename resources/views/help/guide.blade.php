@extends('layouts.master')

@section('title', 'Kullanım Kılavuzu & Uçtan Uca Başlangıç Rehberi')

@section('topbar-title', 'Destek - Kullanım Kılavuzu')

@push('styles')
<style>
    /* Hero Banner */
    .help-hero-banner {
        border-radius: 20px;
        padding: 2.5rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .help-hero-banner,
    :root:not([data-bs-theme="dark"]) .help-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #3b82f6 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .help-hero-banner {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* Glassmorphic Guide Cards */
    .guide-phase-card {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        position: relative;
    }

    html[data-bs-theme="light"] .guide-phase-card,
    :root:not([data-bs-theme="dark"]) .guide-phase-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .guide-phase-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }

    .step-number-badge {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.4rem;
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
    }

    /* Nav Pills Styling */
    .guide-nav-pills .nav-link {
        border-radius: 50px;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        color: #64748b;
        border: 1px solid #e2e8f0;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .guide-nav-pills .nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
    }

    html[data-bs-theme="dark"] .guide-nav-pills .nav-link {
        color: #94a3b8;
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="help-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('help.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Yardım Merkezi
                </a>
                <span class="badge bg-white text-dark rounded-pill px-3 py-1 fw-bold">12 Operasyonel Bölüm & Tüm Modüller</span>
            </div>
            <h1 class="fw-extrabold text-white mb-1 fs-2">Uçtan Uca PERGEL ERP Kullanım Kılavuzu</h1>
            <p class="text-white-50 mb-0 fs-6">Şantiye kurulumundan hakedişe, GİB e-faturadan yapay zeka analitiğine tüm modül ve sayfaların kullanım rehberi.</p>
        </div>

        <a href="{{ route('help.videos') }}" class="btn btn-light text-indigo rounded-pill px-4 fw-bold shadow-sm" style="color:#4f46e5;">
            <i class="ri-play-circle-line me-1"></i>Video Eğitimleri İzle
        </a>
    </div>
</div>

<!-- Quick Phase Navigation Pills -->
<div class="mb-4">
    <ul class="nav guide-nav-pills" id="guidePhasePills" role="tablist">
        @foreach($guideSteps as $index => $step)
            <li class="nav-item">
                <a class="nav-link {{ $index === 0 ? 'active' : '' }}" href="#phase-{{ $step->step }}">
                    <i class="{{ $step->icon }} me-1"></i>Bölüm {{ $step->step }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<!-- 12 Comprehensive Guide Phase Cards -->
<div class="row">
    <div class="col-12">
        @foreach($guideSteps as $step)
            <div class="guide-phase-card" id="phase-{{ $step->step }}">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-number-badge bg-{{ $step->color ?? 'primary' }}">
                            {{ $step->step }}
                        </div>
                        <div>
                            <span class="badge bg-{{ $step->color ?? 'primary' }} bg-opacity-10 text-{{ $step->color ?? 'primary' }} border rounded-pill px-3 py-1 mb-1 fw-bold">
                                {{ $step->category }}
                            </span>
                            <h3 class="fw-extrabold text-dark mb-0 fs-4">{{ $step->title }}</h3>
                        </div>
                    </div>

                    @if(!empty($step->url))
                        <a href="{{ $step->url }}" class="btn btn-sm btn-outline-{{ $step->color ?? 'primary' }} rounded-pill px-4 fw-bold shadow-sm">
                            Modüle Git <i class="ri-external-link-line ms-1"></i>
                        </a>
                    @endif
                </div>

                <p class="lead text-dark mb-3 fs-6 fw-semibold">{{ $step->description }}</p>

                <!-- Sub Modules Pill Links -->
                @if(!empty($step->sub_modules))
                <div class="mb-4 p-3 bg-body rounded-4 border">
                    <h6 class="fw-bold text-dark mb-2 fs-6">
                        <i class="ri-links-line text-{{ $step->color ?? 'primary' }} me-2"></i>Kapsamındaki Sayfalar ve Alt Modüller:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($step->sub_modules as $sub)
                            <a href="{{ $sub['url'] }}" class="btn btn-sm btn-light border rounded-pill text-dark px-3 py-1 fw-medium shadow-sm transition-all" style="font-size: 0.8rem;">
                                <i class="ri-arrow-right-s-line text-{{ $step->color ?? 'primary' }} me-1"></i>{{ $sub['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Detailed Sub-Steps -->
                <div class="mb-4 p-3 bg-body rounded-4 border">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="ri-checkbox-multiple-line text-{{ $step->color ?? 'primary' }} me-2"></i>Uygulama Adımları ve Senaryo:
                    </h6>
                    <ol class="mb-0 ps-3">
                        @foreach($step->details as $detail)
                            <li class="text-muted mb-2 lh-base">{{ $detail }}</li>
                        @endforeach
                    </ol>
                </div>

                <!-- Pro Tip & Warning Boxes -->
                <div class="row g-3">
                    @if(!empty($step->pro_tip))
                        <div class="col-lg-6">
                            <div class="alert alert-info border-0 rounded-4 p-3 mb-0 h-100">
                                <div class="d-flex gap-2">
                                    <i class="ri-lightbulb-line fs-4 text-info"></i>
                                    <div>
                                        <strong class="d-block text-dark mb-1">Uzman Mühendis İpucu:</strong>
                                        <small class="text-muted">{{ $step->pro_tip }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($step->warning))
                        <div class="col-lg-6">
                            <div class="alert alert-warning border-0 rounded-4 p-3 mb-0 h-100">
                                <div class="d-flex gap-2">
                                    <i class="ri-error-warning-line fs-4 text-warning"></i>
                                    <div>
                                        <strong class="d-block text-dark mb-1">Kritik Uarı / Not:</strong>
                                        <small class="text-muted">{{ $step->warning }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection