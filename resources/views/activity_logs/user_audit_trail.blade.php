@extends('layouts.master')

@section('title', 'Kullanıcı Aktivite İzleri')
@section('topbar-title', 'Aktivite Günlüğü - Kullanıcı İzleri')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & SAPPHIRE GLASSMORPHIC THEME */
    .usr-trail-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(37, 99, 235, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .usr-trail-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #10b981, #f59e0b, #2563eb);
        background-size: 300% 100%;
        animation: usrTrailGlow 4s linear infinite;
    }

    @keyframes usrTrailGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .usr-trail-badge-pill {
        background: rgba(37, 99, 235, 0.25) !important;
        border: 1px solid rgba(37, 99, 235, 0.5) !important;
        color: #bfdbfe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Spec Cards */
    .usr-trail-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .usr-trail-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .usr-trail-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Table Container */
    .usr-trail-table-card {
        border-radius: 20px !important;
        padding: 1.8rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 2rem;
    }

    .dark-table-header {
        background-color: #0f172a !important;
        color: #ffffff !important;
    }

    .dark-table-header th {
        color: #ffffff !important;
        font-weight: 800 !important;
        border: none !important;
        padding: 14px 16px !important;
        font-size: 0.85rem !important;
        letter-spacing: 0.5px !important;
    }

    .custom-input-trail {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="usr-trail-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Aktivite Günlüğüne Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill usr-trail-badge-pill">
                        <i class="ri-user-search-fill text-warning fs-5"></i> KULLANICI DENETİM İZLERİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        AUDIT TRAIL #{{ $user->id }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">
                    {{ $user->name }} — Aktivite Günlüğü
                </h2>
                <p class="mb-0 fs-6" style="color: #bfdbfe; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    <i class="ri-mail-line me-1"></i><strong>{{ $user->email }}</strong> kullanıcısının sistem üzerinde gerçekleştirdiği tüm ekleme, güncelleme, silme ve oturum eylemleri.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3.5 rounded-4 border text-start" style="background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255, 255, 255, 0.2) !important;">
                    <small class="text-warning fw-extrabold d-block mb-1 fs-6"><i class="ri-shield-user-fill me-1"></i>Güvenlik Denetimi:</small>
                    <span class="text-white small d-block" style="line-height: 1.5; font-weight: 500;">
                        Tüm işlemler IP adresi ve zaman damgasıyla saniye hassasiyetinde kayıt altındadır.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card usr-trail-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM İŞLEM SAYISI</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ number_format($activities->total()) }} <small class="fs-6 text-muted">Eylem</small></h2>
                    </div>
                    <div class="usr-trail-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-history-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-list-check-2 me-1"></i>Kullanıcı Kayıtları</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Audit Log</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card usr-trail-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SON İŞLEM ZAMANI</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace" style="font-size: 1.3rem;">
                            {{ $activities->first() ? \Carbon\Carbon::parse($activities->first()->created_at)->diffForHumans() : 'Kayıt Yok' }}
                        </h2>
                    </div>
                    <div class="usr-trail-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-time-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-calendar-line me-1"></i>Güncel Hareket</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Canlı İzleme</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card usr-trail-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">GÜVENLİK DURUMU</span>
                        <h2 class="fw-extrabold mb-0 text-info">Güvenli 🟢</h2>
                    </div>
                    <div class="usr-trail-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-shield-keyhole-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-global-line me-1"></i>IP Kontrolü Pasif</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Doğrulanmış</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card usr-trail-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">KULLANICI ROLÜ</span>
                        <h2 class="fw-extrabold mb-0 text-warning fs-5">{{ $user->role ?? 'Saha Yetkilisi' }}</h2>
                    </div>
                    <div class="usr-trail-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-user-star-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-lock-2-line me-1"></i>Sistem İzinleri</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Aktif Üye</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="card border rounded-4 p-3.5 mb-4" style="border-color: #cbd5e1 !important;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text custom-input-trail border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                        <i class="ri-search-2-line text-muted fs-5"></i>
                    </span>
                    <input type="text" id="trailSearchInput" class="form-control custom-input-trail border-start-0 py-2" placeholder="İşlem türü, model veya açıklama içi ara..." style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <span class="badge bg-dark font-monospace text-warning px-3 py-2 rounded-pill fw-bold">
                    Gösterilen: {{ $activities->firstItem() ?? 0 }} - {{ $activities->lastItem() ?? 0 }} / Toplam: {{ $activities->total() }}
                </span>
            </div>
        </div>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="usr-trail-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="trailTable">
                <thead class="dark-table-header text-uppercase">
                    <tr>
                        <th class="ps-4">Tarih & Zaman</th>
                        <th>İşlem Türü</th>
                        <th>Modül / Model</th>
                        <th>IP Adresi</th>
                        <th>Açıklama Detayları</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $act)
                        <tr>
                            <td class="ps-4 font-monospace text-dark fw-bold" style="font-size: 0.9rem;">
                                <i class="ri-calendar-event-line me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($act->created_at)->format('d.m.Y — H:i:s') }}
                            </td>
                            <td>
                                @php
                                    $actName = strtoupper($act->action ?? 'İşlem');
                                    $badgeClass = 'bg-primary bg-opacity-10 text-primary border-primary';
                                    if(str_contains($actName, 'CREATE') || str_contains($actName, 'EKL')) {
                                        $badgeClass = 'bg-success bg-opacity-10 text-success border-success';
                                    } elseif(str_contains($actName, 'DELETE') || str_contains($actName, 'SİL')) {
                                        $badgeClass = 'bg-danger bg-opacity-10 text-danger border-danger';
                                    } elseif(str_contains($actName, 'UPDATE') || str_contains($actName, 'GÜNCEL')) {
                                        $badgeClass = 'bg-info bg-opacity-10 text-info border-info';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} border rounded-pill px-3 py-1 text-uppercase fw-extrabold font-monospace" style="font-size: 11px;">
                                    {{ $actName }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-dark font-monospace text-warning px-2.5 py-1 rounded-pill fw-bold">
                                    {{ class_basename($act->model_type ?? 'Genel Sistem') }}
                                </span>
                            </td>
                            <td class="font-monospace text-muted fw-bold" style="font-size: 0.85rem;">
                                <i class="ri-global-line me-1 text-secondary"></i>{{ $act->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="text-dark fw-semibold" style="font-size: 0.9rem;">
                                {{ $act->description ?? 'Sistem kullanıcısı tarafından standart işlem gerçekleştirildi.' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted fw-bold">
                                <i class="ri-inbox-archive-line display-4 text-muted d-block mb-2"></i>
                                Bu kullanıcı için kayıtlı aktivite izi bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <span class="text-muted small fw-bold">Pergel Aktivite İzleme & Güvenlik Portalı</span>
            <div>
                {{ $activities->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('trailSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#trailTable tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
