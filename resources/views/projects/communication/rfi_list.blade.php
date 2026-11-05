@extends('layouts.master')

@section('title', 'RFI (Bilgi Talebi) Yönetimi — ' . $project->name)

@section('topbar-title', 'Projeler - RFI Yönetimi')

@push('styles')
<style>
    /* Hero Banner */
    .comm-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .comm-hero-banner,
    :root:not([data-bs-theme="dark"]) .comm-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #06b6d4 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .comm-hero-banner {
        background: linear-gradient(135deg, rgba(2, 132, 199, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Cards */
    .comm-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .comm-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .comm-kpi-card,
    :root:not([data-bs-theme="dark"]) .comm-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .comm-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .comm-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .comm-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .comm-glass-card,
    :root:not([data-bs-theme="dark"]) .comm-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .comm-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="comm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('projects.communication.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>İletişim Paneli
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-question-answer-line me-1"></i>Request For Information
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">RFI Yönetimi — {{ $project->name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Saha teknik sorularını, mimari çelişkileri ve idare bilgi taleplerini yönetin.</p>
        </div>

        <button type="button" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createRfiModal" style="background:#0284c7;">
            <i class="ri-add-circle-line me-1"></i>Yeni RFI Oluştur
        </button>
    </div>
</div>

<!-- 4 Top KPI Cards -->
@php
    $totalRfis = $rfis->count();
    $openRfis = $rfis->where('status', 'open')->count();
    $pendingRfis = $rfis->where('status', 'pending')->count();
    $closedRfis = $rfis->where('status', 'closed')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam RFI Sayısı</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ $totalRfis }}</h2>
                </div>
                <div class="comm-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-question-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Açık Talepler</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $openRfis }}</h2>
                </div>
                <div class="comm-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">İncelemede / Bekliyor</span>
                    <h2 class="fw-extrabold mb-0 text-info">{{ $pendingRfis }}</h2>
                </div>
                <div class="comm-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-loader-4-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Yanıtlandı / Kapalı</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $closedRfis }}</h2>
                </div>
                <div class="comm-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="comm-glass-card mb-4 p-3">
    <div class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="ri-search-line"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="RFI no, konu veya kişi ara...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="statusFilter" class="form-select">
                <option value="">Tüm Durumlar</option>
                <option value="open">Açık</option>
                <option value="pending">Bekliyor</option>
                <option value="closed">Kapalı</option>
            </select>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="clearFilters()">
                <i class="ri-refresh-line me-1"></i>Filtreleri Sıfırla
            </button>
        </div>
    </div>
</div>

<!-- RFI Table Container -->
<div class="comm-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-search-line text-info me-2"></i>Kayıtlı RFI Talepleri
        </h5>
        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">
            {{ $totalRfis }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table id="rfisTable" class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">RFI No</th>
                    <th>Konu / Teknik Başlık</th>
                    <th>Atanan Kişi / Mühendis</th>
                    <th>Durum</th>
                    <th>Son Tarih</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rfis as $rfi)
                    <tr data-id="{{ $rfi->id }}">
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold">
                                {{ $rfi->rfi_no }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block mb-1">{{ $rfi->subject }}</strong>
                            <small class="text-muted d-block text-truncate" style="max-width: 320px;">{{ $rfi->description }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-dark d-block"><i class="ri-user-follow-line me-1 text-primary"></i>{{ $rfi->assigned_to ?: 'Atanmadı' }}</span>
                        </td>
                        <td>
                            @if($rfi->status === 'open')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Açık</span>
                            @elseif($rfi->status === 'pending')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">İncelemede</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Kapalı</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted"><i class="ri-calendar-line me-1"></i>{{ $rfi->due_date ? $rfi->due_date->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('projects.communication.rfis.edit', ['project' => $project->id, 'rfi' => $rfi->id]) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('projects.communication.rfis.destroy', ['project' => $project->id, 'rfi' => $rfi->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu RFI kaydını silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="ri-question-line fs-1 d-block mb-2 text-info"></i>
                            Henüz kayıtlı RFI bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create RFI Modal -->
<div class="modal fade" id="createRfiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white p-3">
                <h5 class="modal-title fw-bold text-white"><i class="ri-add-circle-line me-2"></i>Yeni RFI (Bilgi Talebi) Oluştur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.communication.rfis.store', ['project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">RFI Konusu / Teknik Başlık <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-question-line text-info"></i></span>
                            <input type="text" name="subject" class="form-control" placeholder="Örn: Bodrum Kat Perde Donatı Kesit Çelişkisi" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Atanacak Mühendis / Sorumlu E-Posta</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-mail-line text-warning"></i></span>
                                <input type="email" name="assigned_to" class="form-control" placeholder="statik.muhendis@pergel.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Son Yanıt Tarihi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Teknik Açıklama & Soru Detayları</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Proje pafta numarası, çelişen kot veya teknik sorularınız..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Dosya Ekleri (PDF, DWG, Görsel)</label>
                        <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-info text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#0284c7;">
                        <i class="ri-save-line me-1"></i>RFI Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchInput')?.addEventListener('keyup', filterRfis);
document.getElementById('statusFilter')?.addEventListener('change', filterRfis);

function filterRfis() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const st = document.getElementById('statusFilter').value.toLowerCase();
    document.querySelectorAll('#rfisTable tbody tr').forEach(tr => {
        const text = tr.textContent.toLowerCase();
        const statusMatch = !st || text.includes(st);
        const textMatch = !q || text.includes(q);
        tr.style.display = (statusMatch && textMatch) ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterRfis();
}
</script>
@endpush
