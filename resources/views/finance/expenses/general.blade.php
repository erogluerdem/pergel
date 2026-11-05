@extends('layouts.master')
@section('title', 'Genel Giderler')
@section('topbar-title', 'Finans - Genel Giderler')

@section('css')
<link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
body {
    background: #f7f8fa;
}
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    padding-bottom: 16px;
}
.card {
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    border: none;
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}
.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #222;
}
.finance-main-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}
.finance-main-row > div {
    flex: 1 1 0;
    min-width: 320px;
    max-width: 33%;
    display: flex;
}
.card-body {
    flex: 1;
}
.card-footer {
    background: #fff;
    border-radius: 0 0 18px 18px;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.04);
    margin-top: 0;
    padding: 18px 32px;
}
.pagination {
    margin-bottom: 0;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 4px;
    margin-top: 0;
    margin-bottom: 0;
}
.card-footer .pagination {
    margin-bottom: 0;
}
.quick-actions .btn,
.quick-actions .btn-outline-light {
    border-radius: 10px;
    font-size: 1rem;
    padding: 10px 0;
    margin-bottom: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: background 0.2s, color 0.2s;
}
.quick-actions .btn:hover,
.quick-actions .btn-outline-light:hover {
    background: #e3f2fd;
    color: #007bff;
}
.category-card .category-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-right: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.category-card .progress-modern {
    height: 7px;
    border-radius: 8px;
    background: #e9ecef;
}
.category-card .progress-bar {
    border-radius: 8px;
}
.monthly-trend .alert-info {
    background: linear-gradient(90deg, #e3f0ff 0%, #f8f9fa 100%);
    color: #222;
    border: none;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.monthly-trend ul.list-group {
    background: transparent;
}
.monthly-trend .list-group-item {
    background: transparent;
    border: none;
    font-size: 1rem;
    padding: 8px 0;
}
.monthly-trend .fw-bold {
    font-weight: 700 !important;
}
.monthly-trend .text-primary {
    color: #007bff !important;
}
.monthly-trend .text-success {
    color: #28a745 !important;
}
.monthly-trend .text-danger {
    color: #dc3545 !important;
}
.monthly-trend .text-info {
    color: #17a2b8 !important;
}
.expense-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.expense-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 15px;
}
.expense-item {
    border-left: 4px solid transparent;
    transition: all 0.2s ease;
    border-radius: 8px;
    margin-bottom: 8px;
    padding: 15px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.expense-item:hover {
    background-color: #f8f9fa;
    border-left-color: #007bff;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.status-pending { border-left-color: #ffc107; }
.status-approved { border-left-color: #28a745; }
.status-rejected { border-left-color: #dc3545; }
.status-paid { border-left-color: #17a2b8; }
.chart-container {
    position: relative;
    min-height: 300px;
    height: 300px;
    padding: 20px;
}
.category-card {
    background: #f3f4f6;
    color: #222;
    border-radius: 12px;
    transition: all 0.3s ease;
}
.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}
.quick-actions {
    background: #fff;
    color: #222;
}
.quick-actions .btn,
.quick-actions input,
.quick-actions select,
.quick-actions label,
.quick-actions h5,
.quick-actions .card-title {
    color: #222 !important;
}
.category-card .card-title,
.category-card label,
.category-card span,
.category-card div,
.category-card {
    color: #222 !important;
}
.monthly-trend .card-title,
.monthly-trend label,
.monthly-trend span,
.monthly-trend div,
.monthly-trend {
    color: #222 !important;
}
    border-radius: 12px;
}
.monthly-trend {
    background: #f3f4f6;
    color: #222;
    border-radius: 12px;
}
.category-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-right: 15px;
    background: rgba(255,255,255,0.2);
}
.expense-type-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.amount-highlight {
    font-size: 1.1em;
    font-weight: 600;
    color: #2c3e50;
}
.vendor-info {
    color: #6c757d;
    font-size: 0.9em;
}
.progress-modern {
    height: 8px;
    border-radius: 20px;
    overflow: hidden;
    background-color: rgba(255,255,255,0.2);
}
.filter-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}
.search-box {
    border-radius: 25px;
    border: 2px solid #e9ecef;
    padding: 12px 20px;
    font-size: 14px;
}
.search-box:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Category manager button tweaks */
.list-group .btn {
    min-width: 64px;
}
.list-group .edit-category {
    background: transparent;
}
.list-group .delete-category {
    border-color: #dc3545;
}
.list-group .delete-category:hover {
    background-color: #dc3545;
    color: #fff;
}

/* === NEW MODERN DASHBOARD DESIGN === */

/* Header Stats Cards */
.stats-header {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border-left: 4px solid #007bff;
}

.stat-card.success { border-left-color: #28a745; }
.stat-card.warning { border-left-color: #ffc107; }
.stat-card.danger { border-left-color: #dc3545; }

.stat-card h6 {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}

.stat-card .value {
    font-size: 28px;
    font-weight: 700;
    color: #222;
    margin: 0;
}

.stat-card .change {
    font-size: 0.85rem;
    margin-top: 8px;
    color: #6c757d;
}

/* Inline Form */
.form-inline-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 24px;
}

.form-inline-section h5 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 16px;
    color: #222;
}

.form-inline-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

/* Expense Card List */
.expense-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.expense-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
    border-left: 4px solid #007bff;
}

.expense-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.10);
    transform: translateX(4px);
}

