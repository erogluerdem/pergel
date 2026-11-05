@extends('layouts.master')

@section('title', 'Şantiye Notları & Saha Ajandası')
@section('topbar-title', 'Kişisel - Şantiye Notları')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GLASSMORPHIC THEME */
    .notes-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #78350f 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .notes-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: notesGlow 4s linear infinite;
    }

    @keyframes notesGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .notes-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .notes-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .notes-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .notes-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Note Card Item */
    .note-card-item {
        border-radius: 18px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .note-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: #d97706 !important;
    }

    .custom-input-note {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-note:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
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
    <div class="notes-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill notes-badge-pill">
                        <i class="ri-sticky-note-fill text-warning fs-5"></i> KİŞİSEL SAHA AJANDASI & NOT DEFTERİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        {{ isset($notes) && is_object($notes) && method_exists($notes, 'total') ? $notes->total() : (isset($notes) ? count($notes) : 0) }} Kayıtlı Not
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Şantiye Notları & Saha Ajandası</h2>
                <p class="mb-0 fs-6" style="color: #fef08a; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye toplantı kararları, malzeme teslimat hatırlatmaları, şantiye şefi özel ajandası ve anlık saha notları.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#newNoteModal">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Not Oluştur
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $noteCount = isset($notes) && is_object($notes) && method_exists($notes, 'total') ? $notes->total() : (isset($notes) ? count($notes) : 0);
        $lastNoteTime = isset($notes) && count($notes) > 0 ? optional($notes->first())->updated_at->diffForHumans() : 'Kayıt Yok';
    @endphp

    <!-- 4 SUMMARY KPI CARDS BAR -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card notes-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM NOT SAYISI</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $noteCount }} <small class="fs-6 text-muted">Kayıt</small></h2>
                    </div>
                    <div class="notes-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-sticky-note-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-book-mark-line me-1"></i>Saha Not Defteri</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Aktif Ajanda</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card notes-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SON GÜNCELLEME</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace" style="font-size: 1.3rem;">{{ $lastNoteTime }}</h2>
                    </div>
                    <div class="notes-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-history-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-time-line me-1"></i>Anlık Senkron</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Son Not</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card notes-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SAHA HATIRLATMALARI</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">Otomatik</h2>
                    </div>
                    <div class="notes-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-notification-badge-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-alarm-line me-1"></i>Kişisel Bildirimler</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Aktif</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card notes-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">KULLANICI ERİŞİMİ</span>
                        <h2 class="fw-extrabold mb-0 text-info">Özel / Gizli 🔒</h2>
                    </div>
                    <div class="notes-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-lock-password-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-user-line me-1"></i>Sadece Size Özel</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Güvenli</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="card border rounded-4 p-3.5 mb-4" style="border-color: #cbd5e1 !important;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text custom-input-note border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                        <i class="ri-search-2-line text-muted fs-5"></i>
                    </span>
                    <input type="text" id="noteSearchInput" class="form-control custom-input-note border-start-0 py-2" placeholder="Not başlığı veya içeriğinde hızlı ara..." style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <button class="btn btn-emerald text-white rounded-pill px-4 py-2 fw-extrabold shadow-sm d-inline-flex align-items-center gap-2" style="background: #047857; border: none;" data-bs-toggle="modal" data-bs-target="#newNoteModal">
                    <i class="ri-edit-line fs-5"></i> Hızlı Not Ekle
                </button>
            </div>
        </div>
    </div>

    <!-- NOTES MASONRY GRID -->
    <div class="row g-4 mb-4" id="notesGrid">
        @if(isset($notes) && count($notes) > 0)
            @foreach($notes as $note)
                <div class="col-xl-4 col-md-6 note-card-col">
                    <div class="note-card-item p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2.5 mb-3">
                                <h6 class="fw-extrabold text-dark mb-0 fs-6 d-flex align-items-center gap-2">
                                    <i class="ri-pushpin-2-fill text-warning fs-5"></i>
                                    <span class="note-title-text">{{ $note->title ?: 'Başlıksız Not' }}</span>
                                </h6>
                                <span class="badge bg-light text-dark font-monospace border rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                    {{ optional($note->updated_at)->format('d.m.Y — H:i') }}
                                </span>
                            </div>

                            <p class="text-secondary small mb-3 note-body-text" style="line-height: 1.6; white-space: pre-line;">
                                {{ Str::limit($note->body ?? '', 220) }}
                            </p>
                        </div>

                        <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
                            <small class="text-muted font-monospace fw-bold"><i class="ri-time-line me-1"></i>{{ optional($note->updated_at)->diffForHumans() }}</small>

                            <div class="d-flex gap-1">
                                <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-sm btn-outline-warning rounded-circle p-2" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this, 'Notu Silmek İstediğinize Emin Misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card border rounded-4 p-5 text-center bg-white shadow-sm" style="border-color: #cbd5e1 !important;">
                    <i class="ri-sticky-note-line display-3 text-muted mb-3 d-block"></i>
                    <h5 class="fw-extrabold text-dark mb-2">Henüz Kayıtlı Notunuz Bulunmuyor</h5>
                    <p class="text-secondary small mb-4" style="max-width: 500px; margin: 0 auto;">
                        Şantiye toplantı kararları, malzeme sipariş takip notları ve saha ajandanızı oluşturmak için hemen yeni bir not ekleyin.
                    </p>
                    <div>
                        <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" data-bs-toggle="modal" data-bs-target="#newNoteModal">
                            <i class="ri-add-circle-fill me-1 fs-5"></i> İlk Notunuzu Oluşturun
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if(isset($notes) && is_object($notes) && method_exists($notes, 'links'))
        <div class="d-flex justify-content-end mb-4">
            {{ $notes->links() }}
        </div>
    @endif

</div>

<!-- NEW NOTE QUICK MODAL -->
<div class="modal fade" id="newNoteModal" tabindex="-1" aria-labelledby="newNoteModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #78350f 100%); border-bottom: 3px solid #f59e0b;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newNoteModalLabel">
                    <i class="ri-sticky-note-fill text-warning fs-4"></i> Hızlı Not Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            
            <form method="POST" action="{{ route('notes.store') }}">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-extrabold text-dark fs-6">NOT BAŞLIĞI / KONUSU</label>
                        <input type="text" name="title" id="title" class="form-control custom-input-note py-2.5" placeholder="Örn: C-Blok 3. Kat Beton Döküm Notları">
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label fw-extrabold text-dark fs-6">NOT İÇERİĞİ VE DETAYLARI <span class="text-danger">*</span></label>
                        <textarea name="body" id="body" class="form-control custom-input-note py-2" rows="6" placeholder="Not detaylarını yazınız..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i> Notu Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('noteSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('.note-card-col').forEach(col => {
                const title = col.querySelector('.note-title-text')?.textContent.toLowerCase() || '';
                const body = col.querySelector('.note-body-text')?.textContent.toLowerCase() || '';
                if (title.indexOf(filter) > -1 || body.indexOf(filter) > -1) {
                    col.style.display = '';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    }

    // Modal safety reparent
    const modalEl = document.getElementById('newNoteModal');
    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
});
</script>
@endpush
