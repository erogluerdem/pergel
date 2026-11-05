@extends('layouts.master')

@section('title', 'Proje Planlama & Gantt Zaman Çizelgesi')

@section('topbar-title', 'Projeler - Planlama & Çizelge')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.min.css">
<style>
    /* ROYAL DARK SLATE & GOLDEN AMBER GLASSMORPHIC THEME */
    .plan-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .plan-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: planGlow 4s linear infinite;
    }

    @keyframes planGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .plan-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .plan-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .plan-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .plan-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    .plan-glass-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        padding: 24px !important;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
    }

    .gantt-container {
        min-height: 580px;
        border-radius: 14px;
        border: 1.5px solid #cbd5e1 !important;
        background: #f8fafc !important;
    }

    .task-list-container {
        max-height: 580px;
        overflow-y: auto;
    }

    .task-row {
        padding: 1rem 1.2rem;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        transition: all 0.2s ease;
        cursor: pointer;
        border-radius: 12px;
        margin-bottom: 0.5rem;
    }

    .task-row:hover {
        background: #fffbeb !important;
        border-color: #d97706 !important;
    }

    .task-row.critical-path {
        border-left: 6px solid #dc2626 !important;
        background: #fef2f2 !important;
    }

    .custom-input-plan {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }
    .custom-input-plan:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .modal-content {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        overflow: hidden;
    }

    #newTaskModal.modal { z-index: 200000 !important; }
    .modal-backdrop { z-index: 199999 !important; }
    #newTaskModal .modal-dialog { margin-top: 4.5rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="plan-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill plan-badge-pill">
                        <i class="ri-git-commit-fill text-warning fs-5"></i> CPM KRİTİK YOL ANALİZİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        GANTT & İŞ PROGRAMI
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Proje Planlama & Zaman Çizelgesi</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Saha imalat adımlarını, kritik yol (CPM) bağlantılarını, gecikme risklerini ve kaynak dağılımlarını anlık takip edin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <select id="projectSelect" class="form-select custom-input-plan py-2.5 px-3 fw-extrabold" style="min-width: 240px; background-color: #ffffff !important;">
                        <option value="">🏢 Tüm Projeler (Genel Görünüm)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm" onclick="showNewTaskModal()">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Görev
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 LUXURY KPI SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card plan-kpi-card border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM GÖREV SAYISI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;" id="totalTasks">-</div>
                        <small class="text-primary fw-bold"><i class="ri-task-fill me-1"></i>Kayıtlı İmalat Adımı</small>
                    </div>
                    <div class="plan-stat-icon rounded-4 flex-shrink-0" style="background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-task-fill" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card plan-kpi-card border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAMAMLANAN İMALATLAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;" id="completedTasks">-</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Tamamlanan Aşama</small>
                    </div>
                    <div class="plan-stat-icon rounded-4 flex-shrink-0" style="background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card plan-kpi-card border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GECİKEN / RİSKLİ ADIMLAR</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;" id="overdueTasks">-</div>
                        <small class="text-danger fw-bold"><i class="ri-alarm-warning-fill me-1"></i>Risk Altındaki Görevler</small>
                    </div>
                    <div class="plan-stat-icon rounded-4 flex-shrink-0" style="background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-alarm-warning-fill" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card plan-kpi-card border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">KRİTİK YOL (CPM)</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;" id="criticalPath">-</div>
                        <small class="text-warning fw-bold"><i class="ri-git-commit-fill me-1"></i>Kritik Aşama Görevleri</small>
                    </div>
                    <div class="plan-stat-icon rounded-4 flex-shrink-0" style="background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-git-commit-fill" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Planning Area -->
        <div class="col-lg-9">
            <div class="card plan-glass-card">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ri-layout-grid-fill text-warning fs-5"></i>İş Programı & Gantt Yönetimi
                    </h5>
                    <div class="btn-group gap-2">
                        <button class="btn btn-outline-dark rounded-pill px-3.5 fw-extrabold" id="refreshData" title="Yenile">
                            <i class="ri-refresh-line me-1"></i>Yenile
                        </button>
                        <button class="btn btn-warning text-dark rounded-pill px-3.5 fw-extrabold shadow-sm" id="exportData" title="Dışa Aktar">
                            <i class="ri-download-cloud-fill me-1"></i>Rapor Al
                        </button>
                    </div>
                </div>

                <!-- Nav View Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fw-extrabold" data-bs-toggle="tab" href="#ganttView" role="tab">
                            <i class="ri-bar-chart-horizontal-fill me-1 text-warning"></i>Gantt Çizelgesi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-extrabold" data-bs-toggle="tab" href="#listView" role="tab">
                            <i class="ri-list-check-2 me-1 text-primary"></i>Görev Listesi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-extrabold" data-bs-toggle="tab" href="#calendarView" role="tab">
                            <i class="ri-calendar-event-fill me-1 text-info"></i>Takvim Görünümü
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-extrabold" data-bs-toggle="tab" href="#chartView" role="tab">
                            <i class="ri-pie-chart-fill me-1 text-success"></i>Analiz & Grafikler
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Gantt View -->
                    <div id="ganttView" class="tab-pane active" role="tabpanel">
                        <div id="ganttContainer" class="gantt-container p-3">
                            <div class="d-flex justify-content-center align-items-center h-100 py-5">
                                <div class="text-center">
                                    <div class="avatar-lg bg-dark text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3 fs-1 border border-warning">
                                        <i class="ri-bar-chart-horizontal-fill"></i>
                                    </div>
                                    <h5 class="fw-extrabold text-dark">Gantt Şeması Yükleniyor...</h5>
                                    <p class="text-muted fw-bold small">Lütfen yukarıdan bir proje seçin veya yeni bir görev ekleyin.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- List View -->
                    <div id="listView" class="tab-pane" role="tabpanel">
                        <div class="task-list-container">
                            <div id="taskList" class="p-2">
                                <div class="text-center py-5">
                                    <i class="ri-loader-4-line fs-1 d-block mb-2 text-warning spin"></i>
                                    <p class="text-muted fw-bold">Görevler listeleniyor...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar View -->
                    <div id="calendarView" class="tab-pane" role="tabpanel">
                        <div class="calendar-container p-2">
                            <div id="fullCalendar"></div>
                        </div>
                    </div>

                    <!-- Chart View -->
                    <div id="chartView" class="tab-pane" role="tabpanel">
                        <div class="p-3">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-4 bg-light">
                                        <h6 class="fw-extrabold text-dark mb-3"><i class="ri-pie-chart-fill text-primary me-2"></i>İlerleme Dağılımı</h6>
                                        <div class="chart-container" style="height: 250px;">
                                            <canvas id="progressChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-4 bg-light">
                                        <h6 class="fw-extrabold text-dark mb-3"><i class="ri-user-shared-fill text-success me-2"></i>Kaynak Kullanım Grafiği</h6>
                                        <div class="chart-container" style="height: 250px;">
                                            <canvas id="resourceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4 mt-2">
                                <div class="col-12">
                                    <div class="p-3 border rounded-4 bg-light">
                                        <h6 class="fw-extrabold text-dark mb-3"><i class="ri-line-chart-fill text-info me-2"></i>Zaman Çizelgesi & Hedef Karşılaştırması</h6>
                                        <div class="chart-container" style="height: 280px;">
                                            <canvas id="timelineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons Footer -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning text-dark rounded-pill px-4 fw-extrabold shadow-sm" id="btnNewTask" onclick="showNewTaskModal()">
                            <i class="ri-add-fill me-1"></i>Yeni Görev
                        </button>
                        <button class="btn btn-outline-dark rounded-pill px-3.5 fw-extrabold" id="btnCreateBaseline">
                            <i class="ri-save-fill me-1"></i>Baseline Oluştur
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-danger rounded-pill px-3.5 fw-extrabold" onclick="highlightCriticalPath()">
                            <i class="ri-git-commit-fill me-1"></i>Kritik Yolu Vurgula
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Panel -->
        <div class="col-lg-3">
            <div class="card plan-glass-card p-3" id="detailPanel" style="display: none;">
                <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
                    <h6 class="fw-extrabold mb-0 text-dark d-flex align-items-center gap-2"><i class="ri-information-fill text-warning fs-5"></i>Görev Detayı</h6>
                    <button class="btn btn-sm btn-outline-dark rounded-circle" id="closeDetail">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <ul class="nav nav-pills nav-justified mb-3" id="detailTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active small py-1.5 fw-extrabold" data-bs-toggle="pill" href="#tabInfo">Genel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small py-1.5 fw-extrabold" data-bs-toggle="pill" href="#tabResources">Kaynaklar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small py-1.5 fw-extrabold" data-bs-toggle="pill" href="#tabDocs">Evraklar</a>
                    </li>
                </ul>

                <div class="tab-content" id="detailContent">
                    <div id="tabInfo" class="tab-pane active">
                        <div id="taskInfo" class="py-2">
                            <p class="text-muted text-center fw-bold small mb-0">Detaylarını incelemek için bir görev seçin.</p>
                        </div>
                    </div>
                    <div id="tabResources" class="tab-pane">
                        <div id="taskResources" class="py-2">
                            <p class="text-muted text-center fw-bold small mb-0">Atanmış iş gücü veya ekipman bulunmuyor.</p>
                        </div>
                    </div>
                    <div id="tabDocs" class="tab-pane">
                        <div id="taskDocs" class="py-2">
                            <p class="text-muted text-center fw-bold small mb-0">Bağlantılı doküman yok.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NEW TASK MODAL -->
    <div class="modal fade" id="newTaskModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2"><i class="ri-add-circle-fill text-warning fs-5"></i>Yeni Görev / İmalat Adımı Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" onclick="hideNewTaskModal()"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="newTaskForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label class="form-label fw-extrabold small text-dark">Görev / İmalat Adı <span class="text-danger">*</span></label>
                                <input type="text" class="form-control custom-input-plan py-2" name="name" placeholder="Örn: Radye Temel Betonajı" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-extrabold small text-dark">Sorumlu Mühendis / Saha Şefi</label>
                                <input type="text" class="form-control custom-input-plan py-2" name="responsible" placeholder="Örn: Serkan Avcı">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-extrabold small text-dark">Başlangıç Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control custom-input-plan py-2" name="start_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-extrabold small text-dark">Bitiş Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control custom-input-plan py-2" name="end_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-extrabold small text-dark">Süre (İş Günü)</label>
                                <input type="number" class="form-control custom-input-plan py-2" name="duration" min="1" placeholder="10">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-extrabold small text-dark">WBS Bütçe Kodu</label>
                                <input type="text" class="form-control custom-input-plan py-2 font-monospace" name="budget_wbs" placeholder="Örn: WBS-2.1-STR">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-extrabold small text-dark">Mevcut İlerleme Oranı (%)</label>
                                <input type="number" class="form-control custom-input-plan py-2 font-monospace" name="progress" min="0" max="100" value="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-extrabold small text-dark">Teknik Notlar & Açıklamalar</label>
                            <textarea class="form-control custom-input-plan" name="notes" rows="3" placeholder="Saha ekibi için detaylar, malzeme ihtiyaçları veya özel talimatlar..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer p-3 border-top">
                    <button type="button" class="btn btn-outline-dark rounded-pill px-4 fw-extrabold" onclick="hideNewTaskModal()">İptal</button>
                    <button type="button" class="btn btn-warning text-dark rounded-pill px-5 fw-extrabold shadow-sm" id="saveNewTask" onclick="saveNewTask()">
                        <i class="ri-save-fill me-1"></i>Görevi Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Chart.js eski UMD versiyonu (global Chart objesi için) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" onload="console.log('Chart.js UMD başarıyla yüklendi, Chart objesi:', typeof Chart);" onerror="console.error('Chart.js yükleme hatası');"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