.expense-card-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.expense-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background: #f3f4f6;
}

.expense-info h6 {
    margin: 0;
    font-weight: 600;
    color: #222;
    font-size: 0.95rem;
}

.expense-info p {
    margin: 4px 0 0;
    color: #6c757d;
    font-size: 0.85rem;
}

.expense-amount {
    font-size: 1.1rem;
    font-weight: 700;
    color: #007bff;
    margin-right: 20px;
}

/* Filter Sticky */
.filter-sticky {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    margin-bottom: 20px;
    position: sticky;
    top: 80px;
    z-index: 50;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #dee2e6;
    margin-bottom: 16px;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Select2 Dropdown Styling */
.select2-container--default .select2-selection--single {
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    height: auto !important;
    min-height: 44px !important;
    line-height: 1.5 !important;
}

.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #222 !important;
    line-height: 1.5 !important;
    padding: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
    width: 32px !important;
    right: 8px !important;
    top: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-top: -2px !important;
}

.select2-dropdown {
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    margin-top: 4px !important;
}

.select2-container--default .select2-results__option {
    padding: 12px 16px !important;
    font-size: 14px !important;
    color: #222 !important;
    line-height: 1.5 !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #e7f3ff !important;
    color: #007bff !important;
}

.select2-container--default .select2-results__option[aria-selected="true"] {
    background-color: #f3f4f6 !important;
    color: #222 !important;
}

.select2-search__field {
    padding: 8px 12px !important;
    border-radius: 6px !important;
    border: 1px solid #dee2e6 !important;
    font-size: 14px !important;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25) !important;
}

/* Form Large Input Styling */
.form-select-lg {
    font-size: 16px !important;
    padding: 12px 16px !important;
}

.form-control-lg {
    font-size: 16px !important;
    padding: 12px 16px !important;
}

