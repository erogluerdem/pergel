@extends('layouts.master')

@section('title', 'Taşeron Portföyü & Firma Derecelendirme')
@section('topbar-title', 'Saha Operasyonları - Taşeron Yönetimi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & EMERALD GOLD GLASSMORPHIC THEME */
    .subcontractor-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #065f46 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .subcontractor-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: subGlow 4s linear infinite;
    }

    @keyframes subGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sub-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .sub-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-input-sub {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .sub-table-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .sub-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .sub-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .sub-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="subcontractor-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        SAHA OPERASYONLARI & İSG SKORLARI
                    </span>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 fw-bold">
                        ONAYLI FİRMA PORTFÖYÜ
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Taşeron Yönetimi & Firma Derecelendirme</h2>
                <p class="mb-0 fs-6" style="color: #d1fae5; font-weight: 500; max-width: 820px;">
                    Saha yüklenici firmalarını, uzmanlık alanlarını, İSG puanlamalarını, performans skorlarını ve aktif proje atamalarını yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('subcontractor.companies') }}" class="btn btn-warning text-dark rounded-pill py-3 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2">
                    <i class="ri-add-circle-fill fs-4"></i> + Yeni Firma Kaydet
                </a>
            </div>
        </div>
    </div>

    <!-- 4 SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card sub-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">KAYITLI TAŞERON FİRMALAR</small>
                <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $subcontractors->total() }} <small class="fs-6 text-muted">Firma</small></h2>
                <small class="text-primary fw-bold mt-2 d-block"><i class="ri-building-line me-1"></i>Saha Portföyü</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sub-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ONAYLI FİRMALAR</small>
                <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $subcontractors->where('status', 'approved')->count() }} <small class="fs-6 text-muted">Aktif</small></h2>
                <small class="text-success fw-bold mt-2 d-block"><i class="ri-checkbox-circle-fill me-1"></i>Saha Çalışma İzinli</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sub-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ORTALAMA PERFORMANS SKORU</small>
                <h2 class="fw-extrabold mb-0 text-warning font-monospace">4.7 <small class="fs-6 text-muted">/ 5.0</small></h2>
                <small class="text-warning fw-bold mt-2 d-block"><i class="ri-star-fill me-1"></i>Kalite & Zamanlama</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sub-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ORTALAMA İSG GÜVENLİK PUANI</small>
                <h2 class="fw-extrabold mb-0 text-info font-monospace">%94.5</h2>
                <small class="text-info fw-bold mt-2 d-block"><i class="ri-shield-check-line me-1"></i>6331 s.K. Uyumlu</small>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card sub-table-card p-4 mb-4">
        <form method="GET" action="{{ route('subcontractor.index') }}" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label for="search" class="form-label fw-extrabold text-dark small mb-1">Arama Kriteri</label>
                <input type="text" class="form-control custom-input-sub py-2" id="search" name="search"
                       value="{{ request('search') }}" placeholder="Firma adı veya uzmanlık alanı...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label fw-extrabold text-dark small mb-1">Durum Filtresi</label>
                <select class="form-select custom-input-sub py-2" id="status" name="status">
                    <option value="">Tüm Durumlar</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>🟢 Onaylı</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🟡 Onay Bekliyor</option>
                    <option value="conditional" {{ request('status') == 'conditional' ? 'selected' : '' }}>🔵 Şartlı Onaylı</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>🔴 Kara Listede</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="performance_min" class="form-label fw-extrabold text-dark small mb-1">Min. Performans Puanı</label>
                <select class="form-select custom-input-sub py-2" id="performance_min" name="performance_min">
                    <option value="">Tüm Puanlar</option>
                    <option value="4" {{ request('performance_min') == '4' ? 'selected' : '' }}>⭐ 4.0 ve Üzeri</option>
                    <option value="3" {{ request('performance_min') == '3' ? 'selected' : '' }}>⭐ 3.0 ve Üzeri</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2 align-self-end">
                <button type="submit" class="btn btn-emerald text-white rounded-pill flex-fill py-2 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                    <i class="ri-filter-3-line me-1"></i> Filtrele
                </button>
                <a href="{{ route('subcontractor.index') }}" class="btn btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Sıfırla">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="sub-table-card">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-building-line text-warning fs-5"></i> Kayıtlı Taşeron Firmalar İndeksi
            </h5>
            <span class="badge bg-emerald text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #047857;">{{ $subcontractors->total() }} Kayıt</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sub-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">FİRMA ADI</th>
                            <th>UZMANLIK ALANI</th>
                            <th>PERFORMANS SKORU</th>
                            <th>İSG GÜVENLİK SKORU</th>
                            <th>DURUM</th>
                            <th>AKTİF PROJELER</th>
                            <th class="pe-4 text-end">İŞLEMLER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcontractors as $subcontractor)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark fs-6 d-block mb-0.5">
                                        <a href="{{ route('subcontractor.show', $subcontractor->id) }}" class="text-dark text-decoration-none fw-extrabold hover-emerald">
                                            {{ $subcontractor->company_name }}
                                        </a>
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace text-warning px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                        {{ $subcontractor->expertise_area ?: 'Kaba / İnce İnşaat' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 font-monospace fw-extrabold text-warning fs-6">
                                        <i class="ri-star-fill text-warning"></i>
                                        <span>{{ number_format($subcontractor->performance_score, 1) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold font-monospace">
                                        %{{ number_format($subcontractor->safety_score, 1) }}
                                    </span>
                                </td>
                                <td>
                                    @switch($subcontractor->status)
                                        @case('approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                <i class="ri-checkbox-circle-fill me-1"></i>Onaylı
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                <i class="ri-time-line me-1"></i>Onay Bekliyor
                                            </span>
                                            @break
                                        @case('conditional')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                <i class="ri-information-line me-1"></i>Şartlı Onaylı
                                            </span>
                                            @break
                                        @case('blacklisted')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                <i class="ri-error-warning-line me-1"></i>Kara Listede
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary rounded-pill px-3 py-1.5 fw-bold">{{ $subcontractor->status }}</span>
                                    @endswitch
                                </td>
                                <td class="font-monospace fw-bold text-dark fs-6">
                                    {{ $subcontractor->active_projects ?: 1 }} Proje
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('subcontractor.show', $subcontractor->id) }}" class="btn btn-sm btn-emerald text-white rounded-pill px-3.5 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                                        <i class="ri-eye-line me-1"></i> Görüntüle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ri-building-line display-4 text-secondary mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Taşeron Firma Bulunmuyor</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-between align-items-center border-top">
                <small class="text-muted font-monospace fw-bold">Toplam {{ $subcontractors->total() }} firma listeleniyor</small>
                <div>{{ $subcontractors->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>

</div>

@endsection
