@extends('layouts.master')

@section('title', 'Stratejik Program Yönetim Portalı')

@push('styles')
<style>
    .prog-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .prog-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: progGlow 4s linear infinite;
    }

    @keyframes progGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .prog-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-prog {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-prog:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-input-prog {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-prog:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .prog-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .prog-card-container:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: #d97706 !important;
    }

    .manager-avatar-badge {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #0f172a !important;
        color: #f59e0b !important;
        border: 1.5px solid #d97706 !important;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="prog-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill prog-badge">
                        <i class="ri-stack-fill text-warning fs-5"></i> STRATEJİK PROGRAM YÖNETİM İNDEKSİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $programs->total() }} Aktif Program
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Stratejik Program Yönetim Portalı</h2>
                <p class="text-white-50 mb-0 fs-6" style="max-width: 780px; line-height: 1.6;">
                    Makro seviyedeki gayrimenkul ve inşaat programlarının konsolide bütçelerini, proje gruplarını ve stratejik hedeflerini yönetin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('portfolio.management.export') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-file-excel-2-line fs-5"></i>CSV Dışa Aktar
                    </a>
                    <a href="{{ route('portfolio.management.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Program Oluştur
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prog h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM PROGRAM SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_count'] ?? 0 }} Program</div>
                        <small class="text-warning fw-bold"><i class="ri-folder-key-fill me-1"></i>Stratejik Şemsiye Programlar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-stack-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prog h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">AKTİF PROGRAMLAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['active_count'] ?? 0 }} Program</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Yürürlükteki Yatırımlar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-play-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prog h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KONSOLİDE PROGRAM BÜTÇESİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">₺{{ number_format($stats['total_budget'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-wallet-3-line me-1"></i>Toplam Bütçe Taahhüdü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-bank-card-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prog h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GERÇEKLEŞEN HARCAMA</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.5rem;">₺{{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-history-fill me-1"></i>Fiili Şantiye Harcaması</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-coins-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER FORM CARD -->
    <div class="card prog-card-container shadow-sm mb-4">
        <div class="card-header bg-dark text-white p-3.5">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-filter-3-line text-warning fs-5"></i>Program Arama & Stratejik Hedef Filtresi
            </h5>
        </div>
        <div class="card-body p-3.5">
            <form method="GET" action="{{ route('portfolio.program-management.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="ri-search-2-line"></i></span>
                            <input type="text" name="q" class="form-control border-start-0 custom-input-prog" placeholder="Program adı veya yönetici adı..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select custom-input-prog">
                            <option value="">Durum: Tümü</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif Programlar</option>
                            <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planlanan</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlanan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select custom-input-prog">
                            <option value="">Stratejik Hedef: Tümü</option>
                            <option value="konut" {{ request('category') == 'konut' ? 'selected' : '' }}>Konut Geliştirme</option>
                            <option value="ticari" {{ request('category') == 'ticari' ? 'selected' : '' }}>Ticari Yatırımlar</option>
                            <option value="otel" {{ request('category') == 'otel' ? 'selected' : '' }}>Otel/Yatırım</option>
                            <option value="lojistik" {{ request('category') == 'lojistik' ? 'selected' : '' }}>Lojistik/Endüstri</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-warning text-dark w-100 rounded-3 fw-extrabold shadow-sm py-2">
                            Filtrele
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- PROGRAM CARDS GRID -->
    <div class="row g-4 mb-4">
        @forelse($programs as $program)
            <div class="col-md-6">
                <div class="card prog-card-container shadow-sm h-100">
                    <div class="card-header bg-dark text-white p-3.5 d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark font-monospace px-3 py-1.5 rounded-pill fw-extrabold">
                            {{ strtoupper($program->status ?? 'Aktif') }}
                        </span>
                        <div class="d-flex gap-1">
                            <a href="{{ route('portfolio.management.show', $program->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Detay">
                                <i class="ri-eye-line"></i>
                            </a>
                            <a href="{{ route('portfolio.management.edit', $program->id) }}" class="btn btn-sm btn-outline-light rounded-circle p-2" title="Düzenle">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                            <div>
                                <h4 class="fw-extrabold text-dark mb-1">
                                    <a href="{{ route('portfolio.management.show', $program->id) }}" class="text-dark text-decoration-none">
                                        {{ $program->name }}
                                    </a>
                                </h4>
                                <p class="text-secondary font-size-13 mb-0" style="line-height: 1.5;">{{ Str::limit($program->description, 110) }}</p>
                            </div>
                            <div class="manager-avatar-badge" title="Program Yöneticisi: {{ $program->manager ?? '-' }}">
                                {{ strtoupper(substr($program->manager ?? 'P', 0, 1)) }}
                            </div>
                        </div>

                        <!-- META SPECS -->
                        <div class="p-3.5 rounded-4 mb-3" style="background: #F8FAFC; border: 1.5px solid #cbd5e1;">
                            <div class="row g-2 text-center">
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block fw-bold font-size-11 text-uppercase">YÖNETİCİ</small>
                                    <strong class="text-dark font-size-13 fw-extrabold">{{ $program->manager ?? '-' }}</strong>
                                </div>
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block fw-bold font-size-11 text-uppercase">PROJE SAYISI</small>
                                    <strong class="text-primary font-size-13 fw-extrabold">{{ $program->meta['project_count'] ?? 0 }} Proje</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block fw-bold font-size-11 text-uppercase">SAĞLIK</small>
                                    @if(($program->meta['health'] ?? 'green') === 'green')
                                        <span class="badge text-white rounded-pill px-2.5 py-1 fw-bold" style="background: #16a34a;">Sağlıklı</span>
                                    @elseif(($program->meta['health'] ?? 'green') === 'yellow')
                                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-bold">Takipte</span>
                                    @else
                                        <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fw-bold">Kritik</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- PROGRESS BAR -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between font-size-13 mb-1">
                                <span class="text-muted fw-bold">Genel Tamamlanma Oranı:</span>
                                <strong class="text-dark font-monospace">%{{ $program->meta['progress_percent'] ?? 0 }}</strong>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px; background: #E2E8F0;">
                                <div class="progress-bar bg-success" style="width: {{ $program->meta['progress_percent'] ?? 0 }}%;"></div>
                            </div>
                        </div>

                        <!-- FINANCIAL SUMMARY -->
                        <div class="p-3 rounded-4 bg-light border d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 10.5px;">Konsolide Bütçe</small>
                                <strong class="text-dark font-monospace fs-6">₺{{ number_format($program->total_budget ?? 0, 0, ',', '.') }}</strong>
                            </div>
                            <div class="text-end">
                                <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 10.5px;">Harcanan Tutar</small>
                                <strong class="text-success font-monospace fs-6">₺{{ number_format($program->spent_to_date ?? 0, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="d-flex gap-2 pt-2">
                            <a href="{{ route('portfolio.management.show', $program->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-1.5 font-size-13 fw-extrabold shadow-sm w-100">
                                <i class="ri-pie-chart-line me-1"></i> Detay Kontrol Paneli
                            </a>
                            <a href="{{ route('portfolio.management.edit', $program->id) }}" class="btn btn-outline-dark rounded-pill px-3.5 py-1.5 font-size-13 fw-bold">
                                <i class="ri-pencil-line me-1"></i> Düzenle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card prog-card-container shadow-sm p-5 text-center">
                    <i class="ri-stack-line display-3 text-warning mb-3"></i>
                    <h5 class="fw-bold text-dark">Henüz Tanımlanmış Bir Program Bulunmuyor</h5>
                    <p class="text-muted mb-0">Sağ üstte bulunan "+ Yeni Program Oluştur" butonunu kullanarak ilk stratejik şemsiye programınızı oluşturabilirsiniz.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $programs->links() }}
    </div>

</div>
@endsection