</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Accordion: Yeni Gider Ekle - EN ÜST -->
    <div class="accordion mb-2" id="newExpenseAccordion">
        <div class="accordion-item" style="border: 1px solid #e9ecef; border-radius: 14px;">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#newExpenseForm" style="background-color: white; color: #007bff; font-weight: 600; padding: 16px 20px;">
                    <i class="mdi mdi-plus me-2"></i>Yeni Gider Ekle
                </button>
            </h2>
            <div id="newExpenseForm" class="accordion-collapse collapse show" data-bs-parent="#newExpenseAccordion">
                <div class="accordion-body" style="padding: 32px;">
                    <form action="{{ route('finance.expenses.store') }}" method="POST" enctype="multipart/form-data" id="addExpenseForm" style="max-width: 1000px;">
                        @csrf

                        <!-- Ana Grid Form -->
                        <div class="row g-3">
                            <!-- Satır 1: Başlık & Kategori -->
                            <div class="col-md-7">
                                <label class="form-label fw-500">Gider Başlığı <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-lg" placeholder="Ör: İnşaat malzemeleri, Yazılım lisansı" required style="border-radius: 8px; border: 1px solid #dee2e6;">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-500">Kategori <span class="text-danger">*</span></label>
                                <select name="category" class="form-select form-select-lg select2" required style="border-radius: 8px; border: 1px solid #dee2e6;">
                                    <option value="">-- Kategori seçin --</option>
                                    @foreach(\App\Models\GeneralExpense::getCategories() as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Satır 2: Tutar & Tarih -->
                            <div class="col-md-3">
                                <label class="form-label fw-500">Tutar <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0" placeholder="0.00" required style="border-radius: 8px 0 0 8px; border: 1px solid #dee2e6;">
                                    <span class="input-group-text" style="border-radius: 0 8px 8px 0; border: 1px solid #dee2e6; background: #f8f9fa;">₺</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-500">Gider Tarihi <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date" class="form-control form-control-lg" required style="border-radius: 8px; border: 1px solid #dee2e6;" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500">KDV (%)</label>
                                <input type="number" name="vat_rate" class="form-control form-control-lg" step="0.01" min="0" placeholder="0" value="0" style="border-radius: 8px; border: 1px solid #dee2e6;">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500">Para Birimi</label>
                                <select name="currency" class="form-select form-select-lg" style="border-radius: 8px; border: 1px solid #dee2e6;">
                                    <option value="TRY">TRY</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                </select>
                            </div>

                            <!-- Satır 3: Proje & Tedarikçi & Departman -->
                            <div class="col-md-4">
                                <label class="form-label fw-500">Proje</label>
                                <select name="project_id" id="project_id" class="form-select form-select-lg select2" style="border-radius: 8px; border: 1px solid #dee2e6;">
                                    <option value="">-- Proje seçin (isteğe bağlı) --</option>
                                    @forelse($projects ?? [] as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @empty
                                    <option disabled value="">Proje yok</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-500">Tedarikçi</label>
                                <select name="vendor_id" class="form-select form-select-lg select2" style="border-radius: 8px; border: 1px solid #dee2e6;">
                                    <option value="">-- Tedarikçi seçin (isteğe bağlı) --</option>
                                    @forelse($vendors ?? [] as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @empty
                                    <option disabled value="">Tedarikçi yok</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-500">Departman</label>
                                <select name="department" class="form-select select2" style="border-radius: 8px; border: 1px solid #dee2e6;">
                                    <option value="">-- Departman seçin (isteğe bağlı) --</option>
                                    @php
                                        $departments = \App\Models\GeneralExpense::select('department')
                                            ->whereNotNull('department')
                                            ->distinct()
                                            ->pluck('department');
                                    @endphp
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Satır 4: Fatura Bilgileri -->
                            <div class="col-md-6">
                                <label class="form-label fw-500">Fatura / Fiş Numarası</label>
                                <input type="text" name="invoice_number" class="form-control" placeholder="INV-2025-001" style="border-radius: 8px; border: 1px solid #dee2e6;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-500">Fatura / Fiş Tarihi</label>
                                <input type="date" name="invoice_date" class="form-control" style="border-radius: 8px; border: 1px solid #dee2e6;">
                            </div>

                            <!-- Satır 5: Açıklama & Dosya -->
                            <div class="col-md-8">
                                <label class="form-label fw-500">Açıklama</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Ek notlar..." style="border-radius: 8px; border: 1px solid #dee2e6;"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-500">Belge <span class="text-muted">(opsiyonel)</span></label>
                                <div class="input-group">
                                    <input type="file" name="receipt" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="border-radius: 8px 0 0 8px; border: 1px solid #dee2e6;">
                                    <span class="input-group-text" style="border-radius: 0 8px 8px 0; border: 1px solid #dee2e6; background: #f8f9fa;">📎</span>
                                </div>
                                <small class="text-muted d-block mt-1">Max 5MB</small>
                            </div>

                            <!-- Form Buttonları -->
                            <div class="col-12">
                                <div class="d-flex gap-2 pt-2">
                                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1" style="border-radius: 8px; font-weight: 600;">
                                        <i class="mdi mdi-plus-circle me-2"></i>Gider Ekle
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg" style="border-radius: 8px; font-weight: 600; min-width: 120px;">
                                        <i class="mdi mdi-refresh me-2"></i>Sıfırla
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="filter-card">
                <form action="" method="GET" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Arama</label>
                            <input type="text" name="search" class="form-control search-box" placeholder="Başlık, tedarikçi, fatura no..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select select2">
                                <option value="">Tüm Kategoriler</option>
                                @foreach(\App\Models\GeneralExpense::getCategories() as $key => $name)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select select2">
                                <option value="">Tüm Durumlar</option>
                                @foreach(\App\Models\GeneralExpense::getStatuses() as $key => $name)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Departman</label>
                            <select name="department" class="form-select select2">
                                <option value="">Tüm Departmanlar</option>
                                @php
                                    $departments = \App\Models\GeneralExpense::select('department')
                                        ->whereNotNull('department')
                                        ->distinct()
                                        ->pluck('department');
                                @endphp
                                @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tarih Aralığı</label>
                            <input type="text" name="date_range" class="form-control" id="dateRange"
                                placeholder="Tarih seç..." value="{{ request('date_range') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100" id="filterButton">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- OLD LIST REMOVED - MOVED TO TAB SYSTEM -->
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-format-list-bulleted me-2"></i>Gider Listesi
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-filter-variant me-1"></i>Filtrele
                            </button>
                            <button class="btn btn-outline-success btn-sm">
                                <i class="mdi mdi-microsoft-excel me-1"></i>Excel
                            </button>
                            <button class="btn btn-primary btn-sm">
                                <i class="mdi mdi-plus me-1"></i>Yeni Gider
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @foreach($expenses as $expense)
                    <div class="expense-item status-{{ $expense->status }}">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                @php
                                    $gradients = [
                                        'office' => '#667eea, #764ba2',
                                        'utilities' => '#667eea, #764ba2',
                                        'travel' => '#4facfe, #00f2fe',
                                        'marketing' => '#f093fb, #f5576c',
                                        'software' => '#4facfe, #00f2fe',
                                        'equipment' => '#a8edea, #fed6e3',
                                        'supplies' => '#fa709a, #fee140',
                                        'legal' => '#fa709a, #fee140',
                                        'insurance' => '#a8edea, #fed6e3',
                                        'maintenance' => '#a8edea, #fed6e3',
                                        'construction' => '#a8edea, #fed6e3',
                                        'materials' => '#667eea, #764ba2',
                                        'labor' => '#fa709a, #fee140',
                                        'fuel' => '#f093fb, #f5576c',
                                        'logistics' => '#4facfe, #00f2fe',
                                        'catering' => '#f093fb, #f5576c',
                                        'fees' => '#fa709a, #fee140',
                                        'overhead' => '#a8edea, #fed6e3',
                                        'other' => '#667eea, #764ba2',
                                    ];
                                @endphp
                                <div class="category-icon" style="background: linear-gradient(45deg, {{ $gradients[$expense->category] ?? '#fa709a, #fee140' }});">
                                    <i class="mdi {{ App\Models\GeneralExpense::getCategoryIcon($expense->category) }} text-white"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6 class="mb-1">{{ $expense->title }}</h6>
                                <p class="vendor-info mb-0">
                                    <i class="mdi mdi-domain me-1"></i>{{ $expense->vendor_name ?? 'Belirtilmemiş' }}
                                </p>
                                @if($expense->invoice_number)
                                <small class="text-muted">
                                    <i class="mdi mdi-file-document-outline me-1"></i>{{ $expense->invoice_number }}
                                </small>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <span class="expense-type-badge" style="background: {{ $expense->category == 'utilities' ? '#e3f2fd' : ($expense->category == 'software' ? '#f3e5f5' : '#fff3e0') }}; color: {{ $expense->category == 'utilities' ? '#1976d2' : ($expense->category == 'software' ? '#7b1fa2' : '#f57c00') }};">
                                    {{ \App\Models\GeneralExpense::getCategories()[$expense->category] ?? $expense->category }}
                                </span>
                                @if($expense->department)
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="mdi mdi-account-group me-1"></i>{{ $expense->department }}
                                    </small>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="amount-highlight">₺{{ number_format($expense->total_amount, 2, ',', '.') }}</div>
                                @if($expense->vat_amount > 0)
                                <small class="text-muted d-block">
                                    KDV: ₺{{ number_format($expense->vat_amount, 2, ',', '.') }}
                                </small>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="mb-1">{{ $expense->expense_date->format('d.m.Y') }}</div>
                                @if($expense->is_recurring)
                                <span class="badge bg-info">
                                    <i class="mdi mdi-refresh me-1"></i>Tekrarlı
                                </span>
                                @endif
                            </div>
                            <div class="col-md-1 text-center">
                                {!! $expense->status_badge !!}
                            </div>
                            <div class="col-md-1 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('finance.expenses.view', $expense->id) }}"><i class="mdi mdi-eye me-2"></i>Görüntüle</a></li>
                                        <li><a class="dropdown-item" href="{{ route('finance.expenses.edit', $expense->id) }}"><i class="mdi mdi-pencil me-2"></i>Düzenle</a></li>
                                        @if($expense->status == 'pending')
                                        <li><a class="dropdown-item text-success expense-action" href="#"
                                               data-action="approve" data-id="{{ $expense->id }}">
                                               <i class="mdi mdi-check me-2"></i>Onayla
                                            </a>
                                        </li>
                                        <li><a class="dropdown-item text-danger expense-action" href="#"
                                               data-action="reject" data-id="{{ $expense->id }}">
                                               <i class="mdi mdi-close me-2"></i>Reddet
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger expense-action" href="#"
                                               data-action="delete" data-id="{{ $expense->id }}">
                                               <i class="mdi mdi-delete me-2"></i>Sil
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if($expense->description)
                        <div class="row mt-2">
                            <div class="col-md-11 offset-md-1">
                                <small class="text-muted">{{ $expense->description }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #f9fafc 100%);
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .form-section-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #007bff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e3f0ff;
        }

        .btn-submit-expense {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 12px 32px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-submit-expense:hover {
            background: linear-gradient(90deg, #0056b3 0%, #004085 100%);
            box-shadow: 0 6px 16px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }

        .btn-reset-form {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 12px 32px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-reset-form:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
    </style>

    <h5 class="mb-3 mt-2"><i class="mdi mdi-file-document-outline me-2"></i>Giderler Listesi <span class="badge bg-secondary ms-2">{{ $expenses->total() }}</span></h5>

    <!-- Modern Card-Based Expense List -->
    <div class="expense-list">
        @forelse($expenses as $expense)
            <div class="expense-card" style="border-left-color: {{ ['office' => '#667eea', 'utilities' => '#667eea', 'travel' => '#4facfe', 'marketing' => '#f093fb', 'software' => '#4facfe', 'equipment' => '#a8edea', 'supplies' => '#fa709a', 'legal' => '#fa709a', 'insurance' => '#a8edea', 'maintenance' => '#a8edea', 'construction' => '#a8edea', 'materials' => '#667eea', 'labor' => '#fa709a', 'fuel' => '#f093fb', 'logistics' => '#4facfe', 'catering' => '#f093fb', 'fees' => '#fa709a', 'overhead' => '#a8edea', 'other' => '#667eea'][$expense->category] ?? '#007bff' }}">
                
                <!-- Left: Icon & Info -->
                <div class="expense-card-left">
                    <div class="expense-icon" style="background: {{ ['office' => '#667eea', 'utilities' => '#667eea', 'travel' => '#4facfe', 'marketing' => '#f093fb', 'software' => '#4facfe', 'equipment' => '#a8edea', 'supplies' => '#fa709a', 'legal' => '#fa709a', 'insurance' => '#a8edea', 'maintenance' => '#a8edea', 'construction' => '#a8edea', 'materials' => '#667eea', 'labor' => '#fa709a', 'fuel' => '#f093fb', 'logistics' => '#4facfe', 'catering' => '#f093fb', 'fees' => '#fa709a', 'overhead' => '#a8edea', 'other' => '#667eea'][$expense->category] ?? '#007bff' }}22;">
                        <i class="mdi {{ App\Models\GeneralExpense::getCategoryIcon($expense->category) }}" style="color: {{ ['office' => '#667eea', 'utilities' => '#667eea', 'travel' => '#4facfe', 'marketing' => '#f093fb', 'software' => '#4facfe', 'equipment' => '#a8edea', 'supplies' => '#fa709a', 'legal' => '#fa709a', 'insurance' => '#a8edea', 'maintenance' => '#a8edea', 'construction' => '#a8edea', 'materials' => '#667eea', 'labor' => '#fa709a', 'fuel' => '#f093fb', 'logistics' => '#4facfe', 'catering' => '#f093fb', 'fees' => '#fa709a', 'overhead' => '#a8edea', 'other' => '#667eea'][$expense->category] ?? '#007bff' }};"></i>
                    </div>
                    <div class="expense-info">
                        <h6>{{ $expense->title }}</h6>
                        <p>
                            <span class="badge bg-light text-dark me-2" style="font-size: 0.75rem;">{{ \App\Models\GeneralExpense::getCategories()[$expense->category] ?? $expense->category }}</span>
                            @if($expense->vendor_name)
                                <i class="mdi mdi-domain me-1"></i><small>{{ $expense->vendor_name }}</small>
                            @endif
                            <i class="mdi mdi-calendar me-1 ms-2"></i><small>{{ $expense->expense_date->format('d.m.Y') }}</small>
                        </p>
                    </div>
                </div>

                <!-- Right: Amount & Actions -->
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div class="expense-amount">₺{{ number_format($expense->total_amount, 2, ',', '.') }}</div>
                    
                    <div>
                        {!! $expense->status_badge !!}
                    </div>

                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('finance.expenses.view', $expense->id) }}" class="btn btn-outline-secondary" title="Görüntüle" data-bs-toggle="tooltip">
                            <i class="mdi mdi-eye"></i>
                        </a>
                        <a href="{{ route('finance.expenses.edit', $expense->id) }}" class="btn btn-outline-secondary" title="Düzenle" data-bs-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        @if($expense->status == 'pending')
                            <button type="button" class="btn btn-outline-success expense-action" data-action="approve" data-id="{{ $expense->id }}" title="Onayla" data-bs-toggle="tooltip">
                                <i class="mdi mdi-check"></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger expense-action" data-action="delete" data-id="{{ $expense->id }}" title="Sil" data-bs-toggle="tooltip">
                            <i class="mdi mdi-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="mdi mdi-inbox-multiple-outline" style="font-size: 48px; color: #dee2e6; margin-bottom: 16px; display: block;"></i>
                    <p style="color: #6c757d; margin-bottom: 0;"><strong>Henüz gider kaydı yok</strong></p>
                    <p style="color: #adb5bd; font-size: 0.9rem;">İlk giderinizi eklemek için yukarıdaki formu kullanın.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($expenses->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $expenses->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif

    <!-- Departman İstatistikleri -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-group me-2"></i>Departman Bazlı Harcamalar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($departmentStats as $dept)
                        <div class="col-md-4 mb-3">
                            <div class="card" style="background: linear-gradient(135deg, {{ ['#667eea', '#f093fb', '#4facfe', '#fa709a', '#a8edea'][random_int(0,4)] }} 0%, {{ ['#764ba2', '#f5576c', '#00f2fe', '#fee140', '#fed6e3'][random_int(0,4)] }} 100%); color: white;">
                                <div class="card-body text-center">
                                    <h5 class="text-white">{{ $dept->department }}</h5>
                                    <h4 class="text-white mb-0">₺{{ number_format($dept->total, 0, ',', '.') }}</h4>
                                    <small class="text-white-50">{{ $dept->count }} işlem</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toplu Onaylama Modal -->
<div class="modal fade" id="bulkApprovalModal" tabindex="-1" aria-labelledby="bulkApprovalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkApprovalModalLabel">Toplu Onay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.expenses.bulk_approve') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tarih Aralığı</label>
                        <input type="text" name="date_range" class="form-control" id="approvalDateRange" placeholder="Tarih aralığı seçin">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select select2">
                            <option value="">Tüm Kategoriler</option>
                            @foreach(\App\Models\GeneralExpense::getCategories() as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Maksimum Tutar</label>
                        <input type="number" name="max_amount" class="form-control" step="0.01" min="0" placeholder="Maksimum onaylanacak tutar">
                        <small class="form-text text-muted">Belirtilen tutardan düşük olan onay bekleyen tüm giderler onaylanacaktır.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Toplu Onay</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- SweetAlert2 for modern toasts and confirmations -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Load Select2 from CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Small helper to show modern toast using SweetAlert2
function showToast(type, message, opts = {}) {
    const icons = { success: 'success', error: 'error', info: 'info', warning: 'warning' };
    Swal.fire(Object.assign({
        toast: true,
        position: opts.position || 'top-end',
        showConfirmButton: false,
        timer: opts.timer || 3000,
        icon: icons[type] || 'info',
        title: message
    }, opts.swal || {}));
}

$(window).on('load', function() {
    // Select2 initialization
    $('.select2').each(function() {
        var $s = $(this);
        var opts = { placeholder: "Seçiniz...", allowClear: true, width: '100%' };
        try {
            $s.select2(opts);
        } catch (e) {
            console.warn('select2 init failed for element', this, e);
        }
    });

    // Date range picker
    $("#dateRange, #approvalDateRange").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt'],
                longhand: ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi']
            },
            months: {
                shorthand: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'],
                longhand: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık']
            }
        }
    });

    // --- Aylık Trend Chart.js ---
    (function initMonthlyChart(){
        const canvas = document.getElementById('monthlyTrendChart');
        if (!canvas) return;

        // Ensure Chart.js is loaded; if not, dynamically load CDN and then init
        function createChart() {
            try {
                const ctx = canvas.getContext('2d');
                const monthlyData = {!! json_encode($monthlyTrend) !!};
                let labels = monthlyData.map(item => item.month);
                let data = monthlyData.map(item => Number(item.total));
                if (!labels.length || !data.length || data.every(v => v === 0)) {
                    labels = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz'];
                    data = [100, 200, 150, 300, 250, 400];
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Aylık Toplam',
                            data: data,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#007bff',
                            pointBorderColor: '#007bff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
            } catch (e) {
                console.error('Chart creation failed', e);
            }
        }

        if (typeof Chart !== 'undefined') {
            createChart();
            return;
        }

        // Load Chart.js from CDN (UMD build) and initialize after load
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
        script.async = true;
        script.onload = function() { setTimeout(createChart, 50); };
        script.onerror = function() { console.error('Chart.js yüklenemedi'); };
        document.head.appendChild(script);
    })();

    // ===== Form Handler - Yeni Gider Ekle (Accordion) =====
    $('#addExpenseForm').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        }).done(function(response) {
            if (response && response.success) {
                showToast('success', response.message || 'Gider başarıyla eklendi!');
                $form[0].reset();
                // Accordion'u kapatsın
                $('#newExpenseForm').collapse('hide');
                // Sayfanın başına kaydır
                window.scrollTo({ top: 0, behavior: 'smooth' });
                // Sayfa yenile
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('error', response && response.message ? response.message : 'Sunucudan beklenmeyen yanıt alındı');
            }
        }).fail(function(xhr) {
            let errorMsg = 'İşlem başarısız oldu';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showToast('error', errorMsg);
            console.error('Form submit failed:', xhr);
        });
    });

    // ===== Form Field Initialization =====
    // Set default expense_date to today
    document.getElementById('addExpenseForm') && (() => {
        const today = new Date().toISOString().split('T')[0];
        const expenseDateInput = document.querySelector('#addExpenseForm input[name="expense_date"]');
        if (expenseDateInput && !expenseDateInput.value) {
            expenseDateInput.value = today;
        }
    })();

    // Handle project_id change to show/load villas
    $('#project_id').on('change', function() {
        const projectId = $(this).val();
        const $villaSelect = $('#villa_id');
        const $villaRow = $('#villa_row');

        if (!projectId) {
            $villaRow.hide();
            $villaSelect.html('<option value="">Genel Harcama</option>');
            return;
        }

        // Show villa row and load villas via AJAX
        $villaRow.show();
        $villaSelect.html('<option value="">Yükleniyor...</option>');

        $.ajax({
            url: `/api/projects/${projectId}/villas`,
            type: 'GET',
            dataType: 'json',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function(response) {
            let html = '<option value="">Genel Harcama</option>';
            if (response && response.villas && response.villas.length) {
                response.villas.forEach(villa => {
                    html += `<option value="${villa.id}">${villa.name}</option>`;
                });
            }
            $villaSelect.html(html);
            if ($villaSelect.data('select2')) {
                $villaSelect.select2('destroy').select2({
                    placeholder: "Villa Seçiniz",
                    allowClear: true,
                    width: '100%'
                });
            }
        }).fail(function(xhr) {
            console.error('Villa yükleme başarısız:', xhr);
            $villaSelect.html('<option value="">Villa yüklenemedi</option>');
            showToast('error', 'Villalar yüklenirken hata oluştu');
        });
    });

    // Initialize Select2 for form fields when DOM ready and periodically
    function initializeFormSelect2() {
        try {
            $('#addExpenseForm .select2:not(.select2-hidden-accessible)').each(function() {
                try {
                    $(this).select2({
                        placeholder: "Seçiniz...",
                        allowClear: true,
                        width: '100%',
                        dropdownAutoWidth: true
                    });
                } catch(e) {
                    console.warn('Select2 init failed:', e);
                }
            });
        } catch(e) {
            console.warn('Select2 init error:', e);
        }
    }

    // Initialize on document ready
    $(document).ready(function() {
        setTimeout(initializeFormSelect2, 100);
    });

    // Re-initialize Select2 when accordion opens
    $('#newExpenseForm').on('show.bs.collapse', function() {
        setTimeout(initializeFormSelect2, 150);
    });

    // Expense action handlers (delegate to document to ensure dropdown-generated links work)
    $(document).on('click', '.expense-action', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        const id = $(this).data('id');
        console && console.log && console.log('expense-action clicked', action, id);

        // Global AJAX headers (ensure CSRF header is present and server can return JSON)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        });

        const doRequest = function() {
            return $.ajax({
                url: `/finance/expenses/${action}/${id}`,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {},
            });
        };

        // If action is delete or sensitive, show modern confirmation
        if (action === 'delete') {
            return Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu gideri silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Evet, sil',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    doRequest().done(function(response) {
                        if (response && response.success) {
                            showToast('success', response.message || 'İşlem başarılı');
                            setTimeout(() => window.location.reload(), 800);
                        } else {
                            showToast('error', response && response.message ? response.message : 'Sunucudan beklenmeyen yanıt alındı');
                        }
                    }).fail(function(xhr) {
                        handleAjaxError(xhr);
                    });
                }
            });
        }

        doRequest().done(function(response) {
            if (response && response.success) {
                showToast('success', response.message || 'İşlem başarılı');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast('error', response && response.message ? response.message : 'Sunucudan beklenmeyen yanıt alındı');
            }
        }).fail(function(xhr) {
            handleAjaxError(xhr);
        });
    });

    function handleAjaxError(xhr) {
        console && console.error && console.error('expense-action error', xhr.status, xhr.responseText);
        if (xhr.status === 419) {
            showToast('error', 'Oturum süresi doldu. Lütfen sayfayı yenileyip tekrar giriş yapın.');
            return;
        }
        try {
            var json = JSON.parse(xhr.responseText);
            if (json && json.message) {
                showToast('error', json.message);
                return;
            }
        } catch (e) {
            // not JSON
        }
        var text = xhr.responseText ? xhr.responseText.substring(0, 400) : 'Sunucuya ulaşılamıyor';
        showToast('error', 'İşlem sırasında bir hata oluştu: ' + text);
    }
});
</script>

