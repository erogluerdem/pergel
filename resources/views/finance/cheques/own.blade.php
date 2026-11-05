@extends('layouts.master')
@section('title', 'Kendi Çeklerimiz')
@section('topbar-title', 'Finans - Çek Yönetimi')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin: -1rem -0.5rem 2rem -0.5rem;
        border-radius: 0 0 20px 20px;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        z-index: 1;
    }

    .page-header h1 {
        position: relative;
        z-index: 2;
        margin: 0;
        font-weight: 600;
    }

    .stats-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        background: white;
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .stats-card .card-body {
        padding: 2rem;
        position: relative;
        z-index: 2;
    }

    .stats-card.total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .stats-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    .stats-card.cashed {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    .stats-card.returned {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stats-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 3rem;
        opacity: 0.3;
    }

    .stats-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .cheque-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        background: white;
        overflow: hidden;
    }

    .cheque-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    }

    .cheque-header {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .cheque-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #495057;
        margin: 0;
    }

    .bank-info {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .cheque-body {
        padding: 1.5rem;
    }

    .amount-display {
        font-size: 2rem;
        font-weight: 700;
        color: #28a745;
        text-align: center;
        margin-bottom: 1rem;
    }

    .status-badge {
        border-radius: 25px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    .status-cashed { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    .status-returned { background: #f8d7da; color: #721c24; border: 1px solid #f1b0b7; }
    .status-cancelled { background: #e2e3e5; color: #383d41; border: 1px solid #c6c8ca; }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .detail-value {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #dee2e6;
    }

    .btn-action {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border: none;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn-view { background: #007bff; color: white; }
    .btn-edit { background: #28a745; color: white; }
    .btn-cancel { background: #dc3545; color: white; }

    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .filter-section h5 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Form spacing düzeltmeleri */
    .filter-section .row {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    .filter-section [class*="col-"] {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .d-flex.align-items-end .w-100 {
        display: flex;
        gap: 0.5rem;
    }

    /* Yeni Çek Ekle Butonu */
    .btn-add-cheque {
        background: rgba(255,255,255,0.95) !important;
        color: #4f46e5 !important;
        border: 2px solid rgba(255,255,255,0.8) !important;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .btn-add-cheque:hover {
        background: white !important;
        color: #3730a3 !important;
        border-color: white !important;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    .btn-add-cheque:focus {
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25) !important;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #e0e6ed;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Content container */
    .content-wrapper {
        padding: 0 0.5rem;
        max-width: 100%;
        margin: 0 auto;
    }

    /* Modern Tablo Stilleri */
    .cheques-table {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .cheques-table .table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .cheques-table .table thead {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }

    .cheques-table .table thead th {
        border: none;
        padding: 1.5rem 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        position: relative;
    }

    .cheques-table .table thead th:first-child {
        border-radius: 0;
    }

    .cheques-table .table thead th:last-child {
        border-radius: 0;
    }

    .cheques-table .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .cheques-table .table tbody tr:hover {
        background: rgba(79, 70, 229, 0.03);
        transform: scale(1.005);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .cheques-table .table tbody td {
        border: none;
        padding: 1.25rem 1rem;
        vertical-align: middle;
        font-weight: 500;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .cheque-number-cell {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.1rem;
    }

    .bank-cell {
        color: #64748b;
        font-size: 0.95rem;
    }

    .bank-cell .bank-name {
        font-weight: 600;
        color: #334155;
        display: block;
    }

    .bank-cell .branch-name {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 0.25rem;
    }

    .amount-cell {
        font-weight: 800;
        font-size: 1.2rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .recipient-cell {
        color: #475569;
        font-weight: 600;
    }

    .date-cell {
        color: #64748b;
        font-size: 0.9rem;
    }

    .status-cell .status-badge {
        border-radius: 25px;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 2px solid;
    }

    .actions-cell {
        text-align: center;
    }

    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .btn-view-table { background: #3b82f6; color: white; }
    .btn-edit-table { background: #10b981; color: white; }
    .btn-cancel-table { background: #ef4444; color: white; }

    @media (max-width: 768px) {
        .page-header {
            margin: -0.5rem -0.25rem 1.5rem -0.25rem;
        }

        .content-wrapper {
            padding: 0 0.25rem;
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-value {
            font-size: 1.5rem;
        }

        .cheque-card {
            margin-bottom: 1rem;
        }

        .amount-display {
            font-size: 1.5rem;
        }

        .action-buttons {
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 80px;
        }

        /* Filtre Bölümü Responsive */
        .filter-section {
            padding: 1rem;
            margin-left: 0;
            margin-right: 0;
        }

        .filter-section h5 {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive Tablo */
        .cheques-table {
            border-radius: 15px;
        }

        .cheques-table .table thead th {
            padding: 1rem 0.75rem;
            font-size: 0.8rem;
        }

        .cheques-table .table tbody td {
            padding: 1rem 0.75rem;
            font-size: 0.85rem;
        }

        .action-btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
            margin: 0 0.1rem;
        }
    }

    @media (max-width: 576px) {
        .cheques-table {
            margin: 0 -0.25rem;
        }

        .cheques-table .table {
            font-size: 0.8rem;
        }

        .cheques-table .table thead th,
        .cheques-table .table tbody td {
            padding: 0.75rem 0.5rem;
        }

        .action-btn {
            padding: 0.3rem 0.4rem;
            font-size: 0.7rem;
        }

        /* Filtre form mobil */
        .filter-section {
            padding: 0.75rem;
        }

        .form-control {
            font-size: 0.85rem;
        }

        .form-label {
            font-size: 0.8rem;
        }

        .btn-filter, .btn-outline-secondary {
            font-size: 0.85rem;
            padding: 0.75rem 1.5rem;
        }
    }
</style>
@endpush

@push('styles')
<!-- Select2 styles -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
/* Small Select2 tweaks */
.select2-container .select2-selection--single { height: 44px; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 42px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="fas fa-money-check-alt me-3"></i>Kendi Çeklerimiz</h1>
                <p class="mb-0 opacity-75">Şirketimiz tarafından düzenlenen çeklerin takibi ve yönetimi</p>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-add-cheque btn-lg" onclick="addNewCheque()">
                    <i class="fas fa-plus me-2"></i>Yeni Çek Ekle
                </button>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper">
<!-- İstatistik Kartları -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card total">
            <div class="card-body">
                <div class="stats-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stats-value">₺{{ number_format($totalAmount, 0, ',', '.') }}</div>
                <div class="stats-label">Toplam Tutar</div>
                <small class="opacity-75">{{ $cheques->count() }} adet çek</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card pending">
            <div class="card-body">
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-value">₺{{ number_format($pendingAmount, 0, ',', '.') }}</div>
                <div class="stats-label">Bekleyen Çekler</div>
                <small class="opacity-75">{{ $statusCounts['pending'] }} adet</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card cashed">
            <div class="card-body">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-value">₺{{ number_format($cashedAmount, 0, ',', '.') }}</div>
                <div class="stats-label">Tahsil Edilen</div>
                <small class="opacity-75">{{ $statusCounts['cashed'] }} adet</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stats-card returned">
            <div class="card-body">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-value">₺{{ number_format($returnedAmount, 0, ',', '.') }}</div>
                <div class="stats-label">İade Edilen</div>
                <small class="opacity-75">{{ $statusCounts['returned'] }} adet</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtre Bölümü -->
<div class="filter-section">
    <h5><i class="fas fa-filter me-2"></i>Filtrele ve Ara</h5>
    <form id="chequeFilter">
        <!-- İlk Satır -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Çek Numarası</label>
                <input type="text" class="form-control" placeholder="CK2024001" id="chequeNumber">
            </div>
            <div class="col-md-4">
                <label class="form-label">Banka</label>
                <select class="form-control" id="bankName">
                    <option value="">Tüm Bankalar</option>
                    <option value="Türkiye İş Bankası">Türkiye İş Bankası</option>
                    <option value="Ziraat Bankası">Ziraat Bankası</option>
                    <option value="Garanti BBVA">Garanti BBVA</option>
                    <option value="Akbank">Akbank</option>
                    <option value="Yapı Kredi Bankası">Yapı Kredi Bankası</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Durum</label>
                <select class="form-control" id="status">
                    <option value="">Tüm Durumlar</option>
                    <option value="pending">Bekleyen</option>
                    <option value="cashed">Tahsil Edilen</option>
                    <option value="returned">İade Edilen</option>
                    <option value="cancelled">İptal Edilen</option>
                </select>
            </div>
        </div>

        <!-- İkinci Satır -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Başlangıç Tarihi</label>
                <input type="date" class="form-control" id="startDate">
            </div>
            <div class="col-md-4">
                <label class="form-label">Bitiş Tarihi</label>
                <input type="date" class="form-control" id="endDate">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="w-100">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="clearFilters()">
                        <i class="fas fa-times me-1"></i>Temizle
                    </button>
                    <button type="button" class="btn btn-filter" onclick="applyFilters()">
                        <i class="fas fa-search me-1"></i>Filtrele
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Çek Listesi - Tablo Format -->
<div class="cheques-table" id="chequesList">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th><i class="fas fa-hashtag me-2"></i>Çek No</th>
                <th><i class="fas fa-university me-2"></i>Banka</th>
                <th><i class="fas fa-money-bill-wave me-2"></i>Tutar</th>
                <th><i class="fas fa-user me-2"></i>Alıcı</th>
                <th><i class="fas fa-calendar me-2"></i>Vade</th>
                <th><i class="fas fa-info-circle me-2"></i>Durum</th>
                <th><i class="fas fa-cogs me-2"></i>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cheques as $cheque)
            <tr class="cheque-item"
                data-cheque-id="{{ $cheque['id'] }}"
                data-cheque-number="{{ $cheque['cheque_number'] }}"
                data-bank="{{ $cheque['bank_name'] }}"
                data-status="{{ $cheque['status'] }}"
                data-issue-date="{{ $cheque['issue_date'] }}"
                data-due-date="{{ $cheque['due_date'] }}">

                <td class="cheque-number-cell">
                    <strong>{{ $cheque['cheque_number'] }}</strong>
                    <small class="d-block text-muted">{{ $cheque['account_number'] }}</small>
                </td>

                <td class="bank-cell">
                    <div class="bank-name">{{ $cheque['bank_name'] }}</div>
                    <div class="branch-name">{{ $cheque['branch'] }}</div>
                </td>

                <td class="amount-cell">
                    ₺{{ number_format(($cheque['amount'] ?? 0), 0, ',', '.') }}
                </td>

                <td class="recipient-cell">
                    {{ $cheque['recipient'] ?? ($cheque['customer_name'] ?? '') }}
                </td>

                <td class="date-cell">
                    <div>{!! isset($cheque['due_date']) && $cheque['due_date'] ? \Carbon\Carbon::parse($cheque['due_date'])->format('d.m.Y') : '' !!}</div>
                    <small class="text-muted">{!! isset($cheque['issue_date']) && $cheque['issue_date'] ? \Carbon\Carbon::parse($cheque['issue_date'])->format('d.m.Y') . ' düzenlendi' : '' !!}</small>
                </td>

                <td class="status-cell">
                    @php $st = $cheque['status'] ?? 'pending'; @endphp
                    <span class="status-badge status-{{ $st }}">
                        @switch($st)
                            @case('pending') Bekleyen @break
                            @case('cashed') Tahsil Edildi @break
                            @case('returned') İade Edildi @break
                            @case('cancelled') İptal Edildi @break
                            @case('assigned') Atandı @break
                            @default {{ ucfirst($st) }}
                        @endswitch
                    </span>
                </td>

                <td class="actions-cell">
                    <button class="action-btn btn-view-table" onclick="viewCheque({{ $cheque['id'] }})" title="Görüntüle">
                        <i class="fas fa-eye"></i>
                    </button>
                    @if($cheque['status'] == 'pending')
                        <button class="action-btn btn-edit-table" onclick="editCheque({{ $cheque['id'] }})" title="Düzenle">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-cancel-table" onclick="cancelCheque({{ $cheque['id'] }})" title="İptal Et">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="action-btn btn-edit-table" onclick="openAssignModal({{ $cheque['id'] }})" title="Tedarikçiye Ver">
                            <i class="fas fa-hand-holding-dollar"></i>
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Boş Durum -->
<div id="emptyState" class="cheques-table" style="display: none;">
    <div class="empty-state">
        <i class="fas fa-search"></i>
        <h4>Sonuç Bulunamadı</h4>
        <p>Arama kriterlerinize uygun çek bulunamadı. Filtreleri değiştirip tekrar deneyin.</p>
    </div>
</div>

</div> <!-- content-wrapper kapanış -->
@endsection

@push('scripts')
<script>
// Load Select2 via CDN and init remote search for customers
(function(){
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
    s.onload = function(){
        // initialize select2 on the customer select
        $('#newChequeCustomer').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Müşteri Ara veya Seç --',
            dropdownParent: $('#newChequeModal'),
            ajax: {
                url: '/finance/accounts/vouchers/search-parties',
                dataType: 'json',
                delay: 250,
                data: function(params){ return { q: params.term }; },
                processResults: function(data){
                    var out = [];
                    if (data && data.results) {
                        out = data.results.filter(r => r.type === 'customer').map(function(r){ return { id: r.id, text: r.text }; });
                    }
                    return { results: out };
                }
            },
            minimumInputLength: 1
        });
    };
    document.head.appendChild(s);
})();
// pass customers from server to client for the modal select
@php
    $jsCustomers = isset($customers) ? $customers : [];
@endphp
window.__customers = {!! json_encode($jsCustomers, JSON_UNESCAPED_UNICODE) !!};

$(document).ready(function() {
    // Sayfa yüklendiğinde animasyonlar
    $('.stats-card').each(function(index) {
        $(this).delay(index * 100).animate({
            opacity: 1
        }, 500);
    });

    // Tablo satırları için animasyon
    $('.cheque-item').each(function(index) {
        $(this).css('opacity', '0').delay(index * 30).animate({
            opacity: 1
        }, 300);
    });

    // Tablo hover efektleri
    $('.cheque-item').hover(
        function() {
            $(this).addClass('table-hover-effect');
        },
        function() {
            $(this).removeClass('table-hover-effect');
        }
    );
});

// Ensure CSRF header is set for all AJAX requests
if (typeof $ !== 'undefined' && $.ajaxSetup) {
    try {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    } catch(e) { console.error('ajaxSetup failed', e); }
}

function applyFilters() {
    const chequeNumber = $('#chequeNumber').val().toLowerCase();
    const bankName = $('#bankName').val();
    const status = $('#status').val();
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();

    let visibleCount = 0;

    $('.cheque-item').each(function() {
        const item = $(this);
        const itemChequeNumber = item.data('cheque-number').toLowerCase();
        const itemBank = item.data('bank');
        const itemStatus = item.data('status');
        const itemIssueDate = item.data('issue-date');
        const itemDueDate = item.data('due-date');

        let show = true;

        // Çek numarası filtresi
        if (chequeNumber && !itemChequeNumber.includes(chequeNumber)) {
            show = false;
        }

        // Banka filtresi
        if (bankName && itemBank !== bankName) {
            show = false;
        }

        // Durum filtresi
        if (status && itemStatus !== status) {
            show = false;
        }

        // Tarih filtreleri
        if (startDate && itemIssueDate < startDate) {
            show = false;
        }

        if (endDate && itemDueDate > endDate) {
            show = false;
        }

        if (show) {
            item.show();
            visibleCount++;
        } else {
            item.hide();
        }
    });

    // Boş durum kontrolü
    if (visibleCount === 0) {
        $('#emptyState').show();
    } else {
        $('#emptyState').hide();
    }

    // Toast mesajı
    showToast('success', `${visibleCount} çek listelendi`);
}

function clearFilters() {
    $('#chequeFilter')[0].reset();
    $('.cheque-item').show();
    $('#emptyState').hide();
    showToast('info', 'Filtreler temizlendi');
}

function viewCheque(id) {
    // Çek detaylarını göster (basit gösterim)
    // Eğer ileride detay modalı eklenecekse buraya AJAX ile veri çekme yerleştirilebilir
    alert('Çek detayları görüntüleniyor: ' + id);
}

function editCheque(id) {
    // Çek düzenleme (şimdilik basit gösterim / yönlendirme eklenebilir)
    // Send AJAX DELETE to server
    $.ajax({
        url: '/finance/cheques/' + encodeURIComponent(id),
        method: 'POST', // use POST with _method=DELETE for Laravel friendliness
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res && res.ok) {
                // remove row
                try {
                    var btn = $("*[onclick]").filter(function() {
                        var v = $(this).attr('onclick') || '';
                        return v.indexOf('cancelCheque(' + id + ')') !== -1 || v.indexOf("cancelCheque('" + id + "')") !== -1;
                    }).first();
                    if (btn && btn.length) {
                        btn.closest('tr').fadeOut(200, function() { $(this).remove(); });
                    } else {
                        // fallback: remove row by data attribute
                        $('tr[data-cheque-id="' + id + '"]').fadeOut(200, function(){ $(this).remove(); });
                    }
                } catch (e) { console.error(e); }
                showToast('success', 'Çek #' + id + ' iptal edildi');
            } else {
                showToast('error', res && res.message ? res.message : 'İptal yapılamadı');
            }
        },
        error: function(err) {
            if (err.status === 403) {
                showToast('error', 'Giriş gerekli. Yönlendiriliyorsunuz...');
                setTimeout(function(){ window.location = '/login'; }, 800);
                return;
            }
            var msg = 'Sunucu hatası';
            try { msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : msg; } catch(e){}
            showToast('error', msg);
        }
    });
}

// Yeni çek ekleme - modal açma
function addNewCheque() {
    // populate customers into select if present
    // if backend provides a global JS variable `window.__customers` we can fill the select
    if (window.__customers && Array.isArray(window.__customers)) {
        const sel = $('#newChequeCustomer');
        sel.empty();
        sel.append('<option value="">-- Müşteri Seçiniz --</option>');
        window.__customers.forEach(c => {
            sel.append(`<option value="${c.id}">${c.name}</option>`);
        });
    }
    console.log('addNewCheque called');
    try {
        var modalEl = document.getElementById('newChequeModal');

        // If Bootstrap 5 JS is available, prefer its Modal API
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            inst.show();
            return;
        }

        // If jQuery + bootstrap modal plugin available (older projects), use it
        if (typeof $ !== 'undefined' && typeof $('#newChequeModal').modal === 'function') {
            $('#newChequeModal').modal('show');
            return;
        }

        // Last-resort DOM fallback: show modal element and add backdrop
        if (modalEl) {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            // create lightweight backdrop compatible with bootstrap styles
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    } catch (e) {
        console.error('Failed to show modal', e);
        try { document.getElementById('newChequeModal').style.display = 'block'; } catch(_){ }
    }
}

// submit handler for new cheque
function submitNewCheque(e) {
    e.preventDefault();
    console.log('submitNewCheque invoked');
    var fd = new FormData();
    fd.append('cheque_number', $('#newChequeNumber').val());
    fd.append('bank_name', $('#newChequeBank').val());
    fd.append('branch', $('#newChequeBranch').val());
    fd.append('amount', $('#newChequeAmount').val());
    fd.append('issue_date', $('#newChequeIssueDate').val());
    fd.append('due_date', $('#newChequeDueDate').val());
    fd.append('customer_id', $('#newChequeCustomer').val());
    fd.append('notes', $('#newChequeNotes').val());
    var file = $('#newChequeImage').prop('files')[0];
    if (file) fd.append('image', file);

    // disable submit button to prevent double submits
    var $btn = $('#newChequeForm button[type="submit"]');
    $btn.prop('disabled', true).text('Kaydediliyor...');
    console.log('New cheque payload', { cheque_number: $('#newChequeNumber').val(), amount: $('#newChequeAmount').val(), customer_id: $('#newChequeCustomer').val(), hasFile: !!file });

    // Simple client-side validation
    if (!data.cheque_number || !data.amount) {
        showToast('error', 'Lütfen çek numarası ve tutar girin');
        return;
    }

    // POST to server (supports multipart)
    $.ajax({
        url: '/finance/cheques/store',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            console.log('cheques.store response', res);
            if (res.ok) {
                    showToast('success', 'Çek kaydedildi');
                    $('#newChequeModal').modal('hide');
                    // append new row to table (optimistic)
                    try {
                        var c = res.cheque;
                        var recipient = res.customer_name || '';
                        var due = c.due_date ? new Date(c.due_date).toLocaleDateString('tr-TR') : '';
                        var issue = c.issue_date ? new Date(c.issue_date).toLocaleDateString('tr-TR') : '';
                        var row = `<tr class="cheque-item" data-cheque-number="${c.cheque_number || ''}" data-bank="${c.bank_name || ''}" data-status="${c.status || 'pending'}" data-issue-date="${c.issue_date || ''}" data-due-date="${c.due_date || ''}">`+
                            `<td class="cheque-number-cell"><strong>${c.cheque_number || ''}</strong><small class="d-block text-muted">${c.account_number || ''}</small></td>`+
                            `<td class="bank-cell"><div class="bank-name">${c.bank_name || ''}</div><div class="branch-name">${c.branch || ''}</div></td>`+
                            `<td class="amount-cell">₺${(parseFloat(c.amount)||0).toLocaleString('tr-TR')}</td>`+
                            `<td class="recipient-cell">${recipient || (c.recipient || '')}</td>`+
                            `<td class="date-cell"><div>${due}</div><small class="text-muted">${issue} düzenlendi</small></td>`+
                            `<td class="status-cell"><span class="status-badge status-${c.status||'pending'}">${(c.status||'pending')}</span></td>`+
                            `<td class="actions-cell"><button class="action-btn btn-view-table" onclick="viewCheque('${c.id}')" title="Görüntüle"><i class="fas fa-eye"></i></button></td></tr>`;
                        $('#chequesList table tbody').prepend(row);
                    } catch(e){ console.error(e); }
                } else {
                    showToast('error', res.message || 'Kayıt sırasında hata oluştu');
                }
        },
        error: function(err) {
            console.error('cheques.store error', err);
            $btn.prop('disabled', false).text('Kaydet');
            if (err.status === 403) {
                showToast('error', 'Giriş gerekli. Yönlendiriliyorsunuz...');
                setTimeout(function(){ window.location = '/login'; }, 1000);
                return;
            }
            // Validation errors (422)
            if (err.status === 422 && err.responseJSON && err.responseJSON.errors) {
                var msgs = [];
                Object.keys(err.responseJSON.errors).forEach(function(k){ msgs = msgs.concat(err.responseJSON.errors[k]); });
                console.error('Validation failed', err.responseJSON.errors);
                showToast('error', msgs.join('; '));
                return;
            }

            var msg = 'Sunucu hatası';
            try { console.error(err); msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : msg; } catch(e){}
            showToast('error', msg);
        }
    });
}

// attach submit
$(document).on('submit', '#newChequeForm', submitNewCheque);

// Toast fonksiyonu
function showToast(type, message) {
    // Simple toast implementation
    const toast = $(`
        <div class="alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show"
             style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('body').append(toast);

    setTimeout(() => {
        toast.alert('close');
    }, 3000);
}

// Enter tuşu ile filtreleme
$('#chequeFilter input, #chequeFilter select').on('keypress', function(e) {
    if (e.which == 13) {
        applyFilters();
    }
});

// Assign to supplier: modal open and submit
function openAssignModal(chequeId) {
    $('#assignChequeId').val(chequeId);
    // clear previous selection
    if ($('#assignSupplierSelect').data('select2')) {
        $('#assignSupplierSelect').val(null).trigger('change');
    }
    $('#assignSupplierModal').modal('show');
}

// initialize Select2 for assign supplier when script loaded
;(function initAssignSelect() {
    // lazy-load select2 if not present
    var wait = setInterval(function(){
        if (typeof $().select2 === 'function') {
            clearInterval(wait);
            $('#assignSupplierSelect').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Tedarikçi Ara veya Seç --',
                dropdownParent: $('#assignSupplierModal'),
                ajax: {
                    url: '/finance/accounts/vouchers/search-parties',
                    dataType: 'json',
                    delay: 250,
                    data: function(params){ return { q: params.term }; },
                    processResults: function(data){
                        var out = [];
                        if (data && data.results) {
                            out = data.results.filter(r => (r.type === 'supplier' || r.type === 'vendor')).map(function(r){ return { id: r.id, text: r.text }; });
                        }
                        return { results: out };
                    }
                },
                minimumInputLength: 1
            });
        }
    }, 150);
})();

// submit assign
function submitAssign(e) {
    e.preventDefault();
    var chequeId = $('#assignChequeId').val();
    var supplierId = $('#assignSupplierSelect').val();
    if (!supplierId) {
        showToast('error', 'Lütfen bir tedarikçi seçin');
        return;
    }

    $.ajax({
        url: '/finance/cheques/' + encodeURIComponent(chequeId) + '/assign',
        method: 'POST',
        data: { supplier_id: supplierId },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res && res.ok) {
                showToast('success', 'Çek tedarikçiye atandı');
                $('#assignSupplierModal').modal('hide');
                // update row UI: mark status 'assigned' or show supplier name
                try {
                    var row = $('.cheque-item').filter(function(){ return $(this).data('cheque-id') == chequeId; }).first();
                    if (row && row.length) {
                        row.find('.status-cell .status-badge').text('Atandı').removeClass().addClass('status-badge status-cashed');
                        // optionally append supplier name
                        if (res.supplier_name) {
                            var rec = row.find('.recipient-cell');
                            rec.html(res.supplier_name);
                        }
                    }
                } catch(e){ console.error(e); }
            } else {
                showToast('error', res.message || 'Atama sırasında hata oluştu');
            }
        },
        error: function(err) {
            var msg = 'Sunucu hatası';
            try { msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : msg; } catch(e){}
            showToast('error', msg);
        }
    });
}

$(document).on('submit', '#assignSupplierForm', submitAssign);
</script>
@endpush

{{-- Yeni Çek Modal --}}
<div class="modal fade" id="newChequeModal" tabindex="-1" aria-labelledby="newChequeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newChequeModalLabel">Yeni Çek Ekle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <form id="newChequeForm">
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Çek Numarası</label>
            <input type="text" id="newChequeNumber" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tutar</label>
            <input type="number" id="newChequeAmount" class="form-control" step="0.01" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Banka</label>
            <input type="text" id="newChequeBank" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Şube</label>
            <input type="text" id="newChequeBranch" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Düzenlenme Tarihi</label>
            <input type="date" id="newChequeIssueDate" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Vade Tarihi</label>
            <input type="date" id="newChequeDueDate" class="form-control">
          </div>

          <div class="col-12">
            <label class="form-label">Müşteri</label>
            <select id="newChequeCustomer" class="form-control">
              <option value="">-- Müşteri Seçiniz --</option>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Notlar</label>
            <textarea id="newChequeNotes" class="form-control" rows="3"></textarea>
          </div>
                    <div class="col-12">
                        <label class="form-label">Çek Görseli</label>
                        <input type="file" id="newChequeImage" name="image" accept="image/*" class="form-control">
                    </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Kaydet</button>
      </div>
      </form>
    </div>
  </div>
</div>

{{-- Assign to Supplier Modal --}}
<div class="modal fade" id="assignSupplierModal" tabindex="-1" aria-labelledby="assignSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignSupplierModalLabel">Çeki Tedarikçiye Ver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form id="assignSupplierForm">
            <div class="modal-body">
                <input type="hidden" id="assignChequeId" value="">
                <div class="mb-3">
                        <label class="form-label">Tedarikçi</label>
                        <select id="assignSupplierSelect" class="form-control" style="width:100%"></select>
                </div>
                <p class="text-muted">Seçilen tedarikçiye çek atanacak. İşlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" class="btn btn-primary">Atama Yap</button>
            </div>
            </form>
        </div>
    </div>
</div>
