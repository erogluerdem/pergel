@extends('layouts.master')

@section('title', 'Sözleşme Kütüphanesi & Şablon Yönetimi')
@section('topbar-title', 'Sözleşme Yönetimi - Şablon Kütüphanesi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GOLD GLASSMORPHIC THEME */
    .library-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .library-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: libraryGlow 4s linear infinite;
    }

    @keyframes libraryGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .library-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .library-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .library-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .library-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Form & Input Styling */
    .custom-input-library {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-library:focus {
        background-color: #ffffff !important;
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
    }

    .library-table-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .library-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .library-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .library-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Modal Safety Fix */
    .modal {
        z-index: 1055 !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="library-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('contracts.management') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Sözleşmeler Yönetimine Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill library-badge-pill">
                        <i class="ri-book-3-fill text-warning fs-5"></i> 6098 SAYILI BORÇLAR KANUNU (ESER SÖZLEŞMELERİ)
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Sözleşme Kütüphanesi & Şablon Yönetimi</h2>
                <p class="mb-0 fs-6" style="color: #fef3c7; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Türkiye İnşaat Standartlarına (TMMOB & Kamu İhale Mevzuatı) uygun taşeron, tedarik ve hizmet sözleşmesi tipi hazır şablonları ve hukuki kütüphane maddelerini yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end">
                    <button type="button" class="btn btn-warning text-dark rounded-pill py-2.5 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center justify-content-center gap-2" onclick="showNewTemplateModal()">
                        <i class="ri-add-circle-fill fs-4"></i> + Yeni Şablon Ekle
                    </button>
                    <button type="button" class="btn btn-emerald text-white rounded-pill py-2.5 px-4 fw-extrabold shadow-sm d-inline-flex align-items-center justify-content-center gap-2" onclick="showNewClauseModal()" style="background: #047857; border: none;">
                        <i class="ri-scales-3-line fs-4"></i> + Yeni Hukuki Madde Ekle
                    </button>
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

    <!-- 4 SUMMARY KPI SPEC CARDS -->
    @php
        $activeTemplatesCount = $templates->where('status', 'active')->count();
        $activeClausesCount = $clauses->where('status', 'active')->count();
        $categoriesCount = $clauses->pluck('category')->unique()->filter()->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card library-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SÖZLEŞME ŞABLONLARI</span>
                        <h2 class="fw-extrabold mb-0 text-dark font-monospace">{{ $templates->count() }} <small class="fs-6 text-muted">Şablon</small></h2>
                    </div>
                    <div class="library-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-file-word-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-line me-1"></i>Aktif Şablonlar</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">{{ $activeTemplatesCount }} Etkin</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card library-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">MADDE KÜTÜPHANESİ</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $clauses->count() }} <small class="fs-6 text-muted">Madde</small></h2>
                    </div>
                    <div class="library-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-scales-3-fill fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-check-line me-1"></i>Hukuki Hükümler</small>
                    <span class="badge bg-success-subtle text-success fw-bold">{{ $activeClausesCount }} Hazır Metin</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card library-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">HUKUKİ KATEGORİLER</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace">{{ max(1, $categoriesCount) }} <small class="fs-6 text-muted">Kategori</small></h2>
                    </div>
                    <div class="library-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-folders-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-price-tag-3-line me-1"></i>İSG, Ödeme, Fesih</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Sınıflandırılmış</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card library-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SİSTEM UYUMLULUĞU</span>
                        <h2 class="fw-extrabold mb-0 text-primary">TMMOB & BK</h2>
                    </div>
                    <div class="library-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-government-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-building-line me-1"></i>Tip Şartnameler</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Tip Sözleşme</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="card library-table-card p-4 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text custom-input-library border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                        <i class="ri-search-line text-muted fs-5"></i>
                    </span>
                    <input type="text" id="librarySearchInput" class="form-control custom-input-library border-start-0 py-2" placeholder="Şablon adı, madde başlığı veya içerik ara..." style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>

            <div class="col-lg-6 text-lg-end">
                <div class="btn-group btn-group-sm flex-wrap gap-1" role="group">
                    <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold active" onclick="filterClausesCategory('all')">Tüm Kategoriler</button>
                    <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold" onclick="filterClausesCategory('Ödeme')">Ödeme & Hakediş</button>
                    <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold" onclick="filterClausesCategory('İSG')">İSG & Güvenlik</button>
                    <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold" onclick="filterClausesCategory('Garanti')">Garanti & Bakım</button>
                    <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold" onclick="filterClausesCategory('Fesih')">Fesih & İptal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. TEMPLATES SECTION -->
    <div class="library-table-card">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-file-word-fill text-warning fs-5"></i> Hazır Sözleşme Şablonları Portföyü
            </h5>
            <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill px-3.5 fw-extrabold shadow-sm" onclick="showNewTemplateModal()">
                <i class="ri-add-line"></i> Yeni Şablon
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table library-table align-middle mb-0" id="templatesTable">
                    <thead>
                        <tr>
                            <th class="ps-4">ŞABLON ADI & ÖZETİ</th>
                            <th>DURUM</th>
                            <th>OLUŞTURAN</th>
                            <th>TARİH</th>
                            <th class="pe-4 text-end">İŞLEMLER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark d-block fs-6 mb-1">
                                        <i class="ri-file-word-line text-primary me-1"></i>{{ $template->title }}
                                    </strong>
                                    <small class="text-secondary fw-semibold d-block text-truncate" style="max-width: 550px;">
                                        {{ Str::limit(strip_tags($template->body), 110) }}
                                    </small>
                                </td>
                                <td>
                                    @if($template->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="ri-checkbox-circle-fill me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="ri-edit-line me-1"></i>Taslak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark small"><i class="ri-user-3-line text-muted me-1"></i>{{ $template->created_by ? 'User #'.$template->created_by : 'Sistem Rehberi' }}</span>
                                </td>
                                <td class="font-monospace fw-bold text-muted small">
                                    {{ optional($template->created_at)->format('d.m.Y') }}
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group gap-1">
                                        <button class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold shadow-sm" onclick="previewTemplate({{ $template->id }})" title="Önizle">
                                            <i class="ri-eye-line me-1"></i>Önizle
                                        </button>
                                        <a href="{{ route('contracts.library.templates.edit', $template->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form method="POST" action="{{ route('contracts.library.templates.duplicate', $template->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="Kopyala">
                                                <i class="ri-file-copy-line"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('contracts.library.templates.destroy', $template->id) }}" class="d-inline" onsubmit="return confirm('Bu şablonu silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="ri-file-word-line display-4 text-secondary mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Şablon Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Yukarıdaki "Yeni Şablon" butonu ile ilk Tip Sözleşmenizi oluşturabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. CLAUSES SECTION -->
    <div class="library-table-card">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-scales-3-fill text-emerald fs-5" style="color: #047857;"></i> Hukuki Madde Kütüphanesi İndeksi
            </h5>
            <button type="button" class="btn btn-emerald text-white btn-sm rounded-pill px-3.5 fw-extrabold shadow-sm" onclick="showNewClauseModal()" style="background: #047857; border: none;">
                <i class="ri-add-line"></i> Yeni Madde Ekle
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table library-table align-middle mb-0" id="clausesTable">
                    <thead>
                        <tr>
                            <th class="ps-4">MADDE BAŞLIĞI & İÇERİK ÖZETİ</th>
                            <th>KATEGORİ</th>
                            <th>DURUM</th>
                            <th>TARIH</th>
                            <th class="pe-4 text-end">İŞLEMLER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clauses as $clause)
                            <tr data-category="{{ $clause->category }}">
                                <td class="ps-4 py-3">
                                    <strong class="text-dark d-block fs-6 mb-1">
                                        <i class="ri-checkbox-circle-fill text-success me-1"></i>{{ $clause->title }}
                                    </strong>
                                    <small class="text-secondary fw-semibold d-block text-truncate" style="max-width: 550px;">
                                        {{ Str::limit($clause->content, 110) }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace text-warning px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                        {{ $clause->category ?: 'Genel Hüküm' }}
                                    </span>
                                </td>
                                <td>
                                    @if($clause->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="ri-checkbox-circle-fill me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="ri-edit-line me-1"></i>Taslak
                                        </span>
                                    @endif
                                </td>
                                <td class="font-monospace fw-bold text-muted small">
                                    {{ optional($clause->created_at)->format('d.m.Y') }}
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group gap-1">
                                        <button class="btn btn-sm btn-emerald text-white rounded-pill px-3 fw-extrabold shadow-sm" onclick="previewClause({{ $clause->id }})" style="background: #047857; border: none;" title="Önizle">
                                            <i class="ri-eye-line me-1"></i>Önizle
                                        </button>
                                        <a href="{{ route('contracts.library.clauses.edit', $clause->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form method="POST" action="{{ route('contracts.library.clauses.destroy', $clause->id) }}" class="d-inline" onsubmit="return confirm('Bu kütüphane maddesini silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="ri-scales-3-line display-4 text-secondary mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Kütüphane Maddesi Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Yukarıdaki "Yeni Madde Ekle" butonu ile cezai şartlar, İSG veya ödeme hükümlerini kaydedebilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MEVZUAT VE ŞARTNAME REHBERİ CARD -->
    <div class="card library-table-card p-4">
        <h5 class="fw-extrabold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="ri-book-open-fill text-warning me-1"></i> Şantiye & Taşeron Hukuku Uygulama Rehberi
        </h5>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-scales-line text-primary fs-5"></i> 6098 s.K. Eser Sözleşmesi
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        İnşaat yüklenici ve taşeron sözleşmeleri Borçlar Kanunu madde 470-486 hükümlerine tabidir. Ayıba karşı tekeffül, geçici kabul ve kesin hesap süreleri şablonda açıkça belirtilmelidir.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-shield-cross-line text-danger fs-5"></i> İSG & Saha Sorumluluğu
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        6331 sayılı İSG kanunu gereğince taşeron çalışanlarının baret, yelek, yüksekte çalışma sertifikaları ve işe başlama izinleri eksiksiz olmadan sahaya girmesi yasaktır.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-hand-coin-line text-success fs-5"></i> Nakit Teminat & Kesintiler
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        Hakediş ödemelerinde her hakedişten %5 nakit teminat kesintisi yapılır ve geçici kabul onaylanana kadar blokajlı olarak saklanır.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- NEW TEMPLATE MODAL -->
<div class="modal fade" id="newTemplateModal" tabindex="-1" aria-labelledby="newTemplateModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-bottom: 3px solid #f59e0b;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newTemplateModalLabel">
                    <i class="ri-file-word-fill text-warning fs-4"></i> Yeni Sözleşme Şablonu Oluştur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form action="{{ route('contracts.library.templates.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <div class="mb-3">
                        <label for="template_title" class="form-label fw-extrabold text-dark">ŞABLON ADI <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="template_title" class="form-control custom-input-library py-2.5" placeholder="Örn: Tip Kaba İnşaat Taşeronluk Şartnamesi" required>
                    </div>

                    <div class="mb-3">
                        <label for="template_body" class="form-label fw-extrabold text-dark">ŞABLON METNİ & GENEL HÜKÜMLER <span class="text-danger">*</span></label>
                        <textarea name="body" id="template_body" class="form-control custom-input-library" rows="9" placeholder="Şablon maddelerini, taraf yükümlülüklerini ve ödeme şartlarını yazınız..." required></textarea>
                    </div>

                    <div class="mb-2">
                        <label for="template_status" class="form-label fw-extrabold text-dark">DURUM</label>
                        <select name="status" id="template_status" class="form-select custom-input-library py-2">
                            <option value="active" selected>🟢 Aktif (Sistemde Kullanılabilir)</option>
                            <option value="draft">🟡 Taslak</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-extrabold shadow-sm" style="background: #f59e0b; border: none;">
                        <i class="ri-save-3-fill me-1"></i> Şablonu Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- NEW CLAUSE MODAL -->
<div class="modal fade" id="newClauseModal" tabindex="-1" aria-labelledby="newClauseModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #064e3b 100%); border-bottom: 3px solid #10b981;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newClauseModalLabel">
                    <i class="ri-scales-3-fill text-warning fs-4"></i> Yeni Hukuki Madde Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form action="{{ route('contracts.library.clauses.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label for="clause_title" class="form-label fw-extrabold text-dark">MADDE BAŞLIĞI <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="clause_title" class="form-control custom-input-library py-2.5" placeholder="Örn: İş Güvenliği İhlal Cezaları ve Fesih Hakkı" required>
                        </div>
                        <div class="col-md-5">
                            <label for="clause_category" class="form-label fw-extrabold text-dark">KATEGORİ</label>
                            <select name="category" id="clause_category" class="form-select custom-input-library py-2.5">
                                <option value="İSG">İSG & Güvenlik</option>
                                <option value="Ödeme">Ödeme & Hakediş</option>
                                <option value="Süre">Süre & Program</option>
                                <option value="Garanti">Garanti & Bakım</option>
                                <option value="Sigorta">Sigorta & Teminat</option>
                                <option value="Fesih">Fesih & İptal</option>
                                <option value="Genel" selected>Genel Hükümler</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="clause_content" class="form-label fw-extrabold text-dark">MADDE İÇERİĞİ & ŞARTNAME METNİ <span class="text-danger">*</span></label>
                        <textarea name="content" id="clause_content" class="form-control custom-input-library" rows="7" placeholder="Hukuki madde metnini detaylı yazınız..." required></textarea>
                    </div>

                    <div class="mb-2">
                        <label for="clause_status" class="form-label fw-extrabold text-dark">DURUM</label>
                        <select name="status" id="clause_status" class="form-select custom-input-library py-2">
                            <option value="active" selected>🟢 Aktif</option>
                            <option value="draft">🟡 Taslak</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                        <i class="ri-save-3-fill me-1"></i> Maddeyi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PREVIEW MODAL -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-bottom: 3px solid #3b82f6;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="previewModalTitle">
                    <i class="ri-eye-fill text-warning fs-4"></i> Önizleme
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #ffffff;">
                <div id="previewContent" class="p-3.5 bg-light rounded-4 font-monospace border text-dark fs-6" style="white-space: pre-line; line-height: 1.6; border-color: #cbd5e1 !important; max-height: 400px; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer px-4 py-3 bg-light border-top">
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// SAFE BACKDROP-PROOF MODAL LAUNCHERS
function showNewTemplateModal() {
    const modalEl = document.getElementById('newTemplateModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

function showNewClauseModal() {
    const modalEl = document.getElementById('newClauseModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

const templatesData = @json($templates);
const clausesData = @json($clauses);

function previewTemplate(id) {
    const template = templatesData.find(t => t.id === id);
    if (template) {
        const modalEl = document.getElementById('previewModal');
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        document.getElementById('previewModalTitle').textContent = template.title;
        document.getElementById('previewContent').textContent = template.body;
        if (window.bootstrap) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }
}

function previewClause(id) {
    const clause = clausesData.find(c => c.id === id);
    if (clause) {
        const modalEl = document.getElementById('previewModal');
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        document.getElementById('previewModalTitle').textContent = clause.title;
        document.getElementById('previewContent').textContent = clause.content;
        if (window.bootstrap) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }
}

function filterClausesCategory(category) {
    const rows = document.querySelectorAll('#clausesTable tbody tr');
    rows.forEach(row => {
        const rowCategory = row.getAttribute('data-category') || '';
        if (category === 'all' || rowCategory.toLowerCase() === category.toLowerCase()) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('librarySearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#templatesTable tbody tr, #clausesTable tbody tr').forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
