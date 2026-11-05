@extends('layouts.master')

@section('title', 'Kalite Kontrol')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="page-title">Kalite Kontrol</h4>
                    <p class="text-muted">Projenin şartnamelere ve standartlara uygunluğunu takip edin. Denetimler, kontrol listeleri ve eksik işler burada yönetilir.</p>
                </div>
                <div class="text-right">
                    <button id="btn-new-inspection" class="btn btn-primary btn-sm">➕ Yeni Denetim</button>
                    <button id="btn-new-template" class="btn btn-outline-secondary btn-sm">✚ Şablon Oluştur</button>
                    <a id="export-json" class="btn btn-light btn-sm" href="#">💾 JSON Dışa Aktar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Denetimler</h5>
                        <div class="d-flex align-items-center">
                            <input id="template-search" class="form-control form-control-sm mr-2" placeholder="Şablonları ara, denetimleri filtrele..." style="min-width:220px;" />
                            <select id="filter-status" class="form-control form-control-sm mr-2" style="width:140px;">
                                <option value="all">Durum: Tümü</option>
                                <option value="planned">Planlandı</option>
                                <option value="in_progress">Devam Ediyor</option>
                                <option value="completed">Tamamlandı</option>
                            </select>
                            <select id="filter-discipline" class="form-control form-control-sm" style="width:140px;">
                                <option value="all">Disiplin: Tümü</option>
                                <option value="construction">İnşaat</option>
                                <option value="mechanical">Mekanik</option>
                                <option value="electrical">Elektrik</option>
                            </select>
                        </div>
                    </div>

                    <div id="inspections-list">
                        <!-- JS will render list here -->
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kontrol Listesi Şablonları</h5>
                    <div id="templates-list" class="qhse-templates-grid mt-2">
                        <!-- Templates rendered by JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Özet</h5>
                    <div class="summary-cards">
                        <div class="mb-2">Gecikmiş Denetim Sayısı: <strong id="summary-overdue">0</strong></div>
                        <div class="mb-2">Bu Hafta Başarısız Olan Madde Sayısı: <strong id="summary-failed-this-week">0</strong></div>
                        <div class="mb-2">Genel Başarı Oranı: <strong id="summary-success-rate">0%</strong></div>
                    </div>
                    <div class="mt-3">
                        <canvas id="statusChart" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Eksik ve Kusurlu İşler</h5>
                    <div id="punchlist-preview">
                        <!-- recent punchlist items -->
                    </div>
                    <div class="text-right mt-2">
                        <a href="{{ route('index', ['any' => 'field/qhse/punchlist']) }}" class="btn btn-link btn-sm">Tümünü Gör</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal container for dynamic modals -->
    <div id="qhse-modals"></div>
</div>

@endsection

@section('scripts')
<link rel="stylesheet" href="/css/qhse.css" />
<style>
.qhse-modal .card-body { max-height: 70vh; overflow:auto; }
.template-card .start-inspection{ float:right; }
</style>
<!-- Chart.js CDN for charts (falls back if Chart is already loaded globally) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="/js/field-qhse-quality.js"></script>
@endsection
