@extends('layouts.master')

@section('title', 'Şantiye Görev & İmalat Takip Portalı')
@section('topbar-title', 'Saha & İmalat - Görev Portalı')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GLASSMORPHIC THEME */
    .taskidx-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #78350f 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .taskidx-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: taskGlow 4s linear infinite;
    }

    @keyframes taskGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .taskidx-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .custom-kpi-card-taskidx {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-kpi-card-taskidx:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .task-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .custom-input-taskidx {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-taskidx:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
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
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="taskidx-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill taskidx-badge-pill">
                        <i class="ri-task-line text-warning fs-5"></i> SAHA GÖREV YÖNETİMİ & AGILE İMALAT LİSTESİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        {{ $stats['total'] }} Kayıtlı İş Paketi
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Şantiye Görev & İmalat Takip Portalı</h2>
                <p class="mb-0 fs-6" style="color: #fef08a; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye sahası mühendis görevleri, taşeron iş paketleri, bitiş tarihi kontrolleri ve atanan personel imalat listesi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('apps-kanban') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-kanban-view-2 fs-5"></i>Kanban Görünümü
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Görev Ekle
                    </a>
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

    <!-- 4 SUMMARY KPI CARDS BAR -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskidx h-100 border-start border-warning border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM GÖREV</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $stats['total'] }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="task-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-task-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-list-check-2 me-1"></i>Kayıtlı İş Paketleri</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Tüm Listeler</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskidx h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">AKTİF İMALATLAR</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $stats['active'] }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="task-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-hammer-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-loader-4-line me-1"></i>Saha İşlemleri</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Devam Ediyor</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskidx h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TAMAMLANAN</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $stats['completed'] }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="task-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-line me-1"></i>Teslim Alınan</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Onaylandı</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-taskidx h-100 border-start border-danger border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">ACİL SAHA GÖREVLERİ</span>
                        <h2 class="fw-extrabold mb-0 text-danger font-monospace">{{ $stats['urgent'] }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="task-stat-icon bg-danger-subtle text-danger" style="background: rgba(239, 68, 68, 0.12);">
                        <i class="ri-error-warning-fill fs-2" style="color: #ef4444 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-alarm-warning-line me-1"></i>Kritik Öncelik</small>
                    <span class="badge bg-danger-subtle text-danger fw-bold">Müdahale Et</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="card border rounded-4 shadow-sm overflow-hidden bg-white mb-4" style="border-color: #cbd5e1 !important;">
        <div class="card-header bg-dark text-white p-3.5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-list-check-2 text-warning fs-5"></i>Şantiye Görev & İmalat Listesi
            </h5>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-light active" id="btnListView" onclick="setView('list')"><i class="ri-list-check me-1"></i>Liste Görünümü</button>
                    <button type="button" class="btn btn-outline-light" id="btnGridView" onclick="setView('grid')"><i class="ri-grid-fill me-1"></i>Kart Görünümü</button>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- FILTER BAR -->
            <form method="GET" action="{{ route('tasks.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control custom-input-taskidx py-2.5" placeholder="Görev adı veya açıklama ara..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select custom-input-taskidx py-2.5" onchange="this.form.submit()">
                        <option value="">Tüm Durumlar</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🔵 Aktif</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🟢 Tamamlanan</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⚪ Bekleyen</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-select custom-input-taskidx py-2.5" onchange="this.form.submit()">
                        <option value="">Tüm Öncelikler</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>🔴 Acil</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🟡 Yüksek</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🔵 Orta</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>🟢 Düşük</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-filter-3-line me-1"></i>Filtrele
                    </button>
                </div>
            </form>

            <!-- LIST VIEW -->
            <div id="list-view" class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="dark-table-header text-uppercase">
                        <tr>
                            <th class="ps-4">Görev Başlığı & Detay</th>
                            <th>Öncelik</th>
                            <th>Atanan Personel</th>
                            <th>Hedef Tarih</th>
                            <th>İlerleme</th>
                            <th>Durum</th>
                            <th class="pe-4 text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-extrabold text-dark fs-6 mb-0.5">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-dark text-decoration-none hover-warning">
                                            {{ $task->title }}
                                        </a>
                                    </div>
                                    <small class="text-secondary fw-semibold d-inline-block text-truncate" style="max-width: 280px;">
                                        {{ $task->description }}
                                    </small>
                                </td>
                                <td>
                                    @if($task->priority === 'urgent')
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace" style="font-size: 11px;">🔴 ACİL</span>
                                    @elseif($task->priority === 'high')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace" style="font-size: 11px;">🟡 YÜKSEK</span>
                                    @elseif($task->priority === 'medium')
                                        <span class="badge bg-info text-white rounded-pill px-3 py-1 font-monospace" style="font-size: 11px;">🔵 ORTA</span>
                                    @else
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace" style="font-size: 11px; background: #047857;">🟢 DÜŞÜK</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-xs bg-dark font-monospace text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 11px;">
                                            {{ strtoupper(substr($task->assignedTo->name ?? $task->assigned_to ?? 'N', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $task->assignedTo->name ?? $task->assigned_to ?? 'Atanmamış' }}</div>
                                            <small class="text-muted">Saha Sorumlusu</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-monospace fw-bold text-dark" style="font-size: 11.5px;">
                                    <div>{{ optional(\Carbon\Carbon::parse($task->due_date))->format('d.m.Y') }}</div>
                                    @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 font-monospace" style="font-size: 10px;">🚨 Gecikmiş İmalat</span>
                                    @endif
                                </td>
                                <td style="min-width: 140px;">
                                    <div class="d-flex justify-content-between small fw-bold mb-1">
                                        <span class="text-primary font-monospace">%{{ $task->completion_rate ?? 0 }}</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-emerald" style="width: {{ $task->completion_rate ?? 0 }}%; background: #047857;"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($task->status === 'completed')
                                        <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #047857; font-size: 11px;">
                                            <i class="ri-checkbox-circle-fill me-1"></i>Tamamlandı
                                        </span>
                                    @elseif($task->status === 'active')
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #2563eb; font-size: 11px;">
                                            <i class="ri-loader-4-line me-1"></i>Aktif İmalat
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                            <i class="ri-time-line me-1"></i>Bekliyor
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="Detay">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-warning rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this, 'Görevi Silmek İstediğinize Emin Misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5 fw-bold">
                                    <i class="ri-task-line display-4 text-muted d-block mb-2"></i>
                                    Henüz kayıtlı saha görevi bulunmamaktadır.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- GRID VIEW -->
            <div id="grid-view" class="row g-3" style="display: none;">
                @foreach($tasks as $task)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border rounded-4 p-4 h-100 bg-white shadow-sm" style="border-color: #cbd5e1 !important;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                @if($task->priority === 'urgent')
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace" style="font-size: 10px;">🔴 ACİL</span>
                                @elseif($task->priority === 'high')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace" style="font-size: 10px;">🟡 YÜKSEK</span>
                                @else
                                    <span class="badge bg-info text-white rounded-pill px-3 py-1 font-monospace" style="font-size: 10px;">🔵 NORMAL</span>
                                @endif
                                <span class="badge bg-dark font-monospace text-warning">#TSK-{{ $task->id }}</span>
                            </div>
                            <h6 class="fw-extrabold text-dark mb-1">
                                <a href="{{ route('tasks.show', $task->id) }}" class="text-dark text-decoration-none">
                                    {{ $task->title }}
                                </a>
                            </h6>
                            <p class="text-secondary small mb-3 text-truncate">{{ $task->description }}</p>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span class="text-muted">İlerleme Oranı</span>
                                    <span class="text-primary font-monospace">%{{ $task->completion_rate ?? 0 }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-emerald" style="width: {{ $task->completion_rate ?? 0 }}%; background: #047857;"></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2.5 border-top">
                                <span class="badge bg-dark font-monospace text-warning">{{ $task->assignedTo->name ?? $task->assigned_to ?? 'Atanmamış' }}</span>
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold" style="font-size: 11px;">Detay Gör</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-3">
                {{ $tasks->withQueryString()->links() }}
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function setView(view) {
    if (view === 'list') {
        document.getElementById('list-view').style.display = '';
        document.getElementById('grid-view').style.display = 'none';
        document.getElementById('btnListView').classList.add('active');
        document.getElementById('btnGridView').classList.remove('active');
    } else {
        document.getElementById('list-view').style.display = 'none';
        document.getElementById('grid-view').style.display = '';
        document.getElementById('btnGridView').classList.add('active');
        document.getElementById('btnListView').classList.remove('active');
    }
}
</script>
@endpush