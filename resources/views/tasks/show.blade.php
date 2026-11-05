@extends('layouts.master')

@section('title', 'Şantiye Görev Detayı: ' . $task->title)

@push('styles')
<style>
    .taskshow-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .taskshow-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-taskshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .detail-card-task {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 taskshow-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 taskshow-badge">
                            <i class="ri-checkbox-circle-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">GÖREV KODU: #TSK-{{ $task->id }} & ŞANTİYE İMALAT DETAYI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">{{ $task->title }}</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Görev önceliği, atanan mühendis, bitiş tarihi, imalat ilerleme oranı ve hızlı durum güncelleme aksiyon merkezi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-pencil-fill fs-5"></i>Görevi Düzenle
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                            <i class="ri-arrow-left-line fs-5"></i>Geri Dön
                        </a>
                    </div>
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

    <!-- Summary KPI Cards Bar -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GÖREV ÖNCELİĞİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">
                            @if($task->priority === 'urgent')
                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 fs-6">🔴 ACİL</span>
                            @elseif($task->priority === 'high')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fs-6">🟡 YÜKSEK</span>
                            @elseif($task->priority === 'medium')
                                <span class="badge bg-info text-white rounded-pill px-3 py-1 fs-6">🔵 ORTA</span>
                            @else
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 fs-6" style="background: #16a34a;">🟢 DÜŞÜK</span>
                            @endif
                        </div>
                        <small class="text-warning fw-bold"><i class="ri-flag-2-line me-1"></i>Saha Öncelik Seviyesi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-alarm-warning-fill fs-3" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İLERLEME ORANI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">%{{ $task->completion_rate ?? 0 }}</div>
                        <small class="text-primary fw-bold"><i class="ri-line-chart-line me-1"></i>Tamamlanma Yüzdesi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-bar-chart-fill fs-3" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GÖREV DURUMU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">
                            @if($task->status === 'completed')
                                <span class="badge text-white rounded-pill px-3 py-1 fs-6" style="background: #16a34a;">🟢 TAMAMLANDI</span>
                            @elseif($task->status === 'active')
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 fs-6" style="background: #2563eb;">🔵 AKTİF İMALAT</span>
                            @elseif($task->status === 'review')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fs-6">🟡 İNCELENİYOR</span>
                            @else
                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1 fs-6">⚪ BEKLİYOR</span>
                            @endif
                        </div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-line me-1"></i>Sözleşme Aşaması</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-task-line fs-3" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskshow h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HEDEF BİTİŞ TARİHİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.3rem;">
                            {{ optional($task->due_date)->format('d.m.Y H:i') ?? '-' }}
                        </div>
                        @if($task->due_date && $task->due_date < now() && $task->status !== 'completed')
                            <small class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i>Gecikmiş İmalat</small>
                        @else
                            <small class="text-muted fw-bold"><i class="ri-calendar-event-line me-1"></i>Planlanan Tarih</small>
                        @endif
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-calendar-todo-fill fs-3" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Details Left Column -->
        <div class="col-lg-8">
            <div class="card detail-card-task shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fffbeb; color: #d97706;">
                            <i class="ri-file-text-fill fs-4"></i>
                        </div>
                        <h5 class="fw-extrabold text-dark mb-0">Görev Açıklaması & Teknik Detaylar</h5>
                    </div>
                    <span class="badge bg-dark font-monospace text-warning px-3 py-1 rounded-pill">#TSK-{{ $task->id }}</span>
                </div>

                <div class="bg-light p-4 rounded-4 border mb-4">
                    <p class="text-dark fw-medium mb-0 fs-6" style="line-height: 1.7; white-space: pre-line;">
                        {{ $task->description ?: 'Bu görev için detaylı açıklama girilmemiş.' }}
                    </p>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-extrabold text-dark mb-0">İmalat İlerleme Yüzdesi</h6>
                        <span class="font-monospace fw-extrabold text-primary fs-5">%{{ $task->completion_rate ?? 0 }}</span>
                    </div>
                    <div class="progress rounded-pill p-1 bg-light border" style="height: 16px;">
                        <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: {{ $task->completion_rate ?? 0 }}%"></div>
                    </div>
                </div>

                <!-- Timeline / Meta Info -->
                <div class="row g-3 pt-3 border-top">
                    <div class="col-md-6">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Oluşturulma Tarihi</small>
                        <div class="fw-bold text-dark font-monospace">{{ optional($task->created_at)->format('d.m.Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1">Son Güncellenme</small>
                        <div class="fw-bold text-dark font-monospace">{{ optional($task->updated_at)->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Right Column -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                <!-- Task Assignment Card -->
                <div class="card detail-card-task shadow-sm p-4">
                    <h5 class="fw-extrabold text-dark border-bottom pb-3 mb-3">Görev Sorumluları</h5>
                    
                    <div class="mb-3.5">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1.5" style="font-size: 11px;">ATANAN SORUMLU MÜHENDİS</small>
                        <div class="d-flex align-items-center gap-3 p-2.5 bg-light rounded-3 border">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-monospace fw-bold fs-6" style="width: 40px; height: 40px; background: #2563eb !important;">
                                {{ strtoupper(substr($task->assignedUser->name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-extrabold text-dark mb-0">{{ $task->assignedUser->name ?? 'Atanmamış' }}</h6>
                                <small class="text-secondary fw-semibold">Saha Yetkilisi</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1.5" style="font-size: 11px;">GÖREVİ OLUŞTURAN KULLANICI</small>
                        <div class="d-flex align-items-center gap-3 p-2.5 bg-light rounded-3 border">
                            <div class="avatar-sm bg-dark text-warning rounded-circle d-flex align-items-center justify-content-center font-monospace fw-bold fs-6" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($task->createdByUser->name ?? 'K', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-extrabold text-dark mb-0">{{ $task->createdByUser->name ?? 'Sistem Yönetimi' }}</h6>
                                <small class="text-secondary fw-semibold">Proje Yöneticisi</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons Card -->
                <div class="card detail-card-task shadow-sm p-4">
                    <h5 class="fw-extrabold text-dark border-bottom pb-3 mb-3">Hızlı Aksiyonlar</h5>

                    @if($task->status !== 'completed')
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="completion_rate" value="100">
                            <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #16a34a;">
                                <i class="ri-checkbox-circle-fill fs-5"></i>Tamamlandı İşaretle
                            </button>
                        </form>
                    @endif

                    @if($task->status === 'pending')
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #2563eb;">
                                <i class="ri-play-circle-fill fs-5"></i>Görevi Başlat
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="js-confirm-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2.5 fw-extrabold d-flex align-items-center justify-content-center gap-2">
                            <i class="ri-delete-bin-line fs-5"></i>Görevi Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
(function(){
    function ensureSweetAlert(cb){ if(window.Swal) return cb(); var s=document.createElement('script'); s.src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'; s.onload=cb; document.head.appendChild(s); }
    ensureSweetAlert(function(){
        document.querySelectorAll('form.js-confirm-delete').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                Swal.fire({
                    title: 'Görevi silmek istediğinize emin misiniz?',
                    text: 'Bu işlem geri alınamaz ve görev kaydı kalıcı olarak silinir.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Evet, Sil',
                    cancelButtonText: 'İptal'
                }).then(function(r){ if(r.isConfirmed) form.submit(); });
            });
        });
    });
})();
</script>
@endsection