<!-- Modal işlemleri için kesin çalışan JS kodu -->
<script>
$(function() {
    // Kategori ekle
    $(document).on('submit', '#addCategoryForm', function(e) {
        e.preventDefault();
        var name = $(this).find('input[name="category_name"]').val();
        if (!name.trim()) return;
        $.post('/finance/expenses/category/add', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: name
        }, function(resp) {
            if (resp.success) {
                showToast('success', 'Kategori eklendi');
                setTimeout(function(){ location.reload(); }, 800);
            } else {
                showToast('error', resp.message || 'Kategori eklenemedi');
            }
        }).fail(function(xhr) {
            console.error(xhr);
            handleCategoryAjaxError(xhr);
        });
    });
    // Kategori sil
    $(document).on('click', '.delete-category', function() {
        var key = $(this).data('key');
        Swal.fire({
            title: 'Emin misiniz?',
            text: 'Bu kategoriyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.post('/finance/expenses/category/delete', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                key: key
            }, function(resp) {
                if (resp.success) {
                    showToast('success', 'Kategori silindi');
                    setTimeout(function(){ location.reload(); }, 800);
                } else {
                    showToast('error', resp.message || 'Kategori silinemedi');
                }
            }).fail(function(xhr) {
                console.error(xhr);
                handleCategoryAjaxError(xhr);
            });
        });
    });
    // Kategori düzenle
    $(document).on('click', '.edit-category', function() {
        var li = $(this).closest('li');
        var key = $(this).data('key');
        var name = $(this).data('name');
    var input = $('<input type="text" class="form-control form-control-sm" value="'+name+'">');
    var saveBtn = $('<button type="button" class="btn btn-sm btn-success ms-2">Kaydet</button>');
    var cancelBtn = $('<button type="button" class="btn btn-sm btn-secondary ms-2">İptal</button>');
        li.find('span:first').html(input);
        li.find('span:last').html(saveBtn.add(cancelBtn));
        saveBtn.on('click', function() {
            var newName = input.val();
            $.post('/finance/expenses/category/edit', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                key: key,
                name: newName
            }, function(resp) {
                if (resp.success) {
                    showToast('success', 'Kategori güncellendi');
                    setTimeout(function(){ location.reload(); }, 800);
                } else {
                    showToast('error', resp.message || 'Kategori güncellenemedi');
                }
            }).fail(function(xhr) {
                console.error(xhr);
                handleCategoryAjaxError(xhr);
            });
        });
        cancelBtn.on('click', function() {
            location.reload();
        });
    });

    function handleCategoryAjaxError(xhr) {
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : xhr.statusText;
        showToast('error', 'Sunucu hatası: ' + msg);
    }
});
</script>

