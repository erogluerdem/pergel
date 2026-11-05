@extends('layouts.master')

@section('title', 'Submittal (Onay Dosyası) Yönetimi — ' . $project->name)

@section('topbar-title', 'Projeler - Submittals')

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
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .comm-hero-banner {
        background: linear-gradient(135deg, rgba(217, 119, 6, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
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
                    <i class="ri-folder-shield-2-line me-1"></i>Material & Shop Drawing Submittal
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Submittal Yönetimi — {{ $project->name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Malzeme onay dosyalarını, imalat çizimlerini ve numune onaylarını yönetin.</p>
        </div>

        <button type="button" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newSubmittalModal">
            <i class="ri-add-circle-line me-1"></i>Yeni Submittal Ekle
        </button>
    </div>
</div>

<!-- 4 Top KPI Cards -->
@php
    $totalSubs = $subs->total();
    $pendingSubs = $subs->where('status', 'pending')->count();
    $approvedSubs = $subs->where('status', 'approved')->count();
    $rejectedSubs = $subs->where('status', 'rejected')->count();
@endphp
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Submittal</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ $totalSubs }}</h2>
                </div>
                <div class="comm-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-folder-open-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onay Bekliyor</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $pendingSubs }}</h2>
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
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onaylandı</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $approvedSubs }}</h2>
                </div>
                <div class="comm-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="comm-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Revize / Reddedildi</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $rejectedSubs }}</h2>
                </div>
                <div class="comm-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-close-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="comm-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('projects.communication.submittals', $project->id) }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Açıklama, No veya Gönderen..." value="{{ $filters['search'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tüm Durumlar</option>
                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                    <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="due_date_from" class="form-control" value="{{ $filters['due_date_from'] ?? '' }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-warning text-dark rounded-pill flex-fill fw-bold">
                    <i class="ri-filter-3-line me-1"></i>Filtrele
                </button>
                <a href="{{ route('projects.communication.submittals', $project->id) }}" class="btn btn-outline-secondary rounded-circle" title="Temizle">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Submittal Table -->
<div class="comm-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-folder-zip-line text-warning me-2"></i>Kayıtlı Malzeme ve İmalat Onay Dosyaları
        </h5>
        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">
            {{ $subs->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Submittal No</th>
                    <th>Açıklama / Malzeme Adı</th>
                    <th>Gönderen Firma</th>
                    <th>Onaylayıcılar</th>
                    <th>Durum</th>
                    <th>Son Tarih</th>
                    <th class="text-end pe-4">İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subs as $sub)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold">
                                {{ $sub->submittal_no }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $sub->description }}</strong>
                        </td>
                        <td>
                            <span class="fw-bold text-dark"><i class="ri-building-line me-1 text-muted"></i>{{ $sub->submitted_by ?: 'Firma Belirtilmedi' }}</span>
                        </td>
                        <td>
                            @if(is_array($sub->reviewers))
                                @foreach($sub->reviewers as $rev)
                                    <span class="badge bg-light text-dark border rounded-pill me-1 small">{{ $rev }}</span>
                                @endforeach
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($sub->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($sub->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Bekliyor</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted"><i class="ri-calendar-line me-1"></i>{{ $sub->due_date ? $sub->due_date->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            @if($sub->status === 'pending')
                                <form action="{{ route('projects.communication.submittals.review', ['project' => $project->id, 'submittal' => $sub->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approved">
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="ri-check-line me-1"></i>Onayla
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">İşlem Yapıldı</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-folder-open-line fs-1 d-block mb-2 text-warning"></i>
                            Henüz kayıtlı Submittal bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $subs->total() }} kayıt gösteriliyor</small>
        <div>{{ $subs->links() }}</div>
    </div>
</div>

<!-- Create Submittal Modal -->
<div class="modal fade" id="newSubmittalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark p-3">
                <h5 class="modal-title fw-bold"><i class="ri-add-circle-line me-2"></i>Yeni Submittal (Onay Dosyası) Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.communication.submittals.store', ['project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Submittal Açıklaması / Malzeme Adı <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-file-text-line text-warning"></i></span>
                            <input type="text" name="description" class="form-control" placeholder="Örn: Alüminyum Giydirme Cephe Profil ve Isıcam Numune Dosyası" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Onaylayıcı E-Postaları (Virgülle Ayrılmış)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-user-shared-line text-info"></i></span>
                                <input type="text" name="reviewers" class="form-control" placeholder="mimari@pergel.com, statik@pergel.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Son Onay Tarihi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Onay Dokümanları & Kataloglar (PDF, DWG, Görsel)</label>
                        <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Submittal Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