(function() {
    'use strict';

    // Global variables
    let currentProject = null;
    let tasks = [];
    let ganttChart = null;
    let calendar = null;
    let charts = {};
    // Bootstrap Modal instance for new task modal (preferred over manual DOM ops)
    let newTaskBsModal = null;
    // Track last user interaction to avoid programmatic modal auto-open
    let lastUserInteraction = null;

    // Initialize the application
    document.addEventListener('DOMContentLoaded', function() {
        // Reset modal state on page load
        const modalElement = document.getElementById('newTaskModal');
        if (modalElement) {
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        initializeComponents();
        bindEvents();
        loadInitialData();
        // Start tracking genuine user interactions (click/keydown/touch)
        ['click', 'keydown', 'touchstart', 'mousedown'].forEach(evt => {
            document.addEventListener(evt, function() {
                lastUserInteraction = Date.now();
            }, { capture: true, passive: true });
        });
    });

    function initializeComponents() {
        // Initialize FullCalendar
        const calendarEl = document.getElementById('fullCalendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'tr',
            height: 'auto',
            events: function(info, successCallback, failureCallback) {
                if (currentProject) {
                    loadCalendarEvents(successCallback, failureCallback);
                } else {
                    successCallback([]);
                }
            },
            eventClick: function(info) {
                showTaskDetail(info.event.id);
            }
        });

        // Initialize sortable lists
        if (document.getElementById('taskList')) {
            new Sortable(document.getElementById('taskList'), {
                animation: 150,
                ghostClass: 'dragging',
                chosenClass: 'drag-over',
                onEnd: function(evt) {
                    // Handle task reordering
                    updateTaskOrder(evt.oldIndex, evt.newIndex);
                }
            });
        }

        // Initialize charts after Chart.js loads
        setTimeout(() => {
            console.log('Delayed chart initialization starting...');
            initializeCharts();
        }, 2000); // 2 saniye bekle
    }

    function bindEvents() {
        // Project selection
        document.getElementById('projectSelect').addEventListener('change', function(e) {
            currentProject = e.target.value;
            loadProjectData();
        });

        // Tab switches
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const target = e.target.getAttribute('href');
                console.log('Tab değiştirildi:', target);

                if (target === '#calendarView' && calendar) {
                    setTimeout(() => calendar.render(), 100);
                }

                if (target === '#chartView') {
                    console.log('Chart view sekmesi açıldı, charts başlatılıyor...');
                    setTimeout(() => {
                        initializeCharts();
                        updateChartData();
                    }, 200);
                }
            });
        });

        // Action buttons - with enhanced debugging
        console.log('Event listener kurulumu başlıyor...');

        const btnNewTask = document.getElementById('btnNewTask');
        const btnSettings = document.getElementById('btnSettings');

        console.log('btnNewTask:', btnNewTask);
        console.log('btnSettings:', btnSettings);

        if (btnNewTask) {
            btnNewTask.addEventListener('click', function(e) {
                console.log('Yeni Görev butonuna tıklandı - event:', e);
                console.log('currentProject:', currentProject);
                e.preventDefault(); // Form submit'i engelle
                // Mark that a real user just interacted so modal guard allows opening
                lastUserInteraction = Date.now();
                showNewTaskModal();
            });
        } else {
            console.error('btnNewTask buton elementi bulunamadı!');
        }

        if (btnSettings) {
            btnSettings.addEventListener('click', function(e) {
                console.log('Ayarlar butonuna tıklandı - event:', e);
                e.preventDefault(); // Form submit'i engelle
                showSettingsModal();
            });
        } else {
            console.error('btnSettings buton elementi bulunamadı!');
        }

        document.getElementById('btnImportTasks').addEventListener('click', importTasks);
        document.getElementById('btnCreateBaseline').addEventListener('click', createBaseline);
        document.getElementById('btnCriticalPath').addEventListener('click', highlightCriticalPath);
        document.getElementById('btnResourceReport').addEventListener('click', showResourceReport);
		document.getElementById('btnCreateSampleTasks').addEventListener('click', createSampleTasks);
        document.getElementById('btnRiskAnalysis').addEventListener('click', showRiskAnalysis);
        document.getElementById('btnProgressReport').addEventListener('click', showProgressReport);
        document.getElementById('refreshData').addEventListener('click', loadProjectData);
        document.getElementById('exportData').addEventListener('click', exportData);

        // Modal events
        document.getElementById('saveNewTask').addEventListener('click', saveNewTask);
        document.getElementById('closeDetail').addEventListener('click', hideDetailPanel);

        // Auto-select first project if available
        const projectSelect = document.getElementById('projectSelect');
        if (projectSelect.options.length > 1) { // Skip "Tüm Projeler" option
            projectSelect.selectedIndex = 1; // Select first actual project
            currentProject = projectSelect.value;
            if (currentProject) {
                loadProjectData();
            }
        }

        // Debug: Test button existence after DOM is ready (no auto-click)
        console.log('=== BUTTON DEBUG TEST ===');
        setTimeout(() => {
            try {
                const testBtn = document.getElementById('btnNewTask');
                const settingsBtn = document.getElementById('btnSettings');
                console.log('btnNewTask element (no auto-click):', testBtn);
                console.log('btnSettings element (no auto-click):', settingsBtn);
            } catch (e) {
                console.warn('Button debug check failed:', e);
            }
        }, 2000);
    }

    function loadInitialData() {
        updateStatistics();
        if (currentProject) {
            loadProjectData();
        }
    }

    function loadProjectData() {
        if (!currentProject) {
            clearData();
            return;
        }

        console.log('Loading data for project:', currentProject);
        showLoading();

        // Load tasks and statistics for selected project
        Promise.all([
            fetch(`/projects/${currentProject}/planning/tasks`),
            fetch(`/projects/${currentProject}/planning/statistics`)
        ])
        .then(responses => {
            console.log('Received responses:', responses);
            return Promise.all(responses.map(r => {
                if (!r.ok) {
                    throw new Error(`HTTP error! status: ${r.status}`);
                }
                return r.json();
            }));
        })
        .then(([tasksData, statisticsData]) => {
            console.log('Tasks data:', tasksData);
            console.log('Statistics data:', statisticsData);
            tasks = tasksData;
            updateViews();
            updateStatisticsFromAPI(statisticsData);
            hideLoading();
        })
        .catch(error => {
            console.error('Error loading project data:', error);
            showError('Proje verileri yüklenirken hata oluştu: ' + error.message);
            hideLoading();
        });
    }

    function createSampleTasks() {
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }
        const samples = [
            { name: 'Örnek: Şantiye Kurulum', start_date: new Date().toISOString().split('T')[0], end_date: new Date(Date.now()+3*24*3600*1000).toISOString().split('T')[0], duration: 4, notes: 'Kurulum işleri.' },
            { name: 'Örnek: Kazı İşleri', start_date: new Date(Date.now()+4*24*3600*1000).toISOString().split('T')[0], end_date: new Date(Date.now()+10*24*3600*1000).toISOString().split('T')[0], duration: 7, notes: 'Kazı ve altyapı.' },
            { name: 'Örnek: Temel Betonu', start_date: new Date(Date.now()+11*24*3600*1000).toISOString().split('T')[0], end_date: new Date(Date.now()+15*24*3600*1000).toISOString().split('T')[0], duration: 5, notes: 'Temel atma.' }
        ];

        Promise.all(samples.map(s => fetch(`/projects/${currentProject}/planning/tasks`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(s)
        }).then(r=>r.json()).catch(e=>{console.error('sample create error', e);})))
        .then(() => {
            showSuccess('Örnek görevler oluşturuldu');
            loadProjectData();
        });
    }

    function updateViews() {
        updateGanttView();
        updateListView();
        updateCharts();
        if (calendar) {
            calendar.refetchEvents();
        }
    }

    function updateGanttView() {
        const container = document.getElementById('ganttContainer');

        if (!tasks || tasks.length === 0) {
            container.innerHTML = `
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-center">
                        <i class="mdi mdi-chart-gantt text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Gantt Çizelgesi</h5>
                        <p class="text-muted">Görev bulunamadı</p>
                    </div>
                </div>
            `;
            return;
        }

        // Create a simple timeline visualization
        const ganttData = tasks.map(task => ({
            id: task.id,
            name: task.name,
            start: task.start_date,
            end: task.end_date,
            progress: task.progress || 0,
            dependencies: task.dependencies || [],
            critical: task.critical_path || false
        }));

        renderCustomGantt(container, ganttData);
    }

    function renderCustomGantt(container, data) {
        if (!data || data.length === 0) {
            container.innerHTML = `
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-center">
                        <i class="mdi mdi-chart-gantt text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Gantt Çizelgesi</h5>
                        <p class="text-muted">Görev bulunamadı</p>
                    </div>
                </div>
            `;
            return;
        }

        // Calculate date range
        const dates = data.map(task => [new Date(task.start), new Date(task.end)]).flat();
        const minDate = new Date(Math.min(...dates));
        const maxDate = new Date(Math.max(...dates));
        const totalDays = Math.ceil((maxDate - minDate) / (1000 * 60 * 60 * 24));

        // Custom Gantt implementation
        let html = `
            <div class="gantt-header">
                <div class="gantt-timeline-header">
                    <div class="gantt-task-header">Görevler</div>
                    <div class="gantt-dates-header">
                        <div class="d-flex justify-content-between">
                            <small>${formatDate(minDate.toISOString())}</small>
                            <small>Bugün</small>
                            <small>${formatDate(maxDate.toISOString())}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gantt-body">
        `;

        data.forEach(task => {
            const criticalClass = task.critical ? 'critical-path' : '';
            const taskStart = new Date(task.start);
            const taskEnd = new Date(task.end);
            const taskDays = Math.ceil((taskEnd - taskStart) / (1000 * 60 * 60 * 24)) + 1;

            // Calculate position and width based on actual dates
            const leftPercent = ((taskStart - minDate) / (maxDate - minDate)) * 100;
            const widthPercent = (taskDays / totalDays) * 100;

            html += `
                <div class="gantt-row ${criticalClass}" data-task-id="${task.id}">
                    <div class="gantt-task-info">
                        <div class="task-name">${task.name}</div>
                        <div class="task-dates">
                            <small>${formatDate(task.start)} - ${formatDate(task.end)}</small>
                        </div>
                        <div class="progress-bar-custom mt-1">
                            <div class="progress-fill" style="width: ${task.progress}%"></div>
                        </div>
                        <div class="mt-1">
                            <span class="badge bg-success text-white">${task.progress}%</span>
                            ${task.critical ? '<span class="badge bg-danger text-white ms-1">Kritik</span>' : ''}
                        </div>
                    </div>
                    <div class="gantt-timeline">
                        <div class="gantt-bar ${criticalClass}" style="left: ${leftPercent}%; width: ${Math.max(widthPercent, 2)}%;" title="${task.name} - ${task.progress}%">
                            <span class="gantt-bar-progress" style="width: ${task.progress}%"></span>
                            <div class="progress-indicator">${task.progress}%</div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';

        // Add today line
        const today = new Date();
        if (today >= minDate && today <= maxDate) {
            const todayPercent = ((today - minDate) / (maxDate - minDate)) * 100;
            html = html.replace('<div class="gantt-body">',
                `<div class="gantt-body" style="position: relative;">
                 <div class="today-line" style="position: absolute; left: ${todayPercent}%; top: 0; bottom: 0; width: 2px; background: #dc3545; z-index: 10; pointer-events: none;"></div>`);
        }

        container.innerHTML = html;

        // Add click handlers
        container.querySelectorAll('.gantt-row').forEach(row => {
            row.addEventListener('click', function() {
                const taskId = this.dataset.taskId;
                showTaskDetail(taskId);
            });
        });
    }

    function updateListView() {
        const container = document.getElementById('taskList');

        if (!tasks || tasks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="mdi mdi-format-list-bulleted text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Görev Listesi</h5>
                    <p class="text-muted">Görev bulunamadı</p>
                </div>
            `;
            return;
        }

        let html = '';
        tasks.forEach(task => {
            const criticalClass = task.critical_path ? 'critical-path' : '';
            const progressColor = task.progress >= 80 ? 'success' : task.progress >= 50 ? 'warning' : 'danger';

            html += `
                <div class="task-row ${criticalClass}" data-task-id="${task.id}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="task-name">${task.name}</div>
                            <div class="task-meta">
                                <span class="me-3"><i class="mdi mdi-calendar"></i> ${formatDate(task.start_date)} - ${formatDate(task.end_date)}</span>
                                <span class="me-3"><i class="mdi mdi-account"></i> ${task.responsible || 'Atanmamış'}</span>
                                ${task.critical_path ? '<span class="badge bg-danger">Kritik</span>' : ''}
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: ${task.progress || 0}%"></div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="h6 mb-1">${task.progress || 0}%</div>
                            <div class="task-actions">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editTask(${task.id})">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;

        // Add click handlers
        container.querySelectorAll('.task-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('.task-actions')) {
                    const taskId = this.dataset.taskId;
                    showTaskDetail(taskId);
                }
            });
        });
    }

    // Chart.js yükleme durumu takibi
    let chartLoadAttempts = 0;
    const maxChartAttempts = 10;

    function initializeCharts() {
        // Chart.js kontrolü - kapsamlı debug
        console.log('Chart.js kontrol ediliyor...');
        console.log('typeof Chart:', typeof Chart);
        console.log('typeof window.Chart:', typeof window.Chart);
        console.log('Chart global check:', typeof Chart !== 'undefined' ? Chart : 'undefined');
        console.log('window.Chart check:', typeof window.Chart !== 'undefined' ? window.Chart : 'undefined');

        // Chart kütüphanesini güvenli şekilde al
        let ChartLib = null;
        try {
            ChartLib = (typeof Chart !== 'undefined') ? Chart :
                      (typeof window.Chart !== 'undefined') ? window.Chart : null;
        } catch (e) {
            console.error('Chart erişim hatası:', e);
        }

        if (!ChartLib) {
            chartLoadAttempts++;
            if (chartLoadAttempts < maxChartAttempts) {
                console.error(`Chart.js henüz yüklenmedi, charts başlatılamıyor (deneme ${chartLoadAttempts}/${maxChartAttempts})`);
                setTimeout(initializeCharts, 1000); // Bekleme süresini artır
                return;
            } else {
                console.error('Chart.js yüklenemedi, charts devre dışı bırakılıyor');
                return;
            }
        }

        console.log('✅ Chart.js mevcut, charts başlatılıyor...');
        console.log('Chart library:', ChartLib);
        console.log('Chart constructor test:', typeof ChartLib === 'function');

        // Progress Chart
        const progressCtx = document.getElementById('progressChart');
        if (progressCtx) {
            charts.progress = new ChartLib(progressCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tamamlanan', 'Devam Eden', 'Başlamamış'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Resource Chart
        const resourceCtx = document.getElementById('resourceChart');
        if (resourceCtx) {
            charts.resource = new ChartLib(resourceCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Kullanım',
                        data: [],
                        backgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Timeline Chart
        const timelineCtx = document.getElementById('timelineChart');
        if (timelineCtx) {
            charts.timeline = new ChartLib(timelineCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Planlanan',
                        data: [],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Gerçekleşen',
                        data: [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        x: {
                            display: true,
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }

    function updateCharts() {
        if (!tasks || tasks.length === 0) return;

        // Update progress chart
        const completed = tasks.filter(t => t.progress >= 100).length;
        const ongoing = tasks.filter(t => t.progress > 0 && t.progress < 100).length;
        const notStarted = tasks.filter(t => !t.progress || t.progress === 0).length;

        if (charts.progress) {
            charts.progress.data.datasets[0].data = [completed, ongoing, notStarted];
            charts.progress.update();
        }

        // Update other charts with sample data
        updateResourceChart();
        updateTimelineChart();
    }

    function updateResourceChart() {
        // Sample resource data
        const resources = ['Mühendis', 'İşçi', 'Makinist', 'Tekniker'];
        const usage = [85, 92, 67, 78];

        if (charts.resource) {
            charts.resource.data.labels = resources;
            charts.resource.data.datasets[0].data = usage;
            charts.resource.update();
        }
    }

    function updateTimelineChart() {
        if (!currentProject) return;

        fetch(`/projects/${currentProject}/planning/timeline-data`)
            .then(response => response.json())
            .then(data => {
                if (charts.timeline) {
                    const labels = data.map(item => item.month);
                    const planned = data.map(item => item.planned);
                    const completed = data.map(item => item.completed);

                    charts.timeline.data.labels = labels;
                    charts.timeline.data.datasets[0].data = planned;
                    charts.timeline.data.datasets[1].data = completed;
                    charts.timeline.update();
                }
            })
            .catch(error => {
                console.error('Timeline data could not be loaded:', error);
                // Fallback to sample data
                const months = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz'];
                const planned = [20, 40, 60, 80, 90, 100];
                const actual = [15, 35, 55, 70, 85, 95];

                if (charts.timeline) {
                    charts.timeline.data.labels = months;
                    charts.timeline.data.datasets[0].data = planned;
                    charts.timeline.data.datasets[1].data = actual;
                    charts.timeline.update();
                }
            });
    }

    function updateStatistics() {
        if (!tasks || tasks.length === 0) {
            document.getElementById('totalTasks').textContent = '0';
            document.getElementById('completedTasks').textContent = '0';
            document.getElementById('overdueTasks').textContent = '0';
            document.getElementById('criticalPath').textContent = '0';
            return;
        }

        const total = tasks.length;
        const completed = tasks.filter(t => t.progress >= 100).length;
        const overdue = tasks.filter(t => {
            const endDate = new Date(t.end_date);
            return endDate < new Date() && t.progress < 100;
        }).length;
        const critical = tasks.filter(t => t.critical_path).length;

        document.getElementById('totalTasks').textContent = total;
        document.getElementById('completedTasks').textContent = completed;
        document.getElementById('overdueTasks').textContent = overdue;
        document.getElementById('criticalPath').textContent = critical;
    }

    function updateStatisticsFromAPI(stats) {
        document.getElementById('totalTasks').textContent = stats.total_tasks || '0';
        document.getElementById('completedTasks').textContent = stats.completed_tasks || '0';
        document.getElementById('overdueTasks').textContent = stats.overdue_tasks || '0';
        document.getElementById('criticalPath').textContent = stats.critical_path_tasks || '0';

        // Update charts with API data
        if (stats.progress_distribution && charts.progress) {
            const dist = stats.progress_distribution;
            charts.progress.data.datasets[0].data = [dist.completed, dist.in_progress, dist.not_started];
            charts.progress.update();
        }

        // Update resource chart with API data
        if (stats.resource_utilization && charts.resource) {
            const resources = Object.keys(stats.resource_utilization);
            const utilization = Object.values(stats.resource_utilization);

            charts.resource.data.labels = resources;
            charts.resource.data.datasets[0].data = utilization;
            charts.resource.update();
        }
    }

    function showTaskDetail(taskId) {
        const task = tasks.find(t => t.id == taskId);
        if (!task) return;

        // Show detail panel
        const panel = document.getElementById('detailPanel');
        panel.style.display = 'block';

        // Update task info
        const infoTab = document.getElementById('taskInfo');
        infoTab.innerHTML = `
            <div class="mb-3">
                <h6>${task.name}</h6>
                <p class="text-muted mb-2">${task.notes || 'Açıklama yok'}</p>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Başlangıç</small>
                        <div>${formatDate(task.start_date)}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Bitiş</small>
                        <div>${formatDate(task.end_date)}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">İlerleme</small>
                    <div class="progress mt-1">
                        <div class="progress-bar" style="width: ${task.progress || 0}%">${task.progress || 0}%</div>
                    </div>
                </div>
                ${task.critical_path ? '<div class="mt-2"><span class="badge bg-danger">Kritik Yol</span></div>' : ''}
            </div>
        `;

        // Update resources tab
        const resourcesTab = document.getElementById('taskResources');
        if (task.resources && task.resources.length > 0) {
            let resourcesHtml = '<h6>Kaynaklar</h6>';
            task.resources.forEach(resource => {
                resourcesHtml += `<span class="resource-chip">${resource.name || resource}</span>`;
            });
            resourcesTab.innerHTML = resourcesHtml;
        } else {
            resourcesTab.innerHTML = '<p class="text-muted text-center">Kaynak tanımlanmamış</p>';
        }

        // Update documents tab
        const docsTab = document.getElementById('taskDocs');
        if (task.documents && task.documents.length > 0) {
            let docsHtml = '<h6>Dokümanlar</h6><div class="list-group list-group-flush">';
            task.documents.forEach(doc => {
                docsHtml += `
                    <div class="list-group-item p-2">
                        <i class="mdi mdi-file-document me-2"></i>
                        <small>${doc.name || doc}</small>
                    </div>
                `;
            });
            docsHtml += '</div>';
            docsTab.innerHTML = docsHtml;
        } else {
            docsTab.innerHTML = '<p class="text-muted text-center">Doküman yok</p>';
        }
    }

    function hideDetailPanel() {
        document.getElementById('detailPanel').style.display = 'none';
    }

    function showNewTaskModal() {
        console.log('showNewTaskModal çağrıldı, currentProject:', currentProject);
        // Prevent auto-opening modal when page restores from bfcache or on programmatic calls
        if (!lastUserInteraction || (Date.now() - lastUserInteraction) > 20000) {
            console.warn('Modal açma reddedildi: kullanıcı etkileşimi tespit edilmedi son 20s içinde. lastUserInteraction:', lastUserInteraction);
            showError('Lütfen önce bir proje seçin veya sayfada bir işlem yapın.');
            return;
        }
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }

        const modalElement = document.getElementById('newTaskModal');
        if (!modalElement) {
            console.error('newTaskModal elementi bulunamadı');
            return;
        }

        // If modal is not direct child of body, move it to body to avoid z-index/overflow issues
        try {
            if (modalElement.parentElement !== document.body) {
                console.log('Modal elementi body içine taşınıyor (önceki parent):', modalElement.parentElement);
                document.body.appendChild(modalElement);
            }
        } catch (e) {
            console.warn('Modal elementi taşınamadı:', e);
        }

        // Dump some pre-show diagnostics
        try {
            const cs = window.getComputedStyle(modalElement);
            console.log('Pre-show modal computed style:', {
                display: cs.display,
                visibility: cs.visibility,
                zIndex: cs.zIndex,
                pointerEvents: cs.pointerEvents,
                opacity: cs.opacity
            });
            console.log('Existing backdrops count:', document.querySelectorAll('.modal-backdrop').length);
        } catch (e) {
            console.warn('Pre-show diagnostics failed:', e);
        }

        // Prefer Bootstrap Modal API to avoid manual backdrop/body manipulation
        try {
            if (!newTaskBsModal) {
                newTaskBsModal = new bootstrap.Modal(modalElement, { focus: true });
            }
            console.log('Bootstrap modal instance (before show):', newTaskBsModal);
            newTaskBsModal.show();
        } catch (e) {
            // Fallback: minimal manual show (keeps previous behaviour but as fallback only)
            console.warn('Bootstrap modal API kullanılamıyor, manuel modal gösteriliyor:', e);
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');

            let backdrop = document.querySelector('.modal-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop show';
                backdrop.onclick = hideNewTaskModal; // Close modal on backdrop click
                document.body.appendChild(backdrop);
            } else {
                backdrop.classList.add('show');
                backdrop.style.display = 'block';
                backdrop.onclick = hideNewTaskModal;
            }
        }

        // Post-show diagnostics after a short delay to let Bootstrap render backdrop
        setTimeout(() => {
            try {
                const cs = window.getComputedStyle(modalElement);
                const backdrops = Array.from(document.querySelectorAll('.modal-backdrop'));
                console.log('Post-show modal state:', {
                    display: modalElement.style.display || cs.display,
                    classList: Array.from(modalElement.classList),
                    bodyModalOpen: document.body.classList.contains('modal-open'),
                    computed: { display: cs.display, zIndex: cs.zIndex, pointerEvents: cs.pointerEvents, visibility: cs.visibility, opacity: cs.opacity },
                    bounding: modalElement.getBoundingClientRect ? modalElement.getBoundingClientRect() : null,
                    backdropsCount: backdrops.length,
                    backdrops: backdrops.map(b => ({ classList: Array.from(b.classList), style: { display: b.style.display, zIndex: b.style.zIndex, opacity: b.style.opacity } }))
                });

                // If backdrop exists but modal not visible, try cleanup
                if (backdrops.length > 0 && (cs.display === 'none' || !document.body.classList.contains('modal-open'))) {
                    console.warn('Backdrop var ama modal görünmüyor; cleanup uygulanıyor.');
                    backdrops.forEach(b => b.remove());
                    document.body.classList.remove('modal-open');
                }
            } catch (e) {
                console.warn('Post-show diagnostics failed:', e);
            }
        }, 150);
    }

    function hideNewTaskModal() {
        const modalElement = document.getElementById('newTaskModal');
        if (!modalElement) return;

        try {
            if (newTaskBsModal) {
                newTaskBsModal.hide();
            } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const inst = bootstrap.Modal.getInstance(modalElement);
                if (inst) inst.hide();
            } else {
                // Fallback manual hide
                modalElement.style.display = 'none';
                modalElement.classList.remove('show');
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        } catch (e) {
            console.error('hideNewTaskModal hata:', e);
            // Ensure fallback cleanup
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }
    }

    function showSettingsModal() {
        console.log('Ayarlar modal açılıyor...');

        // Create settings modal HTML
        const modalHtml = `
            <div class="modal fade" id="settingsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="mdi mdi-cog me-2"></i>Planlama Ayarları</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Görünüm Ayarları</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showWeekends" checked>
                                        <label class="form-check-label" for="showWeekends">
                                            Hafta sonlarını göster
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showCriticalPath" checked>
                                        <label class="form-check-label" for="showCriticalPath">
                                            Kritik yolu vurgula
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="autoRefresh">
                                        <label class="form-check-label" for="autoRefresh">
                                            Otomatik yenile (30sn)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Bildirimler</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notifyOverdue" checked>
                                        <label class="form-check-label" for="notifyOverdue">
                                            Geciken görevler için bildirim
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notifyDeadlines" checked>
                                        <label class="form-check-label" for="notifyDeadlines">
                                            Yaklaşan son tarihler için bildirim
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                            <button type="button" class="btn btn-primary" onclick="saveSettings()">Kaydet</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('settingsModal');
        if (existingModal) existingModal.remove();

        // Add modal to page
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('settingsModal'));
        modal.show();
    }

    function saveSettings() {
        showSuccess('Ayarlar kaydedildi');
        const modal = bootstrap.Modal.getInstance(document.getElementById('settingsModal'));
        modal.hide();
    }

    function saveNewTask() {
        const form = document.getElementById('newTaskForm');
        const formData = new FormData(form);

        const taskData = {
            name: formData.get('name'),
            responsible: formData.get('responsible'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            duration: formData.get('duration'),
            notes: formData.get('notes')
        };

        // API call to save task
        fetch(`/projects/${currentProject}/planning/tasks`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(taskData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Görev kaydedilemedi');
            }
            return response.json();
        })
        .then(data => {
            showSuccess('Görev başarıyla kaydedildi');
            loadProjectData(); // Reload data

            // Manual modal hide
            hideNewTaskModal();
            form.reset();
        })
        .catch(error => {
            console.error('Error saving task:', error);
            showError('Görev kaydedilirken hata oluştu: ' + error.message);
        });
    }

    // Utility functions
    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('tr-TR');
    }

    function calculatePosition(startDate) {
        // Simple calculation for demo - should be improved
        return Math.random() * 80;
    }

    function calculateDuration(startDate, endDate) {
        // Simple calculation for demo - should be improved
        return Math.random() * 30 + 10;
    }

    function clearData() {
        tasks = [];
        updateViews();
        updateStatistics();
    }

    function showLoading() {
        document.querySelectorAll('.gantt-container, .task-list-container').forEach(el => {
            el.classList.add('loading');
        });
    }

    function hideLoading() {
        document.querySelectorAll('.gantt-container, .task-list-container').forEach(el => {
            el.classList.remove('loading');
        });
    }

    function showError(message) {
        // Create toast notification for errors
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="mdi mdi-alert-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        const container = getOrCreateToastContainer();
        container.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove toast element after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    function showSuccess(message) {
        // Create toast notification for success
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="mdi mdi-check-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        const container = getOrCreateToastContainer();
        container.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove toast element after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    function getOrCreateToastContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        return container;
    }

    function editTask(taskId) {
        const task = tasks.find(t => t.id == taskId);
        if (!task) return;

        // Populate form with task data
        const form = document.getElementById('newTaskForm');
        form.querySelector('[name="name"]').value = task.name || '';
        form.querySelector('[name="responsible"]').value = task.responsible || '';
        form.querySelector('[name="start_date"]').value = task.start_date || '';
        form.querySelector('[name="end_date"]').value = task.end_date || '';
        form.querySelector('[name="duration"]').value = task.duration || '';
        form.querySelector('[name="notes"]').value = task.notes || '';

        // Change modal title and save button behavior
        const modal = document.getElementById('newTaskModal');
        modal.querySelector('.modal-title').textContent = 'Görev Düzenle';

        const saveBtn = document.getElementById('saveNewTask');
        saveBtn.textContent = 'Güncelle';
        saveBtn.onclick = function() {
            updateTask(taskId);
        };

        // Show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    function updateTask(taskId) {
        const form = document.getElementById('newTaskForm');
        const formData = new FormData(form);

        const taskData = {
            name: formData.get('name'),
            responsible: formData.get('responsible'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            duration: formData.get('duration'),
            notes: formData.get('notes')
        };

        fetch(`/projects/${currentProject}/planning/tasks/${taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(taskData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Görev güncellenemedi');
            }
            return response.json();
        })
        .then(data => {
            showSuccess('Görev başarıyla güncellendi');
            loadProjectData();

            const modal = bootstrap.Modal.getInstance(document.getElementById('newTaskModal'));
            modal.hide();
            resetTaskForm();
        })
        .catch(error => {
            console.error('Error updating task:', error);
            showError('Görev güncellenirken hata oluştu: ' + error.message);
        });
    }

    function deleteTask(taskId) {
        if (!confirm('Bu görevi silmek istediğinizden emin misiniz?')) {
            return;
        }

        fetch(`/projects/${currentProject}/planning/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Görev silinemedi');
            }
            return response.json();
        })
        .then(data => {
            showSuccess('Görev başarıyla silindi');
            loadProjectData();
        })
        .catch(error => {
            console.error('Error deleting task:', error);
            showError('Görev silinirken hata oluştu: ' + error.message);
        });
    }

    function resetTaskForm() {
        const modal = document.getElementById('newTaskModal');
        modal.querySelector('.modal-title').textContent = 'Yeni Görev Ekle';

        const saveBtn = document.getElementById('saveNewTask');
        saveBtn.textContent = 'Kaydet';
        saveBtn.onclick = saveNewTask;

        document.getElementById('newTaskForm').reset();
    }

    function updateTaskOrder(oldIndex, newIndex) {
        // Handle task reordering
        console.log('Task reordered from', oldIndex, 'to', newIndex);
    }

    function loadCalendarEvents(successCallback, failureCallback) {
        // Convert tasks to calendar events
        const events = tasks.map(task => ({
            id: task.id,
            title: task.name,
            start: task.start_date,
            end: task.end_date,
            backgroundColor: task.critical_path ? '#ef4444' : '#4f46e5',
            borderColor: task.critical_path ? '#dc2626' : '#3730a3'
        }));
        successCallback(events);
    }

    // Button functions
    function exportData() {
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }

        showLoading();
        fetch(`/projects/${currentProject}/planning/export`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Veriler başarıyla hazırlandı');

                    // Create downloadable content
                    const content = JSON.stringify(data.data, null, 2);
                    const blob = new Blob([content], { type: 'application/json' });
                    const url = window.URL.createObjectURL(blob);

                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `proje_verileri_${new Date().toISOString().split('T')[0]}.json`;

                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                } else {
                    showError('Veri dışa aktarımında hata oluştu');
                }
            })
            .catch(error => {
                console.error('Export error:', error);
                showError('Dışa aktarım sırasında hata oluştu');
            })
            .finally(() => {
                hideLoading();
            });
    }

    function showRiskAnalysis() {
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }

        fetch(`/projects/${currentProject}/planning/risk-analysis`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalHtml = `
                        <div class="modal fade" id="riskAnalysisModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: ${data.risk_color}; color: white;">
                                        <h5 class="modal-title"><i class="mdi mdi-alert me-2"></i>Risk Analizi</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h3 style="color: ${data.risk_color}">${data.risk_level}</h3>
                                                        <p class="mb-0">Risk Seviyesi</p>
                                                        <small class="text-muted">Puan: ${data.risk_score}/10</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>Risk Faktörleri</h6>
                                                        <ul class="list-unstyled">
                                                            <li><span class="text-danger">●</span> Yüksek Riskli: ${data.risks.high_risk_tasks}</li>
                                                            <li><span class="text-warning">●</span> Geciken: ${data.risks.delayed_tasks}</li>
                                                            <li><span class="text-info">●</span> Kritik Yol: ${data.risks.critical_path_delays}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="mdi mdi-lightbulb me-2"></i>Öneriler</h6>
                                            </div>
                                            <div class="card-body">
                                                <ul>
                                                    ${data.recommendations.map(rec => `<li>${rec}</li>`).join('')}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Remove existing modal
                    const existing = document.getElementById('riskAnalysisModal');
                    if (existing) existing.remove();

                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                    const modal = new bootstrap.Modal(document.getElementById('riskAnalysisModal'));
                    modal.show();
                } else {
                    showError('Risk analizi yüklenemedi');
                }
            })
            .catch(error => {
                console.error('Risk analysis error:', error);
                showError('Risk analizi sırasında hata oluştu');
            });
    }

    function showResourceReport() {
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }

        fetch(`/projects/${currentProject}/planning/resource-report`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalHtml = `
                        <div class="modal fade" id="resourceReportModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="mdi mdi-account-multiple me-2"></i>Kaynak Raporu</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-4">
                                            <div class="col-md-4">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h3 class="text-success">${data.total_resources}</h3>
                                                        <p class="mb-0">Toplam Kaynak</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h3 class="text-info">${data.total_hours}</h3>
                                                        <p class="mb-0">Toplam Saat</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h3 class="text-warning">${data.most_utilized ? data.most_utilized.utilization + '%' : '0%'}</h3>
                                                        <p class="mb-0">En Yüksek Kullanım</p>
                                                        <small>${data.most_utilized ? data.most_utilized.name : '-'}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Kaynak Detayları</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Kaynak Adı</th>
                                                                <th>Görev Sayısı</th>
                                                                <th>Toplam Saat</th>
                                                                <th>Kullanım %</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            ${data.resources.map(resource => `
                                                                <tr>
                                                                    <td>${resource.name}</td>
                                                                    <td>${resource.task_count}</td>
                                                                    <td>${resource.hours}</td>
                                                                    <td>
                                                                        <div class="progress" style="height: 20px;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                 style="width: ${resource.utilization}%"
                                                                                 aria-valuenow="${resource.utilization}"
                                                                                 aria-valuemin="0" aria-valuemax="100">
                                                                                ${resource.utilization}%
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            `).join('')}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Remove existing modal
                    const existing = document.getElementById('resourceReportModal');
                    if (existing) existing.remove();

                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                    const modal = new bootstrap.Modal(document.getElementById('resourceReportModal'));
                    modal.show();
                } else {
                    showError('Kaynak raporu yüklenemedi');
                }
            })
            .catch(error => {
                console.error('Resource report error:', error);
                showError('Kaynak raporu sırasında hata oluştu');
            });
    }

    function showProgressReport() {
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }

        fetch(`/projects/${currentProject}/planning/two-week-lookahead`)
            .then(response => response.json())
            .then(data => {
                const modalHtml = `
					<div class="modal fade" id="progressModal" tabindex="-1">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header bg-info text-white">
									<h5 class="modal-title">2 Haftalık İlerleme Raporu</h5>
									<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-4">
											<h6 class="text-success">Başlayacak Görevler</h6>
											<ul class="list-unstyled">
												${data.starting.map(task => `
													<li><strong>${task.name}</strong><br>
													<small class="text-muted">${formatDate(task.start_date)}</small></li>
												`).join('')}
											</ul>
										</div>
										<div class="col-md-4">
											<h6 class="text-warning">Devam Eden</h6>
											<ul class="list-unstyled">
												${data.ongoing.map(task => `
													<li><strong>${task.name}</strong><br>
													<small class="text-muted">${task.progress}% tamamlandı</small></li>
												`).join('')}
											</ul>
										</div>
										<div class="col-md-4">
											<h6 class="text-info">Bitecek Görevler</h6>
											<ul class="list-unstyled">
												${data.ending.map(task => `
													<li><strong>${task.name}</strong><br>
													<small class="text-muted">${formatDate(task.end_date)}</small></li>
												`).join('')}
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				`;

				// Remove existing modal
				const existing = document.getElementById('progressModal');
				if (existing) existing.remove();

				document.body.insertAdjacentHTML('beforeend', modalHtml);
				const modal = new bootstrap.Modal(document.getElementById('progressModal'));
				modal.show();
            })
            .catch(error => {
                console.error('Progress report error:', error);
                showError('İlerleme raporu yüklenemedi');
            });
    }

    // Additional utility functions
    function importTasks() {
        showError('Import özelliği henüz aktif değil');
    }

    function createBaseline() {
        if (!currentProject) {
            showError('Lütfen önce bir proje seçin');
            return;
        }

        const name = prompt('Baseline adını girin:', `Baseline ${new Date().toLocaleDateString('tr-TR')}`);
        if (!name) return;

        fetch(`/projects/${currentProject}/planning/baselines`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            showSuccess('Baseline başarıyla oluşturuldu');
        })
        .catch(error => {
            console.error('Baseline error:', error);
            showError('Baseline oluştururken hata oluştu');
        });
    }

    function highlightCriticalPath() {
        // Highlight critical path tasks in current view
        document.querySelectorAll('.critical-path').forEach(el => {
            el.classList.add('highlight');
            el.style.animation = 'criticalPulse 2s ease-in-out 3';
        });

        setTimeout(() => {
            document.querySelectorAll('.critical-path.highlight').forEach(el => {
                el.classList.remove('highlight');
                el.style.animation = '';
            });
        }, 6000);

        showSuccess('Kritik yol görevleri vurgulandı');
    }

    // Make some functions globally available
    window.editTask = editTask;
    window.deleteTask = deleteTask;
    window.showNewTaskModal = showNewTaskModal;
    window.hideNewTaskModal = hideNewTaskModal;
    window.saveNewTask = saveNewTask;
    window.saveSettings = saveSettings;
})();
</script>

<script>
// Global modal handler: ensure any Bootstrap modal is appended to body and has proper z-index/margin
document.addEventListener('show.bs.modal', function(e) {
    try {
        const modal = e.target;
        if (modal && modal instanceof HTMLElement) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            // adjust dialog spacing
            const dialog = modal.querySelector('.modal-dialog');
            if (dialog) dialog.style.marginTop = '5.5rem';
            // ensure high z-index
            modal.style.zIndex = '200000';
        }
    } catch (err) {
        console.warn('Global show.bs.modal handler error:', err);
    }
});

// Cleanup backdrops on hide to avoid leftover overlay
document.addEventListener('hidden.bs.modal', function(e) {
    try {
        // remove any orphan backdrops
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        document.body.classList.remove('modal-open');
    } catch (err) {
        console.warn('Global hidden.bs.modal handler error:', err);
    }
});

// Also perform a small defensive cleanup on page show (to handle bfcache restores)
window.addEventListener('pageshow', function(e) {
    // remove stray backdrops from previous sessions
    setTimeout(() => {
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        document.body.classList.remove('modal-open');
    }, 50);
});

// End of global modal handlers
</script>


@endpush

