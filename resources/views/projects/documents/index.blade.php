@extends('layouts.master')

@section('title', $project->name . ' — Doküman Kontrolü & Klasörler')

@section('topbar-title', 'Projeler - Doküman Kontrolü')

@push('styles')
<style>
    .doc-proj-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .doc-proj-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: docProjGlow 4s linear infinite;
    }

    @keyframes docProjGlow {
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

    .doc-card-modern-custom {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
    }

    .folder-item-btn-custom {
        border-radius: 12px !important;
        padding: 11px 15px !important;
        border: 1.5px solid #cbd5e1 !important;
        transition: all 0.2s ease !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        margin-bottom: 8px !important;
        background: #f8fafc !important;
        color: #0f172a !important;
        font-weight: 700 !important;
    }

    .folder-item-btn-custom:hover, .folder-item-btn-custom.active {
        background: #fffbeb !important;
        color: #d97706 !important;
        border-color: #d97706 !important;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.12) !important;
    }

    .custom-filter-input-projdoc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }
    .custom-filter-input-projdoc:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-projdoc-table thead,
    .custom-projdoc-table thead tr,
    .custom-projdoc-table thead th {
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

    .custom-projdoc-table td {
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
    <div class="doc-proj-hero text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill doc-badge-pill">
                        <i class="ri-folder-open-fill text-warning fs-5"></i> PROJE DOKÜMAN KONTROLÜ
                    </span>
                    <span class="badge bg-dark text-warning border border-warning px-3 py-2 rounded-pill font-monospace fw-bold">
                        {{ $project->code ?? $project->project_code ?? 'PRJ-' . $project->id }}
                    </span>
                    <span class="badge bg-success text-white px-3 py-2 rounded-pill font-monospace fw-bold">
                        ● {{ ucfirst($project->status ?? 'Aktif') }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $project->name }} — Doküman Havuzu</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Proje bazlı klasörler, mimari/statik teknik şemalar, onay matrisi ve sürüm geçmişini yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('projects.documents.upload', $project->id) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-upload-cloud-2-fill fs-5"></i>+ Yeni Doküman Yükle
                    </a>
                    <a href="{{ route('projects.documents.all') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-arrow-left-line fs-5"></i>Tüm Projeler Havuzu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="row g-4 mb-4">
        <!-- Left Sidebar: Folders -->
        <div class="col-lg-3">
            <div class="card doc-card-modern-custom p-4 mb-4">
                <h5 class="fw-extrabold text-dark mb-3 pb-3 border-bottom d-flex align-items-center justify-content-between">
                    <span><i class="ri-folder-3-fill text-warning me-2 fs-5"></i>Dizin Klasörleri</span>
                    <span class="badge bg-dark text-warning rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ count($folders) }}</span>
                </h5>

                <div id="folderList">
                    <button type="button" class="folder-item-btn-custom active" data-folder-id="">
                        <span><i class="ri-folder-shared-fill me-2 text-warning"></i>Tüm Klasörler</span>
                        <span class="badge bg-dark text-warning rounded-pill font-monospace fw-bold">{{ $documents->total() }}</span>
                    </button>

                    @forelse($folders as $f)
                        <button type="button" class="folder-item-btn-custom" data-folder-id="{{ $f->id }}">
                            <span class="text-truncate me-2"><i class="ri-folder-fill me-2 text-warning"></i>{{ $f->name }}</span>
                            <span class="badge bg-secondary text-white rounded-pill font-monospace fw-bold">{{ $f->documents->count() }}</span>
                        </button>
                    @empty
                        <div class="text-muted fw-bold small py-3 text-center">Henüz özel klasör oluşturulmadı.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Documents Table & Filters -->
        <div class="col-lg-9">
            <!-- Filter Bar -->
            <div class="card doc-card-modern-custom p-3.5 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-7">
                        <input type="text" id="searchInput" class="form-control custom-filter-input-projdoc py-2.5" placeholder="Doküman adı, dosya kodu veya açıklama ara...">
                    </div>
                    <div class="col-md-5">
                        <select id="statusFilter" class="form-select custom-filter-input-projdoc py-2.5">
                            <option value="">Tüm Durumlar (Tümü)</option>
                            <option value="approved">✅ Onaylı (Approved)</option>
                            <option value="review">⏳ İnceleniyor (Review)</option>
                            <option value="draft">📝 Taslak (Draft)</option>
                            <option value="old">📂 Eski Sürüm (Archived)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card doc-card-modern-custom overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table custom-projdoc-table align-middle mb-0" id="documentsTable">
                        <thead style="background-color: #0f172a !important;">
                            <tr style="background-color: #0f172a !important;">
                                <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Dosya / Kod</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Versiyon</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Durum</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Disiplin</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Yükleyen</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tarih</th>
                                <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $d)
                                <tr class="doc-row" data-folder-id="{{ $d->folder_id }}" data-name="{{ strtolower($d->name . ' ' . ($d->code ?? '')) }}" data-status="{{ $d->status }}">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle bg-danger bg-opacity-15 text-danger d-flex align-items-center justify-content-center fw-extrabold" style="width: 40px; height: 40px;">
                                                <i class="ri-file-pdf-fill fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-extrabold mb-0 text-dark fs-6">
                                                    <a href="{{ route('projects.documents.show', [$project->id, $d->id]) }}" target="_blank" class="text-dark text-decoration-none hover-warning">
                                                        {{ $d->name }}
                                                    </a>
                                                </h6>
                                                @if($d->code)<span class="badge bg-dark text-warning font-monospace fw-bold" style="font-size: 10.5px;">{{ $d->code }}</span>@endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark text-warning border border-warning font-monospace px-2.5 py-1 rounded-pill fw-bold">{{ $d->version ?? 'V1.0' }}</span>
                                    </td>
                                    <td>
                                        @php $st = strtolower($d->status); @endphp
                                        @if($st === 'approved')
                                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">✅ Onaylı</span>
                                        @elseif($st === 'review')
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ İnceleniyor</span>
                                        @elseif($st === 'draft')
                                            <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📝 Taslak</span>
                                        @else
                                            <span class="badge bg-dark text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">{{ $d->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">{{ $d->discipline ?? 'Genel' }}</span>
                                    </td>
                                    <td><span class="fw-bold text-dark"><i class="ri-user-3-fill me-1 text-primary"></i>{{ $d->uploader?->name ?? 'Sistem' }}</span></td>
                                    <td><span class="fw-extrabold font-monospace text-dark">{{ $d->updated_at->format('d.m.Y') }}</span></td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('projects.documents.viewer', [$project->id, $d->id]) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold" title="Önizle">
                                                <i class="ri-eye-fill me-1"></i>Önizle
                                            </a>
                                            <a href="{{ route('projects.documents.history', [$project->id, $d->id]) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Geçmiş & Sürümler">
                                                <i class="ri-history-line"></i>
                                            </a>
                                            <a href="{{ route('projects.documents.show', [$project->id, $d->id]) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="İndir">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted fw-bold">
                                        <i class="ri-folder-open-fill fs-1 d-block mb-2 text-warning"></i>
                                        Bu projede henüz yüklü bir doküman bulunmamaktadır.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <small class="text-muted fw-bold">Toplam {{ $documents->total() }} evrak gösteriliyor</small>
                <div>{{ $documents->links() }}</div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const folderButtons = document.querySelectorAll('.folder-item-btn-custom');
    const rows = document.querySelectorAll('.doc-row');
    let activeFolderId = '';

    function filterDocs() {
        const query = searchInput.value.trim().toLowerCase();
        const status = statusFilter.value;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const rowStatus = row.getAttribute('data-status');
            const rowFolder = row.getAttribute('data-folder-id');

            const matchesSearch = !query || name.includes(query);
            const matchesStatus = !status || rowStatus === status;
            const matchesFolder = !activeFolderId || rowFolder === activeFolderId;

            row.style.display = (matchesSearch && matchesStatus && matchesFolder) ? '' : 'none';
        });
    }

    if(searchInput) searchInput.addEventListener('input', filterDocs);
    if(statusFilter) statusFilter.addEventListener('change', filterDocs);

    folderButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            folderButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeFolderId = this.getAttribute('data-folder-id');
            filterDocs();
        });
    });
});
</script>
@endpush