<!-- Modal focus management to avoid aria-hidden warnings -->
<script>
$(function() {
    // When the modal is fully shown, move focus to first focusable element inside it
    $(document).on('shown.bs.modal', '#categoryManagerModal', function () {
        var $modal = $(this);
        var $focusable = $modal.find('input,select,textarea,button,a').filter(':visible').first();
        if ($focusable && $focusable.length) {
            try { $focusable.trigger('focus'); } catch(e) {}
        } else {
            // fallback: focus the modal container
            try { $modal.trigger('focus'); } catch(e) {}
        }
    });

    // When modal hides, return focus to the element that opened it (if present)
    $(document).on('hide.bs.modal', '#categoryManagerModal', function () {
        var $trigger = $(document).find('[data-bs-target="#categoryManagerModal"]').first();
        if ($trigger && $trigger.length) {
            try { $trigger.trigger('focus'); } catch(e) {}
        }
    });
});
</script>
@endsection

@section('styles_modals_buttons')
<style>
    /* Make the cancel button in expense modals black with white text */
    #addExpenseModal .btn.btn-secondary,
    #categoryManagerModal .btn.btn-secondary,
    .modal .btn.btn-cancel-secondary {
        background-color: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
    }

    /* Harmonize primary/confirm button colors across expense modals */
    #addExpenseModal .btn-primary,
    #categoryManagerModal .btn-primary,
    .modal .btn.btn-primary {
        background-color: #ffb400 !important; /* project orange */
        border-color: #ffb400 !important;
        color: #fff !important;
    }

    /* Optional: subtle hover states */
    .btn.btn-secondary:hover {
        background-color: #111 !important;
        border-color: #111 !important;
    }
</style>

$(document).ready(function() {
    // ===== SELECT2 INITIALIZATION =====
    $('.select2').select2({
        width: '100%',
        language: 'tr',
        placeholder: 'Seçiniz...',
        allowClear: false,
        dropdownParent: $(document.body),
        matcher: function(params, data) {
            // Türkçe karakter destegi
            var term = $.trim(params.term);
            if (term === '') return data;
            var regex = new RegExp(term, 'i');
            if (regex.test(data.text)) {
                return data;
            }
            return null;
        }
    });

    // Project ID change - load villas
    $('#project_id').change(function() {
        var projectId = $(this).val();
        if (projectId) {
            $.get('/api/projects/' + projectId + '/villas', function(data) {
                $('#villa_id').empty();
                $('#villa_id').append('<option value="">Genel Harcama</option>');
                $.each(data, function(key, value) {
                    $('#villa_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $('#villa_row').show();
            });
        } else {
            $('#villa_row').hide();
        }
    });
});

</script>
@endsection
