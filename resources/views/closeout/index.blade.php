@extends('layouts.master')

@section('title', 'Proje Kapanış & Geçici Kabul Yönetimi')
@section('topbar-title', 'Projeler - Proje Kapanış')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & INDIGO GLASSMORPHIC THEME */
    .closeout-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #4338ca 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(99, 102, 241, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .closeout-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #3b82f6, #10b981, #6366f1);
        background-size: 300% 100%;
        animation: closeoutGlow 4s linear infinite;
    }

    @keyframes closeoutGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .closeout-badge-pill {
        background: rgba(99, 102, 241, 0.25) !important;
        border: 1px solid rgba(165, 180, 252, 0.4) !important;
        color: #e0e7ff !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Cards */
    .closeout-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
    }

    .closeout-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .closeout-kpi-card.total-card { border-left: 5px solid #4338ca !important; }
    .closeout-kpi-card.progress-card { border-left: 5px solid #0284c7 !important; }
    .closeout-kpi-card.completed-card { border-left: 5px solid #16a34a !important; }
    .closeout-kpi-card.delivered-card { border-left: 5px solid #7c3aed !important; }

    .closeout-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic & High Contrast Inputs */
    .custom-input-closeout {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }
    .custom-input-closeout:focus {
        background-color: #ffffff !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    }

    /* Table Styling */
    .closeout-table-card {
        border-radius: 18px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .closeout-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .closeout-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .closeout-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .progress-bar-custom {
        height: 10px;
        border-radius: 10px;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.4s ease;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <!-- HERO HEADER BANNER -->
    <div class="closeout-hero text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill closeout-badge-pill">
                        <i class="ri-checkbox-multiple-fill text-warning fs-5"></i> PROJECT HANDOVER & AS-BUILT HUB
                    </span>
                    <span class="badge bg-indigo text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background-color: #4338ca;">
                        PROJE KAPANIS & GEÇİCİ KABUL PORTALI
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Proje Kapanış & Geçici Kabul Yönetimi</h2>
                <p class="mb-0 fs-6" style="max-width: 820px; line-height: 1.6; color: #e0e7ff; font-weight: 500;">
                    Proje teslimat süreçlerini, As-Built çizimlerini, O&M (İşletme ve Bakım) kılavuzlarını, iskan evraklarını ve garanti takiplerini tek bir merkezden profesyonelce yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end">
                    <a href="{{ route('closeout.create') }}" class="btn btn-warning text-dark rounded-pill py-3 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="ri-add-circle-fill fs-4"></i> Yeni Kapanış Süreci Başlat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 TOP KPI CARDS -->
    @php
        $totalCloseouts = $closeouts->count();
        $draftCount = $closeouts->where('status', 'draft')->count();
        $inProgressCount = $closeouts->where('status', 'in_progress')->count();
        $completedCount = $closeouts->where('status', 'completed')->count();
        $deliveredCount = $closeouts->where('status', 'delivered')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="closeout-kpi-card total-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM KAPANIS SÜRECİ</span>
                        <h2 class="fw-extrabold mb-0" style="color: #4338ca;">{{ $totalCloseouts }}</h2>
                    </div>
                    <div class="closeout-stat-icon text-indigo" style="background: rgba(67, 56, 202, 0.12); color: #4338ca;">
                        <i class="ri-folder-zip-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-history-line me-1"></i>Kapanış Havuzu</small>
                    <span class="badge bg-light text-dark fw-bold border">100% Toplam</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="closeout-kpi-card progress-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">DEVAM EDEN TESLİMATLAR</span>
                        <h2 class="fw-extrabold mb-0 text-info">{{ $inProgressCount }}</h2>
                    </div>
                    <div class="closeout-stat-icon bg-info-subtle text-info">
                        <i class="ri-time-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-loader-4-line me-1"></i>Evrak & Onay Aşamasında</small>
                    <span class="badge bg-info-subtle text-info fw-bold">{{ $totalCloseouts > 0 ? round(($inProgressCount / $totalCloseouts) * 100) : 0 }}% Oran</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="closeout-kpi-card completed-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TAMAMLANAN TESLİMLER</span>
                        <h2 class="fw-extrabold mb-0 text-success">{{ $completedCount }}</h2>
                    </div>
                    <div class="closeout-stat-icon bg-success-subtle text-success">
                        <i class="ri-checkbox-circle-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-check-double-line me-1"></i>Geçici Kabul Tamam</small>
                    <span class="badge bg-success-subtle text-success fw-bold">{{ $totalCloseouts > 0 ? round(($completedCount / $totalCloseouts) * 100) : 0 }}% Oran</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="closeout-kpi-card delivered-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">İDAREYE TESLİM EDİLDİ</span>
                        <h2 class="fw-extrabold mb-0" style="color: #7c3aed;">{{ $deliveredCount }}</h2>
                    </div>
                    <div class="closeout-stat-icon text-purple" style="background: rgba(124, 58, 237, 0.12); color: #7c3aed;">
                        <i class="ri-shield-check-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-award-line me-1"></i>Kesin Kabul & Garanti Süreci</small>
                    <span class="badge bg-purple-subtle text-purple fw-bold" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">{{ $totalCloseouts > 0 ? round(($deliveredCount / $totalCloseouts) * 100) : 0 }}% Oran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR CARD -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px; border: 1.5px solid #cbd5e1 !important;">
        <div class="card-body p-3.5">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text custom-input-closeout border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                            <i class="ri-search-line text-primary fs-5"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control custom-input-closeout border-start-0 py-2.5" placeholder="Proje adı veya kayıt no ile filtreleyin..." style="border-radius: 0 10px 10px 0 !important;">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-select custom-input-closeout py-2.5">
                        <option value="">Tüm Durumlar (Hepsi)</option>
                        <option value="draft">Taslak Aşamada</option>
                        <option value="in_progress">Devam Ediyor (İşlemde)</option>
                        <option value="completed">Tamamlandı (Kabul Alındı)</option>
                        <option value="delivered">İdareye Teslim Edildi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-dark fw-bold rounded-pill w-100 py-2.5 d-flex align-items-center justify-content-center gap-1" onclick="clearFilters()">
                        <i class="ri-refresh-line text-warning"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CLOSEOUT DATA TABLE CARD -->
    <div class="card closeout-table-card mb-4">
        <div class="card-header bg-white border-0 py-3.5 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-extrabold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="ri-file-shield-fill fs-4" style="color: #4338ca;"></i> Proje Kapanış & Teslimat Kayıtları
            </h5>
            <span class="badge rounded-pill px-3 py-2 fw-extrabold" style="background: rgba(67, 56, 202, 0.1); color: #4338ca; border: 1px solid rgba(67, 56, 202, 0.3);">
                <i class="ri-database-2-line me-1"></i> Total: {{ $totalCloseouts }} Proje Kapanışı
            </span>
        </div>

        <div class="table-responsive">
            <table class="table closeout-table align-middle mb-0" id="closeoutTable">
                <thead>
                    <tr>
                        <th class="ps-4">PROJE & KAPANIS KODU</th>
                        <th>DURUM</th>
                        <th>TESLİMAT İLERLEMESİ</th>
                        <th>BAŞLANGIÇ TARİHİ</th>
                        <th>TAMAMLANMA TARİHİ</th>
                        <th class="text-end pe-4">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($closeouts as $closeout)
                        <tr data-status="{{ $closeout->status }}" data-name="{{ strtolower($closeout->project->name ?? '') }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center text-white font-monospace fw-bold" style="width: 44px; height: 44px; background: linear-gradient(135deg, #0f172a 0%, #4338ca 100%);">
                                        <i class="ri-building-4-line fs-5"></i>
                                    </div>
                                    <div>
                                        <strong class="text-dark d-block fs-6 fw-bold">{{ $closeout->project->name ?? 'Proje Belirtilmedi' }}</strong>
                                        <small class="text-secondary font-monospace fw-semibold">
                                            <i class="ri-hashtag text-primary me-0.5"></i>CLOSEOUT-{{ str_pad($closeout->id, 4, '0', STR_PAD_LEFT) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($closeout->status === 'completed')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                        <i class="ri-checkbox-circle-fill"></i> Tamamlandı
                                    </span>
                                @elseif($closeout->status === 'in_progress')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                        <i class="ri-time-fill"></i> Devam Ediyor
                                    </span>
                                @elseif($closeout->status === 'delivered')
                                    <span class="badge bg-purple-subtle text-purple border border-purple border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">
                                        <i class="ri-shield-check-fill"></i> İdareye Teslim Edildi
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                        <i class="ri-draft-fill"></i> Taslak
                                    </span>
                                @endif
                            </td>
                            <td style="min-width: 200px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-fill progress-bar-custom">
                                        <div class="progress-bar-fill" style="width: {{ $closeout->completion_percentage }}%; background: {{ $closeout->completion_percentage == 100 ? '#16a34a' : '#4338ca' }};"></div>
                                    </div>
                                    <span class="fw-extrabold font-monospace small text-dark me-2">{{ $closeout->completion_percentage }}%</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark fw-bold small"><i class="ri-calendar-line text-primary me-1"></i>{{ $closeout->started_at ? $closeout->started_at->format('d.m.Y') : '-' }}</span>
                            </td>
                            <td>
                                <span class="text-dark fw-bold small"><i class="ri-calendar-check-line text-success me-1"></i>{{ $closeout->completed_at ? $closeout->completed_at->format('d.m.Y') : '-' }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group gap-1">
                                    <a href="{{ route('closeout.show', $closeout) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3.5 fw-bold d-inline-flex align-items-center gap-1">
                                        <i class="ri-eye-line"></i> İncele
                                    </a>
                                    @if($closeout->status !== 'delivered')
                                        <a href="{{ route('closeout.edit', $closeout) }}" class="btn btn-sm btn-outline-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;" title="Kontrol Listesini Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('closeout.destroy', $closeout) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kapanış sürecini silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="ri-folder-zip-fill fs-1 d-block mb-3" style="color:#4338ca;"></i>
                                    <h5 class="fw-bold text-dark mb-1">Henüz başlatılmış proje kapanış süreci bulunamadı.</h5>
                                    <p class="text-secondary small mb-3">Tamamlanmak üzere olan projeleriniz için kapanış evrak takibini başlatın.</p>
                                    <a href="{{ route('closeout.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold" style="background:#4338ca; border:none;">
                                        <i class="ri-add-circle-line me-1"></i> İlk Kapanış Sürecini Başlat
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchInput')?.addEventListener('keyup', filterTable);
document.getElementById('statusFilter')?.addEventListener('change', filterTable);

function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;

    document.querySelectorAll('#closeoutTable tbody tr').forEach(row => {
        const rowName = row.getAttribute('data-name') || '';
        const rowStatus = row.getAttribute('data-status') || '';

        const matchesName = !q || rowName.includes(q);
        const matchesStatus = !status || rowStatus === status;

        row.style.display = (matchesName && matchesStatus) ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();
}
</script>
@endpush
