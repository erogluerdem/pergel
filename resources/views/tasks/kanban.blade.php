@extends('layouts.master')

@section('title', 'Şantiye Görev & İş Paketleri Kanban Panosu')
@section('topbar-title', 'Saha & İmalat - Kanban Panosu')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GLASSMORPHIC THEME */
    .kanban-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #78350f 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kanban-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: kanbanGlow 4s linear infinite;
    }

    @keyframes kanbanGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .kanban-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .kanban-col-card {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        padding: 18px !important;
        height: 100%;
    }

    .kanban-task-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .kanban-task-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
        border-color: #047857 !important;
    }

    .custom-kpi-card-kanban {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-kpi-card-kanban:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .kanban-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="kanban-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kanban-badge-pill">
                        <i class="ri-kanban-view-2 text-warning fs-5"></i> CANLI KANBAN İMALAT BOARD
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        AGILE SAHA SÜREÇLERİ
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Şantiye Görev & İş Paketleri Kanban Panosu</h2>
                <p class="mb-0 fs-6" style="color: #fef08a; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye kaba/ince imalatları, taşeron hakediş görevleri, İSG denetim maddeleri ve tedarik süreçlerinin canlı kanban takibi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-list-check-2 fs-5"></i>Liste Görünümü
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Görev Ekle
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        $pendingCount = is_array($tasks) && isset($tasks['pending']) ? count($tasks['pending']) : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'pending')->count() : 0);
        $activeCount = is_array($tasks) && isset($tasks['active']) ? count($tasks['active']) : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'active')->count() : 0);
        $reviewCount = is_array($tasks) && isset($tasks['review']) ? count($tasks['review']) : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'review')->count() : 0);
        $completedCount = is_array($tasks) && isset($tasks['completed']) ? count($tasks['completed']) : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'completed')->count() : 0);
    @endphp

    <!-- 4 SUMMARY KPI CARDS BAR -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-secondary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">BEKLİYOR (PENDING)</span>
                        <h2 class="fw-extrabold mb-0 text-dark font-monospace">{{ $pendingCount }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="kanban-stat-icon bg-secondary-subtle text-secondary" style="background: rgba(100, 116, 139, 0.12);">
                        <i class="ri-history-line fs-2" style="color: #64748b !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-time-line me-1"></i>Sırada Bekleyen İşler</small>
                    <span class="badge bg-secondary-subtle text-secondary fw-bold">Planlanan</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">AKTİF İMALATLAR</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $activeCount }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="kanban-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-hammer-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-loader-4-line me-1"></i>Şantiyede Devam Eden</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Canlı Saha</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">İNCELENEN & DENETİMDE</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $reviewCount }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="kanban-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-search-eye-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-check-line me-1"></i>YDK / İSG Kontrolünde</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Onay Bekliyor</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kanban h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TAMAMLANAN GÖREVLER</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $completedCount }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="kanban-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-task-fill fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Teslim Edilen İşler</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Bitti</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KANBAN MATRIX COLUMNS GRID -->
    <div class="row g-4 mb-4">
        
        <!-- COLUMN 1: BEKLİYOR -->
        <div class="col-xl-3 col-md-6">
            <div class="kanban-col-card">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-3 border-secondary">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-block" style="width: 12px; height: 12px; background: #64748b;"></span>
                        <h6 class="fw-extrabold text-dark mb-0 text-uppercase" style="letter-spacing: 0.5px;">Bekliyor</h6>
                    </div>
                    <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ $pendingCount }}</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @php $pendingTasks = is_array($tasks) && isset($tasks['pending']) ? $tasks['pending'] : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'pending') : []); @endphp
                    @forelse($pendingTasks as $task)
                        <div class="kanban-task-card p-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                @if($task->priority === 'urgent')
                                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🔴 ACİL</span>
                                @elseif($task->priority === 'high')
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🟡 YÜKSEK</span>
                                @else
                                    <span class="badge bg-info text-white rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🔵 NORMAL</span>
                                @endif
                                <small class="text-muted font-monospace fw-bold">#TSK-{{ $task->id }}</small>
                            </div>
                            <h6 class="fw-extrabold text-dark mb-1">{{ Str::limit($task->title, 35) }}</h6>
                            <p class="text-secondary small mb-3" style="line-height: 1.4;">{{ Str::limit($task->description, 60) }}</p>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                    Detay Gör
                                </a>
                                <div class="d-flex align-items-center gap-1.5">
                                    <div class="avatar-xs bg-dark font-monospace text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 10px;">
                                        {{ strtoupper(substr($task->assignedUser->name ?? $task->assigned_to ?? 'N', 0, 1)) }}
                                    </div>
                                    <small class="text-muted font-monospace fw-bold" style="font-size: 11px;">{{ optional(\Carbon\Carbon::parse($task->due_date))->format('d.m') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ri-history-line display-4 text-muted mb-2 d-block"></i>
                            <small class="fw-bold">Bekleyen Görev Yok</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMN 2: AKTİF İMALATLAR -->
        <div class="col-xl-3 col-md-6">
            <div class="kanban-col-card">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-3 border-primary">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-block" style="width: 12px; height: 12px; background: #2563eb;"></span>
                        <h6 class="fw-extrabold text-dark mb-0 text-uppercase" style="letter-spacing: 0.5px;">Aktif Görevler</h6>
                    </div>
                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 font-monospace fw-bold" style="background: #2563eb;">{{ $activeCount }}</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @php $activeTasks = is_array($tasks) && isset($tasks['active']) ? $tasks['active'] : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'active') : []); @endphp
                    @forelse($activeTasks as $task)
                        <div class="kanban-task-card p-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                @if($task->priority === 'urgent')
                                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🔴 ACİL</span>
                                @elseif($task->priority === 'high')
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🟡 YÜKSEK</span>
                                @else
                                    <span class="badge bg-info text-white rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🔵 NORMAL</span>
                                @endif
                                <small class="text-muted font-monospace fw-bold">#TSK-{{ $task->id }}</small>
                            </div>
                            <h6 class="fw-extrabold text-dark mb-1">{{ Str::limit($task->title, 35) }}</h6>
                            <p class="text-secondary small mb-3" style="line-height: 1.4;">{{ Str::limit($task->description, 60) }}</p>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span class="text-muted">İlerleme Oranı</span>
                                    <span class="text-primary font-monospace">%{{ $task->completion_rate ?? 50 }}</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $task->completion_rate ?? 50 }}%"></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                    Detay Gör
                                </a>
                                <div class="d-flex align-items-center gap-1.5">
                                    <div class="avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-monospace fw-bold" style="width: 28px; height: 28px; font-size: 10px;">
                                        {{ strtoupper(substr($task->assignedUser->name ?? $task->assigned_to ?? 'N', 0, 1)) }}
                                    </div>
                                    <small class="text-muted font-monospace fw-bold" style="font-size: 11px;">{{ optional(\Carbon\Carbon::parse($task->due_date))->format('d.m') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ri-loader-4-line display-4 text-muted mb-2 d-block"></i>
                            <small class="fw-bold">Aktif Görev Yok</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMN 3: İNCELENEN & DENETİMDE -->
        <div class="col-xl-3 col-md-6">
            <div class="kanban-col-card">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-3 border-warning">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-block" style="width: 12px; height: 12px; background: #d97706;"></span>
                        <h6 class="fw-extrabold text-dark mb-0 text-uppercase" style="letter-spacing: 0.5px;">İncelenen</h6>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ $reviewCount }}</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @php $reviewTasks = is_array($tasks) && isset($tasks['review']) ? $tasks['review'] : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'review') : []); @endphp
                    @forelse($reviewTasks as $task)
                        <div class="kanban-task-card p-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px;">🟡 DENETİMDE</span>
                                <small class="text-muted font-monospace fw-bold">#TSK-{{ $task->id }}</small>
                            </div>
                            <h6 class="fw-extrabold text-dark mb-1">{{ Str::limit($task->title, 35) }}</h6>
                            <p class="text-secondary small mb-3" style="line-height: 1.4;">{{ Str::limit($task->description, 60) }}</p>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold text-dark" style="font-size: 11px;">
                                    Detay Gör
                                </a>
                                <div class="d-flex align-items-center gap-1.5">
                                    <div class="avatar-xs bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center font-monospace fw-bold" style="width: 28px; height: 28px; font-size: 10px;">
                                        {{ strtoupper(substr($task->assignedUser->name ?? $task->assigned_to ?? 'N', 0, 1)) }}
                                    </div>
                                    <small class="text-muted font-monospace fw-bold" style="font-size: 11px;">{{ optional(\Carbon\Carbon::parse($task->due_date))->format('d.m') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ri-search-eye-line display-4 text-muted mb-2 d-block"></i>
                            <small class="fw-bold">İncelemede Görev Yok</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMN 4: TAMAMLANAN -->
        <div class="col-xl-3 col-md-6">
            <div class="kanban-col-card">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-3 border-success">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-block" style="width: 12px; height: 12px; background: #047857;"></span>
                        <h6 class="fw-extrabold text-dark mb-0 text-uppercase" style="letter-spacing: 0.5px;">Tamamlanan</h6>
                    </div>
                    <span class="badge bg-success text-white rounded-pill px-2.5 py-1 font-monospace fw-bold" style="background: #047857;">{{ $completedCount }}</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    @php $completedTasks = is_array($tasks) && isset($tasks['completed']) ? $tasks['completed'] : (is_object($tasks) && method_exists($tasks, 'where') ? $tasks->where('status', 'completed') : []); @endphp
                    @forelse($completedTasks as $task)
                        <div class="kanban-task-card p-3.5">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-success text-white rounded-pill px-2.5 py-1 font-monospace" style="font-size: 10px; background: #047857;">🟢 TAMAMLANDI</span>
                                <small class="text-muted font-monospace fw-bold">#TSK-{{ $task->id }}</small>
                            </div>
                            <h6 class="fw-extrabold text-dark mb-1">{{ Str::limit($task->title, 35) }}</h6>
                            <p class="text-secondary small mb-3" style="line-height: 1.4;">{{ Str::limit($task->description, 60) }}</p>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" style="font-size: 11px;">
                                    Detay Gör
                                </a>
                                <div class="d-flex align-items-center gap-1.5">
                                    <div class="avatar-xs bg-success text-white rounded-circle d-flex align-items-center justify-content-center font-monospace fw-bold" style="width: 28px; height: 28px; font-size: 10px;">
                                        {{ strtoupper(substr($task->assignedUser->name ?? $task->assigned_to ?? 'N', 0, 1)) }}
                                    </div>
                                    <small class="text-muted font-monospace fw-bold" style="font-size: 11px;">{{ optional(\Carbon\Carbon::parse($task->due_date))->format('d.m') }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ri-checkbox-circle-line display-4 text-muted mb-2 d-block"></i>
                            <small class="fw-bold">Tamamlanan Görev Yok</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

@endsection