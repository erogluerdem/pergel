@extends('layouts.master')

@section('title', 'Proje Doküman Havuzu & Dijital Arşiv')

@section('topbar-title', 'Projeler - Doküman Havuzu')

@push('styles')
<style>
    .doc-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .doc-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: docGlow 4s linear infinite;
    }

    @keyframes docGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .doc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-doc {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-doc:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-filter-card-doc {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        padding: 20px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
    }

    .custom-filter-input-doc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }
    .custom-filter-input-doc:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .doc-project-card-custom {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .doc-project-card-custom:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.12) !important;
        border-color: #d97706 !important;
    }

    .custom-doc-table thead,
    .custom-doc-table thead tr,
    .custom-doc-table thead th {
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

    .custom-doc-table td {
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
    <div class="doc-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill doc-badge-pill">
                        <i class="ri-folder-zip-fill text-warning fs-5"></i> KURUMSAL DİJİTAL ARŞİV
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        CAD & PDF DOKÜMAN HAVUZU
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        {{ $totalDocuments }} Evrak Kaydı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Proje Doküman Havuzu & Dijital Arşiv</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Mimari, statik, tesisat ve resmi imar evraklarını proje bazlı merkezi dijital arşivde yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="btn-group p-1 bg-white rounded-pill border border-secondary shadow-sm" role="group" id="viewToggleGroup">
                    <button type="button" id="gridView" class="btn btn-sm rounded-pill fw-extrabold btn-warning text-dark px-3.5 active">
                        <i class="ri-grid-fill me-1"></i>Kart Görünümü
                    </button>
                    <button type="button" id="listView" class="btn btn-sm rounded-pill fw-extrabold text-dark px-3.5">
                        <i class="ri-table-line me-1"></i>Liste Görünümü
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-doc h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM PROJE</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $totalProjects }} Proje</div>
                        <small class="text-primary fw-bold"><i class="ri-building-2-fill me-1"></i>Kayıtlı Şantiyeler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-building-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-doc h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM DOKÜMAN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $totalDocuments }} Evrak</div>
                        <small class="text-success fw-bold"><i class="ri-file-text-fill me-1"></i>Arşivlenmiş Belge</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-file-text-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-doc h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">ONAYLANAN BELGELER</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ \App\Models\Document::where('status', 'approved')->count() }} Belge</div>
                        <small class="text-info fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Onaylı Sürüm</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-doc h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SİSTEM KLASÖRLERİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ \App\Models\DocumentFolder::count() }} Klasör</div>
                        <small class="text-warning fw-bold"><i class="ri-folder-3-fill me-1"></i>Kategori Dizinleri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-folder-3-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HIGH CONTRAST FILTERS BAR -->
    <div class="card custom-filter-card-doc mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <input id="projectSearch" type="text" class="form-control custom-filter-input-doc py-2.5 px-3" placeholder="Proje adı veya koduna göre ara...">
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select custom-filter-input-doc py-2.5">
                    <option value="">Tüm Proje Durumları</option>
                    <option value="active">Aktif Projeler</option>
                    <option value="planning">Planlama Aşamasında</option>
                    <option value="completed">Tamamlananlar</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="sortBy" class="form-select custom-filter-input-doc py-2.5">
                    <option value="name">Proje Adına Göre</option>
                    <option value="documents_count">En Çok Dokümanlı</option>
                    <option value="created_at">En Yeniler</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button id="resetFilters" class="btn btn-outline-dark rounded-pill w-100 py-2.5 fw-extrabold">
                    <i class="ri-refresh-line me-1"></i>Sıfırla
                </button>
            </div>
        </div>
    </div>

    <!-- PROJECTS GRID VIEW -->
    <div class="row g-4 mb-4" id="projectCards">
        @foreach($projects as $p)
            <div class="col-xl-4 col-md-6 project-card"
                 data-name="{{ strtolower($p->name) }}"
                 data-status="{{ $p->status }}"
                 data-created="{{ $p->created_at->timestamp }}"
                 data-documents="{{ $p->documents_count }}">
                <div class="card doc-project-card-custom p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle bg-dark text-warning border border-warning d-flex align-items-center justify-content-center fw-extrabold" style="width: 44px; height: 44px;">
                            <i class="ri-building-2-fill fs-4"></i>
                        </div>
                        <span class="badge bg-{{ $p->status == 'active' ? 'success' : ($p->status == 'completed' ? 'primary' : 'warning') }} rounded-pill px-3 py-1.5 font-monospace fw-bold">
                            ● {{ ucfirst($p->status ?? 'Aktif') }}
                        </span>
                    </div>

                    <h5 class="fw-extrabold text-dark mb-1">
                        <a href="{{ route('projects.documents.index', $p->id) }}" class="text-dark text-decoration-none hover-warning">
                            {{ $p->name }}
                        </a>
                    </h5>
                    <small class="badge bg-dark text-warning font-monospace fw-bold d-inline-block mb-3" style="width: fit-content;">
                        {{ $p->code ?? $p->project_code ?? 'PRJ-' . $p->id }}
                    </small>

                    <p class="text-muted fw-semibold small mb-4 flex-grow-1">
                        {{ Str::limit($p->description ?? 'Proje doküman arşivi ve sürüm geçmişi.', 80) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-4 border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                        <div>
                            <small class="text-muted d-block fw-extrabold" style="font-size: 11px;">TOPLAM EVRAK</small>
                            <strong class="fs-6 text-primary font-monospace"><i class="ri-file-text-fill me-1"></i>{{ $p->documents_count }} Doküman</strong>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block fw-extrabold" style="font-size: 11px;">SON GÜNCELLEME</small>
                            <small class="fw-extrabold text-dark font-monospace"><i class="ri-calendar-fill me-1 text-warning"></i>{{ $p->updated_at ? $p->updated_at->format('d.m.Y') : '-' }}</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('projects.documents.index', $p->id) }}" class="btn btn-warning text-dark rounded-pill flex-fill fw-extrabold shadow-sm">
                            <i class="ri-folder-open-fill me-1"></i>Dokümanları İncele
                        </a>
                        <a href="{{ route('projects.documents.upload', $p->id) }}" class="btn btn-outline-dark rounded-circle p-2" title="Doküman Yükle">
                            <i class="ri-upload-2-line fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- PROJECTS LIST VIEW (Hidden by default) -->
    <div class="card custom-card-container overflow-hidden d-none mb-4" id="projectList">
        <div class="table-responsive">
            <table class="table custom-doc-table align-middle mb-0">
                <thead style="background-color: #0f172a !important;">
                    <tr style="background-color: #0f172a !important;">
                        <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Proje Adı / Kod</th>
                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Durum</th>
                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Doküman Sayısı</th>
                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Klasör Sayısı</th>
                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Son İşlem Tarihi</th>
                        <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlemler</th>
                    </tr>
                </thead>
                <tbody id="projectTableBody">
                    @foreach($projects as $p)
                    <tr class="project-row"
                        data-name="{{ strtolower($p->name) }}"
                        data-status="{{ $p->status }}"
                        data-created="{{ $p->created_at->timestamp }}"
                        data-documents="{{ $p->documents_count }}">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-dark text-warning border border-warning d-flex align-items-center justify-content-center fw-extrabold" style="width: 40px; height: 40px;">
                                    <i class="ri-building-2-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-extrabold mb-0 text-dark fs-6">{{ $p->name }}</h6>
                                    <span class="badge bg-dark text-warning font-monospace fw-bold" style="font-size: 10.5px;">{{ $p->code ?? $p->project_code ?? 'PRJ-' . $p->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'active' ? 'success' : ($p->status == 'completed' ? 'primary' : 'warning') }} text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                {{ ucfirst($p->status ?? 'Aktif') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                {{ $p->documents_count }} Doküman
                            </span>
                        </td>
                        <td>
                            <span class="fw-extrabold text-dark"><i class="ri-folder-3-fill me-1 text-warning"></i>{{ $p->folders_count ?? 4 }} Klasör</span>
                        </td>
                        <td><span class="fw-extrabold font-monospace text-dark">{{ $p->updated_at ? $p->updated_at->format('d.m.Y H:i') : '-' }}</span></td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('projects.documents.index', $p->id) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold">
                                    <i class="ri-folder-open-fill me-1"></i>Aç
                                </a>
                                <a href="{{ route('projects.documents.upload', $p->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-extrabold">
                                    <i class="ri-upload-2-line me-1"></i>Yükle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <small class="text-muted fw-bold">Toplam {{ $projects->total() }} proje gösteriliyor</small>
        <div>{{ $projects->links() }}</div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('projectSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    const resetFilters = document.getElementById('resetFilters');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const projectCards = document.getElementById('projectCards');
    const projectList = document.getElementById('projectList');

    function filterProjects() {
        const query = searchInput.value.trim().toLowerCase();
        const status = statusFilter.value;
        const cards = Array.from(document.querySelectorAll('.project-card'));
        const rows = Array.from(document.querySelectorAll('.project-row'));

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const cardStatus = card.getAttribute('data-status');
            const matchesSearch = !query || name.includes(query);
            const matchesStatus = !status || cardStatus === status;
            card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const cardStatus = row.getAttribute('data-status');
            const matchesSearch = !query || name.includes(query);
            const matchesStatus = !status || cardStatus === status;
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    if(searchInput) searchInput.addEventListener('input', filterProjects);
    if(statusFilter) statusFilter.addEventListener('change', filterProjects);

    if(resetFilters) {
        resetFilters.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            sortBy.value = 'name';
            filterProjects();
        });
    }

    if(gridView && listView) {
        gridView.addEventListener('click', function() {
            gridView.classList.add('btn-warning', 'text-dark', 'active');
            gridView.classList.remove('btn-outline-dark');
            listView.classList.remove('btn-warning', 'text-dark', 'active');
            listView.classList.add('btn-outline-dark');
            projectCards.classList.remove('d-none');
            projectList.classList.add('d-none');
        });

        listView.addEventListener('click', function() {
            listView.classList.add('btn-warning', 'text-dark', 'active');
            listView.classList.remove('btn-outline-dark');
            gridView.classList.remove('btn-warning', 'text-dark', 'active');
            gridView.classList.add('btn-outline-dark');
            projectCards.classList.add('d-none');
            projectList.classList.remove('d-none');
        });
    }
});
</script>
@endpush
