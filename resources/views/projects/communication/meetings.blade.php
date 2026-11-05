@extends('layouts.master')

@section('title', 'Toplantı Tutanakları — ' . $project->name)

@section('topbar-title', 'Projeler - Toplantılar')

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
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #10b981 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .comm-hero-banner {
        background: linear-gradient(135deg, rgba(4, 120, 87, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
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

    .meeting-item-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        height: 100%;
        background: #ffffff;
    }

    html[data-bs-theme="dark"] .meeting-item-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .meeting-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
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
                    <i class="ri-team-line me-1"></i>Saha & Koordinasyon Toplantıları
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Toplantı Tutanakları — {{ $project->name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Saha koordinasyon toplantılarını, alınan kararları ve eylemleri takip edin.</p>
        </div>

        <button type="button" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addMeetingModal" style="background:#10b981;">
            <i class="ri-add-circle-line me-1"></i>Yeni Toplantı Ekle
        </button>
    </div>
</div>

<!-- Filters Bar -->
<div class="comm-glass-card mb-4 p-3">
    <div class="row g-3 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="ri-search-line"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Toplantı başlığı veya katılımcı ara...">
            </div>
        </div>
        <div class="col-md-4">
            <input type="date" id="dateFilter" class="form-control">
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-outline-secondary rounded-pill w-100" onclick="clearFilters()">
                <i class="ri-refresh-line me-1"></i>Sıfırla
            </button>
        </div>
    </div>
</div>

<!-- Meetings Grid Cards -->
<div class="row g-4" id="meetingsContainer">
    @forelse($meetings as $m)
        <div class="col-md-6 col-xl-4 meeting-card" data-title="{{ strtolower($m->title) }}">
            <div class="meeting-item-card d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                            <i class="ri-calendar-event-line me-1"></i>{{ $m->meeting_date ? $m->meeting_date->format('d.m.Y') : 'Tarihsiz' }}
                        </span>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small">
                            <i class="ri-checkbox-circle-line me-1"></i>{{ $m->actionItems()->where('status','<>','closed')->count() }} Açık Görev
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">{{ $m->title }}</h5>
                    <p class="text-muted small mb-3 text-truncate-2" style="min-height: 42px;">
                        {{ Str::limit($m->notes, 120) ?: 'Toplantı notu bulunmuyor.' }}
                    </p>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1 fw-bold">Katılımcılar:</small>
                        @forelse($m->participants ?: [] as $p)
                            <span class="badge bg-light text-dark border me-1 small mb-1"><i class="ri-user-line me-1"></i>{{ $p }}</span>
                        @empty
                            <span class="text-muted small">Katılımcı belirtilmedi.</span>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 border-top border-opacity-10 d-flex justify-content-between align-items-center">
                    <a href="{{ route('projects.communication.meetings.show', ['project' => $project->id, 'meeting' => $m->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="ri-eye-line me-1"></i>İncele
                    </a>
                    <div class="btn-group gap-1">
                        <a href="{{ route('projects.communication.meetings.edit', ['project' => $project->id, 'meeting' => $m->id]) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                            <i class="ri-pencil-line"></i>
                        </a>
                        <form action="{{ route('projects.communication.meetings.destroy', ['project' => $project->id, 'meeting' => $m->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu toplantı kaydını silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="comm-glass-card py-5">
                <i class="ri-team-line fs-1 text-success d-block mb-2"></i>
                <h5 class="fw-bold text-dark">Henüz Toplantı Kaydı Bulunmuyor</h5>
                <p class="text-muted small mb-3">Yeni bir şantiye koordinasyon toplantısı eklemek için yukarıdaki butonu kullanabilirsiniz.</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Add Meeting Modal -->
<div class="modal fade" id="addMeetingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-emerald text-white p-3" style="background:#10b981;">
                <h5 class="modal-title fw-bold text-white"><i class="ri-add-circle-line me-2"></i>Yeni Toplantı Tutanağı Ekle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.communication.meetings.store', ['project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-dark">Toplantı Başlığı <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-team-line text-success"></i></span>
                                <input type="text" name="title" class="form-control" placeholder="Örn: Haftalık Saha İlerleme Toplantısı #14" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-dark">Toplantı Tarihi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                                <input type="date" name="meeting_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Katılımcı E-Postaları (Virgülle Ayrılmış)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-user-follow-line text-warning"></i></span>
                            <input type="text" name="participants" class="form-control" placeholder="proje.muduru@pergel.com, taseron.kaya@pergel.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Toplantı Kararları & Notlar</label>
                        <textarea name="notes" class="form-control" rows="5" placeholder="Görüşülen konular, alınan kararlar ve teknik notlar..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Toplantı Ekleri & Sunumlar (PDF, DOC, Görsel)</label>
                        <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#10b981;">
                        <i class="ri-save-line me-1"></i>Toplantıyı Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchInput')?.addEventListener('keyup', filterMeetings);

function filterMeetings() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.meeting-card').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    filterMeetings();
}
</script>
@endpush
