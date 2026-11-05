@extends('layouts.master')

@section('title', 'Şantiye & Şirket İçi Mesajlaşma Merkezi')
@section('topbar-title', 'Haberleşme - Mesajlar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & INDIGO/SAPPHIRE GLASSMORPHIC THEME */
    .msg-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #3730a3 50%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(59, 130, 246, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .msg-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b, #3b82f6);
        background-size: 300% 100%;
        animation: msgGlow 4s linear infinite;
    }

    @keyframes msgGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .msg-badge-pill {
        background: rgba(59, 130, 246, 0.25) !important;
        border: 1px solid rgba(59, 130, 246, 0.5) !important;
        color: #bfdbfe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .msg-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .msg-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .msg-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Message Item Card */
    .msg-item-card {
        border-radius: 18px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .msg-item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: #2563eb !important;
    }

    .custom-input-msg {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-msg:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
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
    <div class="msg-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill msg-badge-pill">
                        <i class="ri-mail-send-fill text-warning fs-5"></i> DAHİLİ E-POSTA & MESAJLAŞMA
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        GELEN KUTUSU
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Şantiye & Şirket İçi Mesajlaşma Merkezi</h2>
                <p class="mb-0 fs-6" style="color: #bfdbfe; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Proje yöneticileri, şantiye şefleri, taşeronlar ve muhasebe departmanları arası anlık dahili mesajlaşma ve resmi bildirim akışı.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                        <i class="ri-mail-add-fill fs-5"></i>+ Yeni Mesaj Gönder
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
        $msgCount = isset($messages) && is_object($messages) && method_exists($messages, 'total') ? $messages->total() : (isset($messages) ? count($messages) : 0);
        $unreadCount = isset($messages) && is_object($messages) ? count($messages->where('is_read', false)) : 0;
    @endphp

    <!-- 4 SUMMARY KPI CARDS BAR -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card msg-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">GELEN MESAJLAR</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $msgCount }} <small class="fs-6 text-muted">Mesaj</small></h2>
                    </div>
                    <div class="msg-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-mail-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-inbox-line me-1"></i>Gelen Kutusu Arşivi</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Dahili Akış</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card msg-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">OKUNMAMIŞ MESAJLAR</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $unreadCount }} <small class="fs-6 text-muted">Yeni</small></h2>
                    </div>
                    <div class="msg-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-mail-unread-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-notification-3-line me-1"></i>Bekleyen Okumalar</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Yüksek Öncelik</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card msg-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">GÖNDERİLEN MESAJLAR</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">Aktif</h2>
                    </div>
                    <div class="msg-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-send-plane-2-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-line me-1"></i>İletildi</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Giden Kutusu</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card msg-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">GÜVENLİ HABERLEŞME</span>
                        <h2 class="fw-extrabold mb-0 text-info">SSL 256</h2>
                    </div>
                    <div class="msg-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-shield-user-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-lock-line me-1"></i>Uçtan Uca Şifreli</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Korumalı</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="card border rounded-4 p-3.5 mb-4" style="border-color: #cbd5e1 !important;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text custom-input-msg border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                        <i class="ri-search-2-line text-muted fs-5"></i>
                    </span>
                    <input type="text" id="msgSearchInput" class="form-control custom-input-msg border-start-0 py-2" placeholder="Mesaj konusu veya içerikte hızlı ara..." style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <button class="btn btn-emerald text-white rounded-pill px-4 py-2 fw-extrabold shadow-sm d-inline-flex align-items-center gap-2" style="background: #047857; border: none;" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                    <i class="ri-mail-add-line fs-5"></i> Yeni Mesaj Oluştur
                </button>
            </div>
        </div>
    </div>

    <!-- MESSAGES LIST CONTAINER -->
    <div class="row g-3 mb-4" id="messagesList">
        @if(isset($messages) && count($messages) > 0)
            @foreach($messages as $message)
                <div class="col-12 msg-card-col">
                    <div class="msg-item-card p-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-md bg-dark font-monospace text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($message->fromUser->name ?? $message->subject ?? 'M', 0, 2)) }}
                                </div>

                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <h6 class="fw-extrabold text-dark mb-0 fs-6 msg-subject-text">{{ $message->subject ?: 'Konusuz Bildirim' }}</h6>
                                        @if(!$message->is_read)
                                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-0.5 rounded-pill font-monospace" style="font-size: 10px;">OKUNMADI</span>
                                        @endif
                                    </div>
                                    <p class="text-secondary small mb-1 msg-body-text" style="line-height: 1.5;">
                                        {{ Str::limit($message->body ?? '', 160) }}
                                    </p>
                                    <small class="text-muted font-monospace"><i class="ri-user-3-line me-1"></i>Gönderen: {{ $message->fromUser->name ?? 'Şantiye Yönetimi' }}</small>
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-md-end justify-content-between gap-2 flex-shrink-0">
                                <small class="text-muted font-monospace fw-bold"><i class="ri-time-line me-1"></i>{{ optional($message->created_at)->diffForHumans() }}</small>

                                <div class="d-flex gap-2">
                                    @if(!$message->is_read)
                                        <form method="POST" action="{{ route('messages.read', $message->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" type="submit">
                                                <i class="ri-check-double-line me-1"></i>Okundu
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('messages.reply', $message->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                        <i class="ri-reply-line me-1"></i>Yanıtla
                                    </a>

                                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}" class="d-inline" onsubmit="return confirmDelete(this, 'Mesajı Silmek İstediğinize Emin Misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-circle p-2" type="submit" title="Sil">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card border rounded-4 p-5 text-center bg-white shadow-sm" style="border-color: #cbd5e1 !important;">
                    <i class="ri-mail-open-line display-3 text-muted mb-3 d-block"></i>
                    <h5 class="fw-extrabold text-dark mb-2">Henüz Kayıtlı Mesajınız Bulunmuyor</h5>
                    <p class="text-secondary small mb-4" style="max-width: 500px; margin: 0 auto;">
                        Şantiye şefleri, taşeron temsilcileri ve muhasebe birimi ile dahili mesajlaşma başlatmak için yeni mesaj oluşturabilirsiniz.
                    </p>
                    <div>
                        <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                            <i class="ri-mail-add-fill me-1 fs-5"></i> Yeni Mesaj Gönderin
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if(isset($messages) && is_object($messages) && method_exists($messages, 'links'))
        <div class="d-flex justify-content-end mb-4">
            {{ $messages->links() }}
        </div>
    @endif

</div>

<!-- NEW MESSAGE QUICK MODAL -->
<div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #3730a3 100%); border-bottom: 3px solid #f59e0b;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newMessageModalLabel">
                    <i class="ri-mail-add-fill text-warning fs-4"></i> Yeni Dahili Mesaj Gönder
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            
            <form method="POST" action="{{ route('messages.index') }}">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label for="subject" class="form-label fw-extrabold text-dark fs-6">MESAJ KONUSU <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control custom-input-msg py-2.5" placeholder="Örn: Blok B-2 İmalat Hakediş Onayı Hakkında" required>
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label fw-extrabold text-dark fs-6">MESAJ METNİ VE DETAYLAR <span class="text-danger">*</span></label>
                        <textarea name="body" id="body" class="form-control custom-input-msg py-2" rows="6" placeholder="Mesajınızı detaylıca yazınız..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-send-plane-fill me-1"></i> Mesajı Gönder
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
    const searchInput = document.getElementById('msgSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('.msg-card-col').forEach(col => {
                const subject = col.querySelector('.msg-subject-text')?.textContent.toLowerCase() || '';
                const body = col.querySelector('.msg-body-text')?.textContent.toLowerCase() || '';
                if (subject.indexOf(filter) > -1 || body.indexOf(filter) > -1) {
                    col.style.display = '';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    }

    // Modal safety reparent
    const modalEl = document.getElementById('newMessageModal');
    if (modalEl && modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
});
</script>
@endpush
