@extends('layouts.master')

@section('title','Proje Planlama - Zaman Çizelgesi')

@push('styles')
<style>
    /* MODERN GLASSMORPHISM STYLES - PROJECT CONSISTENT */
    .planning-header-modern {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.4));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        position: relative;
        overflow: hidden;
    }

    .planning-header-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(76, 110, 245, 0.1) 0%, transparent 50%);
        border-radius: 50%;
        z-index: 1;
    }

    .planning-header-modern .content {
        position: relative;
        z-index: 2;
    }

    .page-title-modern {
        font-size: 2.25rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .page-subtitle-modern {
        font-size: 1.1rem;
        color: #5a6a7a;
    }

    .header-actions .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        transition: all 0.2s ease;
    }

    .header-actions .btn-primary {
        background-color: #4c6ef5;
        border-color: #4c6ef5;
        box-shadow: 0 4px 12px rgba(76, 110, 245, 0.3);
    }

    .header-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 110, 245, 0.4);
    }

    /* MODERN KPI CARDS */
    .kpi-card-modern {
        position: relative;
        border: none;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .kpi-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.1);
    }

    .kpi-card-modern .card-body {
        position: relative;
        z-index: 2;
        padding: 1.75rem;
    }

    .kpi-icon-modern {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        font-size: 32px;
        transition: all 0.3s ease;
    }

    .kpi-card-modern:hover .kpi-icon-modern {
        transform: scale(1.1) rotate(-5deg);
    }

    .kpi-card-modern .kpi-title {
        font-size: 1rem;
        font-weight: 500;
        color: #495057;
    }

    .kpi-card-modern .kpi-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #212529;
        line-height: 1.2;
    }

    /* MODERN TABLE CONTAINER */
    .modern-table-container {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    .table-header {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.4));
        backdrop-filter: blur(10px);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.9);
    }

    .table-tools {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        min-width: 300px;
    }

    .search-box input {
        border-radius: 25px;
        padding-left: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
    }

    .search-box input:focus {
        border-color: #4c6ef5;
        box-shadow: 0 0 0 0.2rem rgba(76, 110, 245, 0.25);
    }

    /* MODERN FILTERS */
    .filter-section-modern {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    .filter-section-modern .form-label {
        font-weight: 600;
        color: #495057;
    }

    .filter-section-modern .form-control,
    .filter-section-modern .form-select {
        border: 1px solid rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 8px;
    }

    .filter-section-modern .form-control:focus,
    .filter-section-modern .form-select:focus {
        border-color: #4c6ef5;
        box-shadow: 0 0 0 0.2rem rgba(76, 110, 245, 0.25);
    }

    .filter-section-modern .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        transition: all 0.2s ease;
    }

    .filter-section-modern .btn-primary {
        background-color: #4c6ef5;
        border-color: #4c6ef5;
        box-shadow: 0 4px 12px rgba(76, 110, 245, 0.3);
    }

    .filter-section-modern .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 110, 245, 0.4);
    }

    /* MODERN TABS */
    .modern-tabs .nav-tabs {
        border: none;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 0.5rem;
    }

    .modern-tabs .nav-link {
        border: none;
        color: #6b7280;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .modern-tabs .nav-link.active {
        background: #4c6ef5;
        color: white;
        box-shadow: 0 4px 12px rgba(76, 110, 245, 0.3);
    }

    .modern-tabs .nav-link:hover:not(.active) {
        background: rgba(76, 110, 245, 0.1);
        color: #4c6ef5;
    }

    /* MODERN DETAIL PANEL */
    .modern-detail-panel {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        position: sticky;
        top: 20px;
    }

    .modern-detail-panel .card-header {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.4));
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 16px 16px 0 0 !important;
    }

    /* GANTT CONTAINER */
    .gantt-container-modern {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        min-height: 500px;
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 768px) {
        .planning-header-modern {
            padding: 1.5rem;
            text-align: center;
        }

        .planning-header-modern .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .filter-section-modern {
            padding: 1rem;
        }

        .table-tools {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .modern-tabs .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
@section('content')
<div class="container-fluid py-4">
    <!-- Modern Header Section -->
    <div class="planning-header-modern mb-4">
        <div class="content">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title-modern mb-1">Proje Planlama</h1>
                    <p class="page-subtitle-modern mb-0">Zaman çizelgesi ve kaynak yönetimi</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0 header-actions">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Yeni Proje
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Filter Section -->
    <div class="filter-section-modern mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Proje Seçimi</label>
                <select id="projectSelect" class="form-select">
                    <option value="">Tüm Projeler</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Zaman Aralığı</label>
                <select id="timeRange" class="form-select">
                    <option value="all">Tüm Zaman</option>
                    <option value="month">Bu Ay</option>
                    <option value="quarter">Bu Çeyrek</option>
                    <option value="year">Bu Yıl</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Görünüm</label>
                <select id="viewMode" class="form-select">
                    <option value="gantt">Gantt Grafiği</option>
                    <option value="list">Liste Görünümü</option>
                    <option value="calendar">Takvim Görünümü</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card kpi-card-modern kpi-total-tasks">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-modern me-3">
                            <i class="fas fa-tasks text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="kpi-title mb-1">Toplam Görev</h6>
                            <h3 class="kpi-value mb-0" id="totalTasks">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card kpi-card-modern kpi-completed-tasks">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-modern me-3">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="kpi-title mb-1">Tamamlanan</h6>
                            <h3 class="kpi-value mb-0" id="completedTasks">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card kpi-card-modern kpi-overdue-tasks">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-modern me-3">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="kpi-title mb-1">Geciken</h6>
                            <h3 class="kpi-value mb-0" id="overdueTasks">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card kpi-card-modern kpi-progress">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon-modern me-3">
                            <i class="fas fa-chart-line text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="kpi-title mb-1">Genel İlerleme</h6>
                            <h3 class="kpi-value mb-0" id="overallProgress">0%</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <!-- Modern Planning Views -->
            <div class="modern-table-container mb-3">
                <div class="table-header">
                    <div class="modern-tabs">
                        <ul class="nav nav-tabs" id="planningViews" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#ganttView">
                                    <i class="fas fa-chart-gantt me-2"></i>Gantt Grafiği
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#listView">
                                    <i class="fas fa-list me-2"></i>Liste Görünümü
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#calendarView">
                                    <i class="fas fa-calendar me-2"></i>Takvim Görünümü
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content">
                        <div id="ganttView" class="tab-pane active">
                            <div class="gantt-container-modern p-4">
                                <div id="frappeGantt" style="height:100%"></div>
                                <div id="ganttOverlay" style="position:absolute;left:0;top:0;right:0;bottom:0;pointer-events:none;"></div>
                            </div>
                        </div>
                        <div id="listView" class="tab-pane">
                            <div class="table-responsive p-3">
                                <table class="table table-hover mb-0" id="planningTasksTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th><i class="fas fa-tasks me-1"></i>Görev</th>
                                            <th><i class="fas fa-calendar-plus me-1"></i>Başlangıç</th>
                                            <th><i class="fas fa-calendar-check me-1"></i>Bitiş</th>
                                            <th><i class="fas fa-clock me-1"></i>Süre</th>
                                            <th><i class="fas fa-percent me-1"></i>%Tamam</th>
                                            <th><i class="fas fa-user me-1"></i>Sorumlu</th>
                                            <th><i class="fas fa-cogs me-1"></i>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                                <br>Görev verisi yükleniyor...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="calendarView" class="tab-pane">
                            <div class="gantt-container-modern p-4">
                                <div id="calendarContainer" style="height:500px;">
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-calendar-alt fa-3x mb-3 text-muted"></i>
                                        <h5>Takvim Görünümü</h5>
                                        <p>FullCalendar entegrasyonu ile interaktif takvim burada görüntülenecek.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Action Buttons -->
            <div class="modern-table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex gap-2 flex-wrap">
                            <button id="btnNewTask" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Yeni Görev Ekle
                            </button>
                            <button id="btnImport" class="btn btn-outline-secondary">
                                <i class="fas fa-upload me-1"></i>İçe Aktar
                            </button>
                            <button id="btnBaseline" class="btn btn-outline-primary">
                                <i class="fas fa-bookmark me-1"></i>Baseline Oluştur
                            </button>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>Kritik yol ve kaynak analizi için panelleri kullanın
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <!-- Modern Detail Panel -->
            <div id="detailPanel" class="modern-detail-panel" style="display:none;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-info-circle me-2"></i>Görev Detayları</span>
                    <button id="closeDetail" class="btn btn-sm btn-link p-0 text-muted">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="card-body p-3">
                    <ul class="nav nav-tabs nav-fill" id="detailTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabDetails">
                                <i class="fas fa-info me-1"></i>Detay
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabResources">
                                <i class="fas fa-users me-1"></i>Kaynak
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabBudget">
                                <i class="fas fa-dollar-sign me-1"></i>Bütçe
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabNotes">
                                <i class="fas fa-sticky-note me-1"></i>Notlar
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="detailContent">
                        <div id="tabDetails" class="tab-pane active">
                            <div id="taskDetails">
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-mouse-pointer fa-2x mb-2"></i>
                                    <p>Görev detaylarını görmek için bir göreve tıklayın.</p>
                                </div>
                            </div>
                        </div>
                        <div id="tabResources" class="tab-pane">
                            <div id="taskResources">
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <p>Kaynak bilgileri burada yüklenecek.</p>
                                </div>
                            </div>
                        </div>
                        <div id="tabBudget" class="tab-pane">
                            <div id="taskBudget">
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                                    <p>Bütçe bağlantısı burada görüntülenecek.</p>
                                </div>
                            </div>
                        </div>
                        <div id="tabNotes" class="tab-pane">
                            <div id="taskNotes">
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-sticky-note fa-2x mb-2"></i>
                                    <p>Notlar ve yorumlar burada görüntülenecek.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="dependencyEditor" style="display:none;margin-top:15px;">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-link me-1"></i>Bağımlılıklar
                        </label>
                        <select id="dependencySelect" class="form-select" multiple style="height:120px;">
                        </select>
                        <div class="mt-3 d-flex justify-content-end">
                            <button id="saveDependencies" class="btn btn-primary btn-sm">
                                <i class="fas fa-save me-1"></i>Kaydet
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Analysis Panel -->
            <div class="modern-detail-panel mt-3">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-2"></i>Analiz & Raporlama
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-chart-area text-primary me-1"></i>Kaynak Yükleme Grafiği
                        </h6>
                        <div class="gantt-container-modern p-3" style="min-height:120px;">
                            <canvas id="histogramChart" width="400" height="120"></canvas>
                        </div>
                        <button id="btnLoadHistogram" class="btn btn-outline-primary btn-sm w-100 mt-2">
                            <i class="fas fa-sync me-1"></i>Yenile
                        </button>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-eye text-info me-1"></i>İki Haftalık Bakış
                        </h6>
                        <div class="gantt-container-modern p-3" style="max-height:200px;overflow:auto;">
                            <div id="lookaheadReport" class="text-muted">
                                <i class="fas fa-clock me-1"></i>Rapor yükleniyor...
                            </div>
                        </div>
                        <button id="btnLookahead" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="fas fa-file-alt me-1"></i>Raporu Hazırla
                        </button>
                    </div>

                    <hr>

                    <div>
                        <h6 class="fw-semibold mb-3">
                            <i class="fas fa-balance-scale text-warning me-1"></i>Baseline Karşılaştırma
                        </h6>
                        <div class="mb-2">
                            <select id="baselineSelect" class="form-select form-select-sm">
                                <option value="">Varsayılan (yok)</option>
                            </select>
                        </div>
                        <button id="btnCompareBaseline" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-exchange-alt me-1"></i>Karşılaştır
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Baseline Compare Modal -->
<div class="modal fade" id="baselineCompareModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Baseline Karşılaştırma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="modal-body" id="baselineCompareBody">
                <div id="baselineCompareContent">Yükleniyor...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* small helpers for gantt placeholders */
#planningTasksTable tbody td { vertical-align: middle; }
#detailPanel { max-height: 80vh; overflow:auto; }
/* toastr minimal */
/* toastr minimal */
.toast-fixed { position: fixed; right: 20px; top: 20px; z-index: 9999; }
.gantt .bar.critical-path { background: #dc3545 !important; border-color: #dc3545 !important; }
</style>
@endpush

@push('scripts')
<!-- Frappe Gantt Local -->
<link rel="stylesheet" href="{{ asset('css/frappe-gantt.css') }}">
<script src="{{ asset('js/frappe-gantt.min.js') }}"></script>
<!-- Chart.js for histogram -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
(function(){
    // Wrap everything to avoid a single exception breaking all bindings
    try{
    // minimal fallback toast so catch blocks can call it if needed
    function _fallbackToast(msg){ try{ console.log('TOAST:',msg); }catch(e){} }

    // utility: safe JSON parse
    function tryParseJson(s){ try { return JSON.parse(s); } catch(e){ return s; } }

    // helper to fetch JSON with credentials and content-type checking
    function fetchJson(url, opts){
        opts = opts || {};
        opts.credentials = opts.credentials || 'same-origin';
        return fetch(url, opts).then(function(res){
            var ct = (res.headers.get('content-type') || '').toLowerCase();
            if (!res.ok) {
                return res.text().then(function(text){
                    var body = (ct.indexOf('application/json') !== -1) ? tryParseJson(text) : text;
                    throw new Error('HTTP ' + res.status + ' - ' + (body && body.message ? body.message : (typeof body === 'string' ? body.substr(0,200) : JSON.stringify(body))));
                });
            }
            if (ct.indexOf('application/json') === -1) {
                return res.text().then(function(text){ console.error('Expected JSON from', url, 'got:', text); throw new Error('Sunucudan beklenmeyen yanıt alındı (JSON değil). Oturumunuz sona ermiş olabilir.'); });
            }
            return res.json();
        });
    }
    // basic UI wiring for the scaffold (guard each element exists)
    var _el;
    _el = document.getElementById('btnNewTask'); if (_el) _el.addEventListener('click', function(){ alert('Yeni görev ekleme modalı açılacak (henüz implement yok)'); });
    // Import button (was missing handler)
    _el = document.getElementById('btnImport'); if (_el) _el.addEventListener('click', function(){ alert('Import akışı (CSV/Excel) burada başlayacak (demo)'); });
    _el = document.getElementById('btnBaseline'); if (_el) _el.addEventListener('click', function(){ alert('Baseline oluşturma akışı burada başlayacak'); });
    _el = document.getElementById('btnCompareBaseline'); if (_el) _el.addEventListener('click', function(){ alert('Baseline karşılaştırması yapılacak (demo)'); });
    _el = document.getElementById('btnLookahead'); if (_el) _el.addEventListener('click', function(){ alert('2-Week Lookahead raporu oluşturuluyor (demo)'); });

    // detail panel demo
    var detail = document.getElementById('detailPanel');
    var detailBody = document.getElementById('detailBody');
    function openDetail(html){ if(detailBody) detailBody.innerHTML = html; if(detail) detail.style.display = 'block'; }
    function closeDetail(){ if(detail) detail.style.display = 'none'; }
    _el = document.getElementById('closeDetail'); if (_el) _el.addEventListener('click', closeDetail);

    // fetch tasks for selected project
    var projectSelect = document.getElementById('projectSelect');
    function loadTasks(projectId){
        var tbody = document.querySelector('#planningTasksTable tbody');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Yükleniyor...</td></tr>';
        if (!projectId) { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Lütfen bir proje seçin</td></tr>'; return; }


    fetchJson('/projects/' + projectId + '/planning/tasks')
        .then(function(data){
                console.log('Loaded tasks data:', data);
                if (!data.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Bu projede görev yok</td></tr>'; return; }
                tbody.innerHTML = '';
                // prepare tasks for frappe-gantt
                var ganttTasks = data.map(function(t){
                    return {
                        id: t.id.toString(),
                        name: t.name,
                        start: t.start_date || new Date().toISOString().substr(0,10),
                        end: t.end_date || t.start_date || new Date().toISOString().substr(0,10),
                        progress: t.progress || 0,
                        custom_class: t.critical_path ? 'critical-path' : ''
                    };
                });
                // keep latest tasks globally for interactions
                window.planningTasks = data;
                // render gantt
                if (window.ganttInstance) {
                    window.ganttInstance.refresh(ganttTasks);
                } else {
                    console.log('Creating Gantt with tasks:', ganttTasks);
                    window.ganttInstance = new Gantt('#frappeGantt', ganttTasks, {
                        on_date_change: function(task, start, end) {
                            console.log('Date change:', task, start, end);
                            // optimistic toast
                            showToast('Görev güncelleniyor...', 'info');
                            // send update to backend
                                                        fetchJson('/projects/' + projectId + '/planning/tasks/' + task.id, {
                                                                method: 'PUT',
                                                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                                                                body: JSON.stringify({ start_date: start, end_date: end })
                                                        }).then(function(updated){ showToast('Görev kaydedildi', 'success'); console.log('updated', updated); })
                                                            .catch(function(e){ showToast('Güncelleme başarısız: ' + e.message, 'danger'); console.error(e); });
                        }
                    });
                }
                    // draw dependency lines and attach handlers after render
                    setTimeout(function(){ drawDependencyLines(window.planningTasks); attachBarDragHandlers(); }, 200);

                data.forEach(function(t){
                    var tr = document.createElement('tr');
                    tr.innerHTML = '<td>'+ (t.name || '-') +'</td>'+
                                   '<td>'+ (t.start_date || '-') +'</td>'+
                                   '<td>'+ (t.end_date || '-') +'</td>'+
                                   '<td>'+ (t.duration || '-') +'</td>'+
                                   '<td>'+ (t.progress || 0) +'%</td>'+
                                   '<td>'+ (t.responsible || '-') +'</td>'+
                                   '<td><button class="btn btn-sm btn-outline-primary">Detay</button></td>';
                    tbody.appendChild(tr);
                    tr.querySelector('button').addEventListener('click', function(){
                        openDetail(t);
                    });
                });
            })
            .catch(function(e){ tbody.innerHTML = '<tr><td colspan="7" class="text-danger">Veri yüklenemedi</td></tr>'; });
    }

    function openDetail(task){
        fetchJson('/projects/' + projectSelect.value + '/planning/tasks/' + task.id + '/details')
            .then(function(details){
                // Fill details tab
                document.getElementById('taskDetails').innerHTML = `
                    <strong>Görev Adı:</strong> ${details.name}<br>
                    <strong>Başlangıç:</strong> ${details.start_date || '-'}<br>
                    <strong>Bitiş:</strong> ${details.end_date || '-'}<br>
                    <strong>Süre:</strong> ${details.duration || '-'} gün<br>
                    <strong>%Tamam:</strong> ${details.progress || 0}%<br>
                    <strong>Sorumlu:</strong> ${details.responsible || '-'}<br>
                    <strong>Kritik Yol:</strong> ${details.critical_path ? '<span class="badge badge-danger">Evet</span>' : 'Hayır'}
                `;
                // Resources tab
                let resHtml = '<ul class="list-group">';
                if (details.resources && details.resources.human) {
                    resHtml += '<li class="list-group-item"><strong>İnsan Gücü:</strong></li>';
                    details.resources.human.forEach(h => resHtml += `<li class="list-group-item">${h.role}: ${h.count} kişi, ${h.hours || 0} saat</li>`);
                }
                if (details.resources && details.resources.equipment) {
                    resHtml += '<li class="list-group-item"><strong>Ekipman:</strong></li>';
                    details.resources.equipment.forEach(e => resHtml += `<li class="list-group-item">${e.name}: ${e.count} adet</li>`);
                }
                resHtml += '</ul>';
                document.getElementById('taskResources').innerHTML = resHtml;
                // Budget tab
                document.getElementById('taskBudget').innerHTML = `<strong>WBS Bağlantısı:</strong> ${details.budget_wbs || 'Yok'}`;
                // Documents tab
                let docHtml = '<ul class="list-group">';
                if (details.documents) {
                    details.documents.forEach(d => docHtml += `<li class="list-group-item"><a href="#" onclick="openDoc(${d.id})">${d.name} (${d.type})</a></li>`);
                }
                docHtml += '</ul>';
                document.getElementById('taskDocuments').innerHTML = docHtml;
                // Notes tab
                document.getElementById('taskNotes').innerHTML = `<textarea class="form-control" rows="4">${details.notes || ''}</textarea><button class="btn btn-sm btn-primary mt-2" onclick="saveNotes(${task.id}, this.previousElementSibling.value)">Kaydet</button>`;
                // Show panel
                detail.style.display = 'block';
                // Populate dependency editor
                populateDependencyEditor(task, window.planningTasks);
            })
            .catch(function(e){ showToast('Detay yüklenemedi: '+e.message, 'danger'); });
    }

    if (projectSelect) {
        projectSelect.addEventListener('change', function(){ loadTasks(this.value); });
        // if a project is already selected on page load, auto-load its tasks
        if (projectSelect.value) { setTimeout(function(){ loadTasks(projectSelect.value); loadBaselines(projectSelect.value); }, 50); }
    }

        // Toast helper (minimal)
        function showToast(message, type){
            try{
                var container = document.getElementById('toastContainer');
                if (!container){ container = document.createElement('div'); container.id = 'toastContainer'; container.className = 'toast-fixed'; document.body.appendChild(container); }
                var t = document.createElement('div'); t.className = 'alert alert-' + (type || 'info') + ' shadow-sm'; t.style.marginBottom = '8px'; t.innerText = message;
                container.appendChild(t);
                setTimeout(function(){ t.classList.add('fade'); t.style.transition = 'opacity 0.4s'; t.style.opacity = 0; setTimeout(function(){ try{ container.removeChild(t); }catch(e){} }, 400); }, 3000);
            } catch(e){ _fallbackToast(message); }
        }

        // populate dependency multiselect with current project tasks and mark current dependencies
        function populateDependencyEditor(task, allTasks){
            var sel = document.getElementById('dependencySelect');
            sel.innerHTML = '';
            allTasks.forEach(function(t){
                if (t.id == task.id) return; // can't depend on self
                var opt = document.createElement('option'); opt.value = t.id; opt.text = t.name + ' (' + (t.start_date||'-') + ' - ' + (t.end_date||'-') + ')';
                sel.appendChild(opt);
            });
            // select existing dependencies
            try {
                var deps = task.dependencies || [];
                for (var i=0;i<sel.options.length;i++){ if (deps.indexOf(parseInt(sel.options[i].value)) !== -1) sel.options[i].selected = true; }
            } catch(e) { }
            document.getElementById('dependencyEditor').style.display = 'block';
            // attach save handler (once)
            var btn = document.getElementById('saveDependencies');
            btn.onclick = function(){
                var selected = Array.prototype.slice.call(sel.selectedOptions).map(function(o){ return parseInt(o.value); });
                showToast('Bağımlılıklar kaydediliyor...', 'info');
                                fetchJson('/projects/' + projectSelect.value + '/planning/tasks/' + task.id, {
                                        method: 'PUT',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                                        body: JSON.stringify({ dependencies: selected })
                                }).then(function(updated){ showToast('Bağımlılıklar kaydedildi', 'success'); loadTasks(projectSelect.value); })
                                    .catch(function(e){ showToast('Kaydetme başarısız: '+e.message, 'danger'); });
            };
        }

        // baseline flow: when clicking baseline button, create baseline for selected project
        var _btnBaseline = document.getElementById('btnBaseline'); if (_btnBaseline) _btnBaseline.addEventListener('click', function(){
            var pid = projectSelect.value; if (!pid) { showToast('Lütfen önce bir proje seçin', 'warning'); return; }
            var name = prompt('Baseline adı (isteğe bağlı):', 'Baseline ' + new Date().toISOString().substr(0,10));
            showToast('Baseline oluşturuluyor...', 'info');
            fetchJson('/projects/' + pid + '/planning/baselines', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ name: name })
            }).then(function(j){ showToast('Baseline oluşturuldu', 'success'); loadBaselines(pid); }).catch(function(e){ showToast('Baseline oluşturulamadı: '+e.message, 'danger'); });
    });

    function loadBaselines(projectId){
            if (!projectId) return;
            fetchJson('/projects/' + projectId + '/planning/baselines').then(function(list){
                var sel = document.getElementById('baselineSelect'); sel.innerHTML = '<option value="">Varsayılan (yok)</option>';
                list.forEach(function(b){ var opt = document.createElement('option'); opt.value = b.id; opt.text = (b.name||('Baseline '+b.id)) + ' - ' + new Date(b.created_at).toLocaleString(); sel.appendChild(opt); });
            }).catch(function(e){ console.error(e); });
        }

        // when project changes, reload baselines as well
        projectSelect.addEventListener('change', function(){ loadBaselines(this.value); });

        // Load resource histogram
        document.getElementById('btnLoadHistogram').addEventListener('click', function(){
            var pid = projectSelect.value; if (!pid) { showToast('Proje seçin', 'warning'); return; }
            fetchJson('/projects/' + pid + '/planning/resource-histogram')
                .then(data => {
                    var ctx = document.getElementById('histogramChart').getContext('2d');
                    var labels = Object.keys(data);
                    var humanData = labels.map(w => Object.values(data[w].human).reduce((a,b)=>a+b,0));
                    var equipData = labels.map(w => Object.values(data[w].equipment).reduce((a,b)=>a+b,0));
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'İnsan Gücü', data: humanData, backgroundColor: 'rgba(54, 162, 235, 0.5)' },
                                { label: 'Ekipman', data: equipData, backgroundColor: 'rgba(255, 99, 132, 0.5)' }
                            ]
                        },
                        options: { responsive: true, scales: { y: { beginAtZero: true } } }
                    });
                })
                .catch(e => showToast('Histogram yüklenemedi: '+e.message, 'danger'));
        });

        document.getElementById('btnLookahead').addEventListener('click', function(){
            var pid = projectSelect.value; if (!pid) { showToast('Proje seçin', 'warning'); return; }
            fetchJson('/projects/' + pid + '/planning/two-week-lookahead')
                .then(data => {
                    var html = '<h6>Başlayacak Görevler:</h6><ul>';
                    data.starting.forEach(t => html += `<li>${t.name} (${t.start_date})</li>`);
                    html += '</ul><h6>Devam Eden:</h6><ul>';
                    data.ongoing.forEach(t => html += `<li>${t.name} (${t.progress}%)</li>`);
                    html += '</ul><h6>Bitecek:</h6><ul>';
                    data.ending.forEach(t => html += `<li>${t.name} (${t.end_date})</li>`);
                    html += '</ul>';
                    document.getElementById('lookaheadReport').innerHTML = html;
                })
                .catch(e => showToast('Rapor yüklenemedi: '+e.message, 'danger'));
        });

        document.getElementById('btnCompareBaseline').addEventListener('click', function(){
            var pid = projectSelect.value; var bid = document.getElementById('baselineSelect').value;
            if (!pid) { showToast('Lütfen proje seçin', 'warning'); return; }
            if (!bid) { showToast('Lütfen karşılaştırılacak baseline seçin', 'warning'); return; }
            var modal = document.getElementById('baselineCompareModal');
            $('#baselineCompareModal').modal('show');
            document.getElementById('baselineCompareContent').innerHTML = 'Yükleniyor...';
            fetchJson('/projects/' + pid + '/planning/baselines/' + bid + '/compare')
                .then(function(j){ renderBaselineDiff(j.diffs || []); })
                .catch(function(e){ document.getElementById('baselineCompareContent').innerHTML = '<div class="text-danger">Karşılaştırma başarısız: '+e.message+'</div>'; });
        });

        function renderBaselineDiff(diffs){
            var out = '';
            if (!diffs || Object.keys(diffs).length === 0) { out = '<div class="text-muted">Fark yok</div>'; document.getElementById('baselineCompareContent').innerHTML = out; return; }
            out += '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Görev</th><th>Alan</th><th>Baseline</th><th>Mevcut</th></tr></thead><tbody>';
            Object.keys(diffs).forEach(function(k){
                var item = diffs[k]; var task = item.task || ('#'+k);
                // make task a link (if route available, adapt); here we link to project planning details anchor
                var taskHtml = '<a href="#" class="text-decoration-none">'+task+'</a>';
                Object.keys(item.changes).forEach(function(field){
                    var change = item.changes[field];
                    var baseVal = (change.baseline===null?'-':change.baseline);
                    var curVal = (change.current===null?'-':change.current);
                    var cls = '';
                    // colorize changed numeric/date fields
                    if (baseVal !== curVal) {
                        // dates: show green if schedule improved (earlier start or earlier finish), red if delayed
                        if (field === 'start_date' || field === 'end_date') {
                            try {
                                var b = new Date(baseVal); var c = new Date(curVal);
                                if (!isNaN(b.getTime()) && !isNaN(c.getTime())) {
                                    cls = (c > b) ? 'text-danger' : 'text-success';
                                } else { cls = 'text-warning'; }
                            } catch(e){ cls = 'text-warning'; }
                        } else {
                            // numeric progress/duration: increased duration or decreased progress -> red
                            if (field === 'duration') { cls = (parseInt(curVal) > parseInt(baseVal)) ? 'text-danger' : 'text-success'; }
                            else if (field === 'progress') { cls = (parseInt(curVal) < parseInt(baseVal)) ? 'text-danger' : 'text-success'; }
                            else { cls = 'text-warning'; }
                        }
                    }
                    out += '<tr><td>'+taskHtml+'</td><td>'+field+'</td><td>'+'<span class="'+cls+'">'+baseVal+'</span>'+'</td><td>'+'<span class="'+cls+'">'+curVal+'</span>'+'</td></tr>';
                });
            });
            out += '</tbody></table></div>';
            document.getElementById('baselineCompareContent').innerHTML = out;
        }

        // draw dependency lines on top of Gantt using an SVG overlay
        function drawDependencyLines(tasks){
            var overlay = document.getElementById('ganttOverlay');
            if (!overlay) return;
            // ensure SVG exists
            var svg = overlay.querySelector('svg');
            if (!svg) { svg = document.createElementNS('http://www.w3.org/2000/svg','svg'); svg.setAttribute('width','100%'); svg.setAttribute('height','100%'); svg.style.position='absolute'; svg.style.left=0; svg.style.top=0; overlay.appendChild(svg); }
            // clear existing lines
            while (svg.firstChild) svg.removeChild(svg.firstChild);
            if (!tasks || !tasks.length) return;
            var container = document.querySelector('#frappeGantt');
            var crect = container.getBoundingClientRect();

            function findElById(id){
                // try several selectors used by frappe-gantt variations
                return container.querySelector('[data-id="'+id+'"]') || container.querySelector('.bar[data-id="'+id+'"]') || document.getElementById('task-'+id) || document.getElementById('bar-'+id) || container.querySelector('[id$="-'+id+'"]');
            }

            var tasksById = {};
            tasks.forEach(function(t){ tasksById[t.id] = t; });

            tasks.forEach(function(t){
                var deps = t.dependencies || [];
                deps.forEach(function(d){
                    var srcEl = findElById(t.id);
                    var tgtEl = findElById(d);
                    if (!srcEl || !tgtEl) return;
                    var s = srcEl.getBoundingClientRect();
                    var e = tgtEl.getBoundingClientRect();
                    var x1 = s.left - crect.left + s.width/2;
                    var y1 = s.top - crect.top + s.height/2;
                    var x2 = e.left - crect.left + e.width/2;
                    var y2 = e.top - crect.top + e.height/2;
                    var line = document.createElementNS('http://www.w3.org/2000/svg','line');
                    line.setAttribute('x1', x1); line.setAttribute('y1', y1); line.setAttribute('x2', x2); line.setAttribute('y2', y2);
                    line.setAttribute('stroke', '#6c757d'); line.setAttribute('stroke-width', '2'); line.setAttribute('marker-end','url(#arrow)');
                    svg.appendChild(line);
                });
            });
            // add arrow marker if not present
            if (!svg.querySelector('marker')){
                var defs = document.createElementNS('http://www.w3.org/2000/svg','defs');
                var marker = document.createElementNS('http://www.w3.org/2000/svg','marker');
                marker.setAttribute('id','arrow'); marker.setAttribute('markerWidth','10'); marker.setAttribute('markerHeight','10'); marker.setAttribute('refX','6'); marker.setAttribute('refY','3'); marker.setAttribute('orient','auto');
                var path = document.createElementNS('http://www.w3.org/2000/svg','path'); path.setAttribute('d','M0,0 L0,6 L9,3 z'); path.setAttribute('fill','#6c757d');
                marker.appendChild(path); defs.appendChild(marker); svg.appendChild(defs);
            }
        }

        // JS-side cycle detection to warn user before server call
        function hasCycleJS(adj){
            var visited = {}, rec = {};
            function dfs(n){
                if (!visited[n]){
                    visited[n]=true; rec[n]=true;
                    var neighbors = adj[n] || [];
                    for(var i=0;i<neighbors.length;i++){ var m = neighbors[i]; if(!visited[m] && dfs(m)) return true; if(rec[m]) return true; }
                }
                rec[n]=false; return false;
            }
            for(var k in adj){ if(!visited[k] && dfs(k)) return true; }
            return false;
        }

        // update populateDependencyEditor to client-check cycles before sending
        function populateDependencyEditor(task, allTasks){
            var sel = document.getElementById('dependencySelect');
            sel.innerHTML = '';
            allTasks.forEach(function(t){ if (t.id == task.id) return; var opt = document.createElement('option'); opt.value = t.id; opt.text = t.name + ' (' + (t.start_date||'-') + ' - ' + (t.end_date||'-') + ')'; sel.appendChild(opt); });
            try { var deps = task.dependencies || []; for (var i=0;i<sel.options.length;i++){ if (deps.indexOf(parseInt(sel.options[i].value)) !== -1) sel.options[i].selected = true; } } catch(e) { }
            document.getElementById('dependencyEditor').style.display = 'block';
            var btn = document.getElementById('saveDependencies');
            btn.onclick = function(){
                var selected = Array.prototype.slice.call(sel.selectedOptions).map(function(o){ return parseInt(o.value); });
                // build adjacency for client-side check
                var adj = {};
                allTasks.forEach(function(t){ adj[t.id] = (t.dependencies || []).map(function(x){ return parseInt(x); }); });
                adj[task.id] = selected.slice();
                if (hasCycleJS(adj)) { showToast('Bu seçim döngü oluşturuyor. Lütfen bağımlılıkları gözden geçirin.', 'danger'); return; }
                showToast('Bağımlılıklar kaydediliyor...', 'info');
                                fetchJson('/projects/' + projectSelect.value + '/planning/tasks/' + task.id, {
                                        method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ dependencies: selected })
                                }).then(function(updated){ showToast('Bağımlılıklar kaydedildi', 'success'); loadTasks(projectSelect.value); })
                                    .catch(function(e){ showToast('Kaydetme başarısız: '+e.message, 'danger'); });
            };
        }

        // Attach drag-to-connect handlers to gantt bars
        function attachBarDragHandlers(){
            var container = document.querySelector('#frappeGantt');
            if (!container) return;
            // remove previous listeners if any
            container.querySelectorAll('.gantt-bar, .bar').forEach(function(b){ b.onmousedown = null; b.onmouseup = null; b.ondragstart = function(){ return false; }; });

            container.querySelectorAll('[data-id], .bar, .gantt-bar').forEach(function(bar){
                bar.style.cursor = 'pointer';
                bar.addEventListener('mousedown', function(e){
                    // start a drag line if user holds ctrl (to avoid interfering with drag-to-move)
                    if (!e.ctrlKey && !e.metaKey) return;
                    e.preventDefault();
                    var startEl = bar;
                    var overlay = document.getElementById('ganttOverlay');
                    var svg = overlay.querySelector('svg'); if(!svg){ svg = document.createElementNS('http://www.w3.org/2000/svg','svg'); svg.setAttribute('width','100%'); svg.setAttribute('height','100%'); svg.style.position='absolute'; svg.style.left=0; svg.style.top=0; overlay.appendChild(svg); }
                    var tempLine = document.createElementNS('http://www.w3.org/2000/svg','line'); tempLine.setAttribute('stroke','#007bff'); tempLine.setAttribute('stroke-dasharray','4'); tempLine.setAttribute('stroke-width','2'); svg.appendChild(tempLine);
                    var rect = container.getBoundingClientRect();
                    function moveHandler(ev){
                        var x = ev.clientX - rect.left; var y = ev.clientY - rect.top;
                        var s = startEl.getBoundingClientRect(); var sx = s.left - rect.left + s.width/2; var sy = s.top - rect.top + s.height/2;
                        tempLine.setAttribute('x1', sx); tempLine.setAttribute('y1', sy); tempLine.setAttribute('x2', x); tempLine.setAttribute('y2', y);
                    }
                    function upHandler(ev){
                        document.removeEventListener('mousemove', moveHandler); document.removeEventListener('mouseup', upHandler);
                        // detect target bar under pointer
                        var dropEl = document.elementFromPoint(ev.clientX, ev.clientY);
                        // try to walk up to a bar element
                        while(dropEl && dropEl !== container && !dropEl.getAttribute('data-id') && !dropEl.classList.contains('bar') && !dropEl.classList.contains('gantt-bar')){ dropEl = dropEl.parentElement; }
                        svg.removeChild(tempLine);
                        if (!dropEl || dropEl === startEl || dropEl === container) return;
                        // find ids
                        var srcId = startEl.getAttribute('data-id') || (startEl.id || '').replace('task-','').replace('bar-','');
                        var tgtId = dropEl.getAttribute('data-id') || (dropEl.id || '').replace('task-','').replace('bar-','');
                        if (!srcId || !tgtId) return;
                        srcId = parseInt(srcId); tgtId = parseInt(tgtId);
                        // build adjacency and client-side cycle check
                        var adj = {};
                        (window.planningTasks||[]).forEach(function(t){ adj[t.id] = (t.dependencies||[]).map(function(x){ return parseInt(x); }); });
                        // add new edge src -> tgt
                        adj[srcId] = adj[srcId] || []; if (adj[srcId].indexOf(tgtId) === -1) adj[srcId].push(tgtId);
                        if (hasCycleJS(adj)) { showToast('Bu işlem döngü oluşturuyor. Bağımlılık eklenmedi.', 'danger'); return; }
                        // persist to server: add tgt to src dependencies
                        showToast('Bağımlılık ekleniyor...', 'info');
                                                fetchJson('/projects/' + projectSelect.value + '/planning/tasks/' + srcId, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ dependencies: adj[srcId] }) })
                                                    .then(function(updated){ showToast('Bağımlılık eklendi', 'success'); loadTasks(projectSelect.value); })
                                                    .catch(function(e){ showToast('Kaydetme başarısız: '+e.message, 'danger'); });
                    }
                    document.addEventListener('mousemove', moveHandler);
                    document.addEventListener('mouseup', upHandler);
                });
            });
        }

    // override earlier definition by placing new function in scope
    function openDoc(docId){ showToast('Doküman açılıyor (entegrasyon eksik)', 'info'); }
        function saveNotes(taskId, notes){
            fetchJson('/projects/' + projectSelect.value + '/planning/tasks/' + taskId, {
                method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ notes: notes })
            }).then(() => showToast('Notlar kaydedildi', 'success')).catch(e => showToast('Kaydetme başarısız: '+e.message, 'danger'));
        }
    } catch(e){ console.error('Planning inline script error:', e); try{ showToast('İç script hatası: '+e.message, 'danger'); }catch(_){} }
})();
</script>
@endpush

@endsection
