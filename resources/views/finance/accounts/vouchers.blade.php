@extends('layouts.master')
@section('title', 'Cari Fişler - Mali Yönetim')
@section('topbar-title', 'Finans - Cari Hesap Fişleri')

@section('styles')
<style>
    .voucher-stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .voucher-stats-card .card-body {
        padding: 1.5rem;
    }
    
    .voucher-stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .voucher-stats-label {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: 0;
    }
    
    .voucher-quick-stats {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }
    
    .voucher-type-btn {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        transition: all 0.3s ease;
        min-height: 60px;
        border: none;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .voucher-type-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        color: white;
    }
    
    .voucher-type-btn.btn-collection {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .voucher-type-btn.btn-payment {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
    }
    
    .voucher-type-btn.btn-journal {
        background: linear-gradient(135deg, #6f42c1, #e83e8c);
    }
    
    .voucher-table-wrapper {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .voucher-table-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .voucher-table-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #495057;
    }
    
    .voucher-table-subtitle {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .voucher-filters-bar {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .voucher-search-input {
        border-radius: 25px;
        border: 1px solid #ced4da;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .voucher-search-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        border-color: #80bdff;
    }
    
    .voucher-filter-btn {
        border-radius: 20px;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        margin: 0 0.2rem;
        transition: all 0.3s ease;
    }
    
    .voucher-filter-btn.active {
        transform: scale(1.05);
    }
    
    .voucher-table {
        margin: 0;
    }
    
    .voucher-table thead th {
        background: linear-gradient(135deg, #495057, #6c757d);
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem 0.75rem;
        font-size: 0.9rem;
    }
    
    .voucher-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .voucher-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }
    
    .voucher-table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border: none;
    }
    
    .voucher-type-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
    }
    
    .voucher-type-collection {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .voucher-type-payment {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
    }
    
    .voucher-type-journal {
        background: linear-gradient(135deg, #6f42c1, #e83e8c);
    }
    
    .voucher-amount {
        font-family: 'Monaco', 'Menlo', monospace;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .voucher-amount.positive {
        color: #28a745;
    }
    
    .voucher-amount.negative {
        color: #dc3545;
    }
    
    .voucher-date {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .voucher-actions .btn {
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 0 0.1rem;
        transition: all 0.2s ease;
    }
    
    .voucher-actions .btn:hover {
        transform: scale(1.1);
    }
    
    .voucher-pagination-wrapper {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-top: 1px solid #dee2e6;
    }
    
    .voucher-progress {
        height: 4px;
        border-radius: 2px;
        background: #e9ecef;
        overflow: hidden;
    }
    
    .voucher-progress-bar {
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    
    .sidebar-stats {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }
    
    .sidebar-stats h5 {
        color: #495057;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .stats-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .stats-item:last-child {
        border-bottom: none;
    }
    
    .stats-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .stats-value {
        font-weight: 600;
        color: #495057;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .voucher-no {
        font-family: 'Monaco', 'Menlo', monospace;
        background: #f8f9fa;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    @media (max-width: 768px) {
        .voucher-type-btn {
            margin-bottom: 0.5rem;
            width: 100%;
        }
        
        .voucher-table-wrapper {
            margin: 0 -15px;
            border-radius: 0;
        }
        
        .voucher-table thead {
            display: none;
        }
        
        .voucher-table tr {
            display: block;
            border: 1px solid #dee2e6;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            background: white;
        }
        
        .voucher-table td {
            display: block;
            text-align: right;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .voucher-table td:before {
            content: attr(data-label) ": ";
            float: left;
            font-weight: 600;
            color: #495057;
        }
    }
</style>
@endsection

@section('content')
    <!-- Header Section with Stats -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="voucher-stats-card card">
                <div class="card-body text-center">
                    <h2 class="voucher-stats-number" id="dashboard-total-vouchers">0</h2>
                    <p class="voucher-stats-label">Toplam Cari Fiş</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="voucher-quick-stats">
                <div class="text-center">
                    <small class="text-muted">Bu Ay</small>
                    <h4 class="mb-0" id="monthly-vouchers">0</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-md-4 mb-2">
                    <button id="btn-collection" class="btn voucher-type-btn btn-collection w-100">
                        <i class="mdi mdi-cash-plus me-2"></i>
                        Tahsilat Fişi
                        <small class="d-block">Müşteriden Para Tahsili</small>
                    </button>
                </div>
                <div class="col-md-4 mb-2">
                    <button id="btn-payment" class="btn voucher-type-btn btn-payment w-100">
                        <i class="mdi mdi-cash-minus me-2"></i>
                        Tediye Fişi
                        <small class="d-block">Tedarikçiye Ödeme</small>
                    </button>
                </div>
                <div class="col-md-4 mb-2">
                    <button id="btn-journal" class="btn voucher-type-btn btn-journal w-100">
                        <i class="mdi mdi-swap-horizontal me-2"></i>
                        Mahsup Fişi
                        <small class="d-block">Borç-Alacak Kaydı</small>
                    </button>
                </div>
            </div>

            <!-- Main Table -->
            <div class="voucher-table-wrapper">
                <div class="voucher-table-header">
                    <h4 class="voucher-table-title">Cari Fiş Listesi</h4>
                    <p class="voucher-table-subtitle">Tüm cari işlemlerinizi buradan görüntüleyebilir ve yönetebilirsiniz</p>
                </div>
                
                <div class="voucher-filters-bar">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <input type="text" id="voucher-search" class="form-control voucher-search-input border-start-0" 
                                       placeholder="Fiş ara... (No, Cari, Açıklama)">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-primary voucher-filter-btn active" data-filter="all">
                                <i class="mdi mdi-view-list me-1"></i>Tümü
                            </button>
                            <button class="btn btn-outline-success voucher-filter-btn" data-filter="collection">
                                <i class="mdi mdi-cash-plus me-1"></i>Tahsilat
                            </button>
                            <button class="btn btn-outline-danger voucher-filter-btn" data-filter="payment">
                                <i class="mdi mdi-cash-minus me-1"></i>Tediye
                            </button>
                            <button class="btn btn-outline-secondary voucher-filter-btn" data-filter="journal">
                                <i class="mdi mdi-swap-horizontal me-1"></i>Mahsup
                            </button>
                            <button id="btn-bulk-delete" class="btn btn-outline-danger voucher-filter-btn ms-2">
                                <i class="mdi mdi-delete me-1"></i>Seçiliyi Sil
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table voucher-table" id="recent-vouchers-table">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input type="checkbox" id="select-all-vouchers" class="form-check-input">
                                    </div>
                                </th>
                                <th>Tarih</th>
                                <th>Fiş No</th>
                                <th>Tür</th>
                                <th>Cari Hesap</th>
                                <th>Tutar</th>
                                <th>Açıklama</th>
                                <th width="150">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center p-4">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border text-primary me-2" role="status">
                                            <span class="visually-hidden">Yükleniyor...</span>
                                        </div>
                                        <span class="text-muted">Veriler yükleniyor...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="voucher-pagination-wrapper">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div id="vouchers-progress" class="voucher-progress" style="display:none;">
                                <div id="vouchers-progress-bar" class="voucher-progress-bar" style="width:0%"></div>
                            </div>
                            <small class="text-muted" id="pagination-info">Sayfa bilgisi yükleniyor...</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" id="vouchers-pagination">
                                <button id="vouchers-prev" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-chevron-left"></i> Önceki
                                </button>
                                <button id="vouchers-next" class="btn btn-outline-secondary">
                                    Sonraki <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <!-- Quick Stats -->
            <div class="sidebar-stats">
                <h5><i class="mdi mdi-chart-line me-2"></i>Özet Bilgiler</h5>
                <div class="stats-item">
                    <span class="stats-label">Toplam Fiş</span>
                    <span class="stats-value" id="total-vouchers">-</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Bu Ay</span>
                    <span class="stats-value" id="monthly-count">-</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Tahsilat Toplam</span>
                    <span class="stats-value text-success" id="collection-total">-</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Tediye Toplam</span>
                    <span class="stats-value text-danger" id="payment-total">-</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Son Güncelleme</span>
                    <span class="stats-value" id="last-updated">-</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="sidebar-stats">
                <h5><i class="mdi mdi-lightning-bolt me-2"></i>Hızlı İşlemler</h5>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" id="btn-export-excel">
                        <i class="mdi mdi-file-excel me-1"></i>Excel'e Aktar
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" id="btn-print-report">
                        <i class="mdi mdi-printer me-1"></i>Rapor Yazdır
                    </button>
                    <button class="btn btn-outline-info btn-sm" id="btn-backup">
                        <i class="mdi mdi-backup-restore me-1"></i>Yedek Al
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Voucher Modal -->
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0">
                    <div>
                        <h4 class="modal-title mb-0" id="voucherModalTitle">
                            <i class="mdi mdi-receipt me-2"></i>Fiş Oluştur
                        </h4>
                        <small class="text-white-50" id="voucherModalSubtitle">Yeni mali işlem kaydı</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="voucher-form">
                        <input type="hidden" name="type" id="voucher-type" />
                        
                        <!-- Basic Information Card -->
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="text-primary mb-0">
                                    <i class="mdi mdi-information-outline me-2"></i>Temel Bilgiler
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="mdi mdi-calendar me-1"></i>Tarih
                                        </label>
                                        <input type="date" name="date" id="voucher-date" 
                                               class="form-control form-control-lg" 
                                               value="{{ now()->toDateString() }}" required />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="mdi mdi-file-document-outline me-1"></i>Fiş No
                                        </label>
                                        <input type="text" name="no" id="voucher-no" 
                                               class="form-control form-control-lg" 
                                               placeholder="Otomatik oluşturulacak" />
                                        <div class="form-text">Boş bırakırsanız otomatik numara atanır</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="mdi mdi-currency-try me-1"></i>Tutar
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="amount" id="voucher-amount" 
                                                   class="form-control form-control-lg" required />
                                            <span class="input-group-text">₺</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Counterparty Information Card -->
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="text-primary mb-0">
                                    <i class="mdi mdi-account-outline me-2"></i>Cari Hesap Bilgileri
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label fw-semibold">Cari Hesap / Karşı Taraf</label>
                                        <div class="position-relative">
                                            <input type="text" name="counterparty" id="voucher-counterparty" 
                                                   class="form-control form-control-lg" 
                                                   placeholder="Müşteri veya tedarikçi adı yazın..." autocomplete="off" />
                                            <input type="hidden" id="voucher-counterparty-id" name="counterparty_id" />
                                            <div id="counterparty-suggestions" class="list-group position-absolute w-100" 
                                                 style="z-index:1050;display:none;top:100%;max-height:200px;overflow-y:auto;">
                                            </div>
                                        </div>
                                        <div class="form-text">
                                            <i class="mdi mdi-lightbulb-outline me-1"></i>
                                            Yazmaya başlayın, mevcut cari hesaplar listelenecek
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="mdi mdi-credit-card-outline me-1"></i>Ödeme Yöntemi
                                        </label>
                                        <select name="payment_method" id="voucher-payment-method" 
                                                class="form-select form-select-lg">
                                            <option value="">Seçiniz</option>
                                            <option value="cash">💰 Nakit</option>
                                            <option value="bank_transfer">🏦 Banka Havalesi/EFT</option>
                                            <option value="pos">💳 Kredi Kartı / POS</option>
                                            <option value="check">📋 Çek</option>
                                            <option value="credit_note">📝 Kredi Notu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Card -->
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="text-primary mb-0">
                                    <i class="mdi mdi-text-box-outline me-2"></i>Açıklama ve Notlar
                                </h6>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" id="voucher-notes" 
                                          class="form-control" rows="4" 
                                          placeholder="İşlem detayları, fatura bilgileri, özel notlar..."></textarea>
                                <div class="form-text">
                                    Bu alan fatura eşleştirme bilgileri için de kullanılır
                                </div>
                            </div>
                        </div>

                        <!-- Journal Lines (Only for Mahsup) -->
                        <div id="journal-lines" class="card border-0 bg-warning bg-opacity-10" style="display:none;">
                            <div class="card-header bg-transparent border-0">
                                <h6 class="text-warning mb-0">
                                    <i class="mdi mdi-swap-horizontal me-2"></i>Mahsup Satırları
                                </h6>
                                <small class="text-muted">Borç ve alacak tutarları eşit olmalıdır</small>
                            </div>
                            <div class="card-body">
                                <div id="journal-rows" class="mb-3">
                                    <!-- Dynamic journal rows will be added here -->
                                </div>
                                <button type="button" id="add-journal-row" 
                                        class="btn btn-outline-warning">
                                    <i class="mdi mdi-plus me-1"></i>Yeni Satır Ekle
                                </button>
                                <div class="mt-3 p-3 border rounded bg-white">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <span class="text-muted">Toplam Borç</span>
                                            <h5 class="text-success mb-0" id="total-debit">0.00 ₺</h5>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted">Toplam Alacak</span>
                                            <h5 class="text-danger mb-0" id="total-credit">0.00 ₺</h5>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="badge" id="balance-status">Denge Kontrol Ediliyor</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <!-- Invoice Suggestion Box -->
                    <div id="invoice-suggestion-box" class="card border-0 bg-info bg-opacity-10 mt-4" style="display:none;">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="text-info mb-0">
                                <i class="mdi mdi-file-document-multiple-outline me-2"></i>Fatura Eşleştirme Önerileri
                            </h6>
                            <small class="text-muted">Bu cari hesaba ait ödenmemiş faturalar</small>
                        </div>
                        <div class="card-body">
                            <div id="invoice-suggestions" class="list-group list-group-flush">
                                <!-- Invoice suggestions will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>İptal
                    </button>
                    <button type="button" id="submit-voucher" class="btn btn-primary btn-lg">
                        <i class="mdi mdi-check me-1"></i>
                        <span id="submit-voucher-text">Fişi Kaydet</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    (function(){
        const modalEl = new bootstrap.Modal(document.getElementById('voucherModal'));
        const form = document.getElementById('voucher-form');
        const isAdmin = @json(auth()->check() && auth()->user()->is_admin);
        const currentUserId = @json(auth()->check() ? auth()->id() : null);
        
        let currentFilter = 'all';
        let searchTerm = '';
        let totalStats = {
            collection: { count: 0, amount: 0 },
            payment: { count: 0, amount: 0 },
            journal: { count: 0, amount: 0 }
        };

        // Enhanced modal opener with animations and better UX
        function openModal(type){
            // Reset form
            form.reset();
            delete form.dataset.editId;
            
            // Set voucher type
            document.getElementById('voucher-type').value = type;
            
            // Update modal title and subtitle based on type
            const titles = {
                'collection': {
                    title: '<i class="mdi mdi-cash-plus me-2"></i>Tahsilat Fişi Oluştur',
                    subtitle: 'Müşteriden para tahsil işlemi'
                },
                'payment': {
                    title: '<i class="mdi mdi-cash-minus me-2"></i>Tediye Fişi Oluştur', 
                    subtitle: 'Tedarikçiye ödeme işlemi'
                },
                'journal': {
                    title: '<i class="mdi mdi-swap-horizontal me-2"></i>Mahsup Fişi Oluştur',
                    subtitle: 'Borç-Alacak kayıt işlemi'
                }
            };
            
            document.getElementById('voucherModalTitle').innerHTML = titles[type].title;
            document.getElementById('voucherModalSubtitle').innerText = titles[type].subtitle;
            document.getElementById('submit-voucher-text').innerText = 'Fişi Kaydet';
            
            // Show/hide journal lines
            const journalLines = document.getElementById('journal-lines');
            if (type === 'journal') {
                journalLines.style.display = 'block';
                // Add default rows if none exist
                const journalRows = document.getElementById('journal-rows');
                if (journalRows.children.length === 0) {
                    addJournalRow();
                    addJournalRow();
                }
                updateJournalTotals();
            } else {
                journalLines.style.display = 'none';
            }
            
            // Set default date to today
            document.getElementById('voucher-date').value = new Date().toISOString().split('T')[0];
            
            // Handle voucher number permissions
            const voucherNoInput = document.getElementById('voucher-no');
            if (!isAdmin) {
                voucherNoInput.setAttribute('readonly', 'readonly');
                voucherNoInput.placeholder = 'Otomatik oluşturulacak';
            } else {
                voucherNoInput.removeAttribute('readonly');
                voucherNoInput.placeholder = 'Otomatik veya manuel girin';
            }
            
            // Show modal with animation
            modalEl.show();
            
            // Focus on first available input
            setTimeout(() => {
                if (type !== 'journal') {
                    document.getElementById('voucher-counterparty').focus();
                } else {
                    document.querySelector('.jr-account')?.focus();
                }
            }, 300);
        }

        // Button event listeners with loading states
        document.getElementById('btn-collection').addEventListener('click', ()=> {
            showButtonLoading('btn-collection');
            setTimeout(() => {
                openModal('collection');
                hideButtonLoading('btn-collection', '<i class="mdi mdi-cash-plus me-2"></i>Tahsilat Fişi<small class="d-block">Müşteriden Para Tahsili</small>');
            }, 200);
        });
        
        document.getElementById('btn-payment').addEventListener('click', ()=> {
            showButtonLoading('btn-payment');
            setTimeout(() => {
                openModal('payment');
                hideButtonLoading('btn-payment', '<i class="mdi mdi-cash-minus me-2"></i>Tediye Fişi<small class="d-block">Tedarikçiye Ödeme</small>');
            }, 200);
        });
        
        document.getElementById('btn-journal').addEventListener('click', ()=> {
            showButtonLoading('btn-journal');
            setTimeout(() => {
                openModal('journal');
                hideButtonLoading('btn-journal', '<i class="mdi mdi-swap-horizontal me-2"></i>Mahsup Fişi<small class="d-block">Borç-Alacak Kaydı</small>');
            }, 200);
        });

        function showButtonLoading(buttonId) {
            const btn = document.getElementById(buttonId);
            btn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Yükleniyor...';
            btn.disabled = true;
        }

        function hideButtonLoading(buttonId, originalContent) {
            const btn = document.getElementById(buttonId);
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }

        document.getElementById('submit-voucher').addEventListener('click', async ()=>{
            const data = new FormData(form);
            // if journal lines exist, collect them
            if(document.getElementById('voucher-type').value==='journal'){
                const rows = [];
                document.querySelectorAll('#journal-rows .jr-row').forEach(r=>{
                    rows.push({account: r.querySelector('.jr-account').value, debit: parseFloat(r.querySelector('.jr-debit').value||0), credit: parseFloat(r.querySelector('.jr-credit').value||0)});
                });
                data.append('lines', JSON.stringify(rows));
                // basic validation: sums equal
                const totalDebit = rows.reduce((s,x)=>s+(x.debit||0),0);
                const totalCredit = rows.reduce((s,x)=>s+(x.credit||0),0);
                if(Math.abs(totalDebit - totalCredit) > 0.0001){ alert('Mahsup fişi için toplam Borç ve Alacak eşit olmalıdır.'); return; }
                data.append('amount', totalDebit);
            }

            const payload = {};
            data.forEach((v,k)=>{ payload[k]=v; });

            try{
                const editId = form.dataset.editId;
                let res;
                if(editId){
                    // update
                    res = await fetch(`{{ url('finance/accounts/vouchers') }}/${editId}`, {method:'PUT', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: data});
                } else {
                    res = await fetch("{{ route('finance.accounts.vouchers.store') }}", {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body: data});
                }
                const json = await res.json();
                if(json.ok){
                    modalEl.hide();
                    // clear edit flag
                    delete form.dataset.editId;
                    refreshList(currentPage);
                    alert('Fiş kaydedildi.');
                } else {
                    alert('Hata: ' + (json.message || 'Kaydedilemedi'));
                }
            } catch(e){
                console.error(e); alert('Sunucu hatası: ' + e.message);
            }
        });

        function openModalForEdit(v){
            // v is object
            document.getElementById('voucher-type').value = v.type;
            document.getElementById('voucherModalTitle').innerText = 'Fiş Düzenle';
            document.getElementById('voucher-date').value = v.date ? v.date.split('T')[0] : '';
            document.getElementById('voucher-no').value = v.no || (v.id?('VCH-'+String(v.id).padStart(6,'0')):'');
            document.getElementById('voucher-counterparty').value = v.counterparty || '';
            document.getElementById('voucher-counterparty-id').value = v.counterparty_id || '';
            document.getElementById('voucher-amount').value = v.amount || '';
            document.getElementById('voucher-payment-method').value = v.payment_method || '';
            document.getElementById('voucher-notes').value = v.notes || '';
            // journal lines
            const jr = document.getElementById('journal-rows'); jr.innerHTML = '';
            if(v.lines && Array.isArray(v.lines) && v.lines.length){
                v.lines.forEach(row => {
                    addJournalRow();
                    const last = jr.children[jr.children.length-1];
                    last.querySelector('.jr-account').value = row.account || '';
                    last.querySelector('.jr-debit').value = row.debit || '';
                    last.querySelector('.jr-credit').value = row.credit || '';
                });
            }
            document.getElementById('journal-lines').style.display = (v.type==='journal') ? 'block' : 'none';
            form.dataset.editId = v.id;
            if(!isAdmin) document.getElementById('voucher-no').setAttribute('readonly','readonly'); else document.getElementById('voucher-no').removeAttribute('readonly');
            modalEl.show();
        }

        // Enhanced journal row management
        function addJournalRow() {
            const container = document.getElementById('journal-rows');
            const idx = container.children.length + 1;
            const div = document.createElement('div');
            div.className = 'jr-row mb-3 border rounded p-3 bg-white position-relative';
            div.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <span class="badge bg-secondary">${idx}</span>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small">Hesap Kodu/Açıklama</label>
                        <input class="form-control jr-account" placeholder="Ör: 100.01 - Kasa" required/>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-success">Borç (₺)</label>
                        <input class="form-control jr-debit" type="number" step="0.01" placeholder="0.00" 
                               oninput="updateJournalTotals()"/>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-danger">Alacak (₺)</label>
                        <input class="form-control jr-credit" type="number" step="0.01" placeholder="0.00" 
                               oninput="updateJournalTotals()"/>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger btn-remove-row position-absolute" 
                        style="top: 10px; right: 10px;" title="Satırı Sil">
                    <i class="mdi mdi-close"></i>
                </button>
            `;
            container.appendChild(div);
            
            // Add remove functionality
            div.querySelector('.btn-remove-row').addEventListener('click', () => {
                div.remove();
                updateJournalTotals();
                updateRowNumbers();
            });
            
            // Focus on account input
            div.querySelector('.jr-account').focus();
            
            // Update totals
            updateJournalTotals();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('.jr-row');
            rows.forEach((row, index) => {
                const badge = row.querySelector('.badge');
                if (badge) badge.textContent = index + 1;
            });
        }

        function updateJournalTotals() {
            const debitInputs = document.querySelectorAll('.jr-debit');
            const creditInputs = document.querySelectorAll('.jr-credit');
            
            let totalDebit = 0;
            let totalCredit = 0;
            
            debitInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                totalDebit += value;
            });
            
            creditInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                totalCredit += value;
            });
            
            // Update display
            document.getElementById('total-debit').textContent = totalDebit.toFixed(2) + ' ₺';
            document.getElementById('total-credit').textContent = totalCredit.toFixed(2) + ' ₺';
            
            // Update balance status
            const balanceStatus = document.getElementById('balance-status');
            const difference = Math.abs(totalDebit - totalCredit);
            
            if (difference < 0.01) {
                balanceStatus.textContent = '✓ Dengede';
                balanceStatus.className = 'badge bg-success';
            } else {
                balanceStatus.textContent = `⚠ Fark: ${difference.toFixed(2)} ₺`;
                balanceStatus.className = 'badge bg-warning';
            }
            
            return { totalDebit, totalCredit, balanced: difference < 0.01 };
        }

        // Make updateJournalTotals globally available
        window.updateJournalTotals = updateJournalTotals;

        document.getElementById('add-journal-row').addEventListener('click', addJournalRow);

        // Enhanced date formatting function
        function fmtDate(dstr) {
            if (!dstr) return '';
            try {
                const dt = new Date(dstr);
                if (isNaN(dt)) return dstr;
                return dt.toLocaleDateString('tr-TR', {
                    day: '2-digit',
                    month: '2-digit', 
                    year: 'numeric'
                });
            } catch (e) {
                return dstr;
            }
        }

        // Enhanced delete function with confirmation
        async function onDeleteClick(e) {
            const id = e.currentTarget.getAttribute('data-id');
            const tr = e.currentTarget.closest('tr');
            let voucherInfo = 'Bu fiş';
            
            if (tr && tr.dataset.voucher) {
                try {
                    const voucher = JSON.parse(decodeURIComponent(tr.dataset.voucher));
                    voucherInfo = `${getVoucherTypeText(voucher.type)} fişi (${voucher.no || 'No: ' + voucher.id})`;
                } catch (e) {
                    console.error('Parse error:', e);
                }
            }
            
            // Enhanced confirmation dialog
            const confirmModal = document.createElement('div');
            confirmModal.className = 'modal fade';
            confirmModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-delete-alert me-2"></i>Fiş Silme Onayı
                            </h5>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-alert-circle text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h6>${voucherInfo} silinecek!</h6>
                            <p class="text-muted">Bu işlem geri alınamaz. Devam etmek istediğinizden emin misiniz?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                            <button type="button" class="btn btn-danger" id="confirm-delete">
                                <i class="mdi mdi-delete me-1"></i>Evet, Sil
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(confirmModal);
            const modal = new bootstrap.Modal(confirmModal);
            modal.show();
            
            confirmModal.querySelector('#confirm-delete').addEventListener('click', async () => {
                const deleteBtn = confirmModal.querySelector('#confirm-delete');
                deleteBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Siliniyor...';
                deleteBtn.disabled = true;
                
                try {
                    const response = await fetch(`{{ url('finance/accounts/vouchers') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.ok) {
                        modal.hide();
                        showToast('Fiş başarıyla silindi', 'success');
                        refreshList(currentPage);
                    } else {
                        throw new Error(result.message || 'Silme işlemi başarısız');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    showToast(error.message, 'error');
                } finally {
                    modal.hide();
                }
            });
            
            confirmModal.addEventListener('hidden.bs.modal', () => {
                confirmModal.remove();
            });
        }

        // Enhanced bulk delete with progress
        document.getElementById('btn-bulk-delete').addEventListener('click', async () => {
            const selectedIds = Array.from(document.querySelectorAll('.select-voucher:checked'))
                .map(cb => cb.getAttribute('data-id'));
            
            if (!selectedIds.length) {
                showToast('Lütfen en az bir kayıt seçin', 'warning');
                return;
            }
            
            // Confirmation modal for bulk delete
            const confirmModal = document.createElement('div');
            confirmModal.className = 'modal fade';
            confirmModal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-delete-sweep me-2"></i>Toplu Silme Onayı
                            </h5>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-alert-multiple text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h6>${selectedIds.length} adet fiş silinecek!</h6>
                            <p class="text-muted">Bu işlem geri alınamaz ve tüm seçili kayıtlar kalıcı olarak silinir.</p>
                            <div class="progress mt-3" id="bulk-delete-progress" style="display: none;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                            <button type="button" class="btn btn-danger" id="confirm-bulk-delete">
                                <i class="mdi mdi-delete-sweep me-1"></i>Tümünü Sil
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(confirmModal);
            const modal = new bootstrap.Modal(confirmModal);
            modal.show();
            
            confirmModal.querySelector('#confirm-bulk-delete').addEventListener('click', async () => {
                const deleteBtn = confirmModal.querySelector('#confirm-bulk-delete');
                const progress = confirmModal.querySelector('#bulk-delete-progress');
                const progressBar = progress.querySelector('.progress-bar');
                
                deleteBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Siliniyor...';
                deleteBtn.disabled = true;
                progress.style.display = 'block';
                
                try {
                    const response = await fetch(`{{ route('finance.accounts.vouchers.bulk-delete') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    });
                    
                    const result = await response.json();
                    progressBar.style.width = '100%';
                    
                    if (result.ok) {
                        setTimeout(() => {
                            modal.hide();
                            showToast(`${selectedIds.length} adet fiş başarıyla silindi`, 'success');
                            refreshList(currentPage);
                        }, 500);
                    } else {
                        throw new Error(result.message || 'Toplu silme işlemi başarısız');
                    }
                } catch (error) {
                    console.error('Bulk delete error:', error);
                    showToast(error.message, 'error');
                    modal.hide();
                }
            });
            
            confirmModal.addEventListener('hidden.bs.modal', () => {
                confirmModal.remove();
            });
        });

        // Pagination event listeners
        document.getElementById('vouchers-prev').addEventListener('click', () => {
            if (currentPage > 1) {
                refreshList(currentPage - 1);
            }
        });

        document.getElementById('vouchers-next').addEventListener('click', () => {
            if (currentPage < lastPage) {
                refreshList(currentPage + 1);
            }
        });

        // Enhanced counterparty autocomplete with caching
        let counterpartyCache = new Map();
        let counterpartyTimer = null;
        
        const cpInput = document.getElementById('voucher-counterparty');
        const cpSuggestions = document.getElementById('counterparty-suggestions');
        
        cpInput.addEventListener('input', () => {
            clearTimeout(counterpartyTimer);
            const query = cpInput.value.trim();
            
            // Clear counterparty ID when input changes
            document.getElementById('voucher-counterparty-id').value = '';
            
            if (!query) {
                cpSuggestions.style.display = 'none';
                return;
            }
            
            // Check cache first
            if (counterpartyCache.has(query)) {
                displayCounterpartySuggestions(counterpartyCache.get(query));
                return;
            }
            
            counterpartyTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`{{ route('finance.accounts.vouchers.search-parties') }}?q=${encodeURIComponent(query)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    const result = await response.json();
                    
                    if (result.ok) {
                        // Cache the result
                        counterpartyCache.set(query, result.results);
                        displayCounterpartySuggestions(result.results);
                    }
                } catch (error) {
                    console.error('Counterparty search error:', error);
                }
            }, 300);
        });

        function displayCounterpartySuggestions(results) {
            cpSuggestions.innerHTML = '';
            
            if (results.length > 0) {
                results.forEach(result => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    item.innerHTML = `
                        <div>
                            <strong>${result.text}</strong>
                            <small class="d-block text-muted">${result.type === 'customer' ? 'Müşteri' : 'Tedarikçi'}</small>
                        </div>
                        <i class="mdi mdi-${result.type === 'customer' ? 'account' : 'truck'} text-muted"></i>
                    `;
                    
                    item.addEventListener('click', () => {
                        cpInput.value = result.text;
                        document.getElementById('voucher-counterparty-id').value = result.id;
                        cpSuggestions.style.display = 'none';
                        
                        // Load invoice suggestions for this party
                        loadInvoiceSuggestions(result.id);
                    });
                    
                    cpSuggestions.appendChild(item);
                });
                
                cpSuggestions.style.display = 'block';
            } else {
                cpSuggestions.style.display = 'none';
            }
        }

        // Enhanced invoice suggestion loading
        async function loadInvoiceSuggestions(partyId) {
            try {
                const response = await fetch(`{{ route('finance.accounts.vouchers.invoice-suggest') }}?party_id=${encodeURIComponent(partyId)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                
                const result = await response.json();
                const suggestionBox = document.getElementById('invoice-suggestion-box');
                const suggestionsList = document.getElementById('invoice-suggestions');
                
                suggestionsList.innerHTML = '';
                
                if (result.ok && result.results.length > 0) {
                    result.results.forEach(invoice => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                        item.innerHTML = `
                            <div>
                                <strong>${invoice.text}</strong>
                                <small class="d-block text-success">Kalan: ${formatCurrency(invoice.balance)}</small>
                            </div>
                            <i class="mdi mdi-file-document-outline text-muted"></i>
                        `;
                        
                        item.addEventListener('click', () => {
                            const notesField = document.getElementById('voucher-notes');
                            const currentNotes = notesField.value;
                            const invoiceNote = `Fatura Eşleştirme: ${invoice.text} (Kalan: ${formatCurrency(invoice.balance)})`;
                            
                            notesField.value = currentNotes ? `${currentNotes}\n${invoiceNote}` : invoiceNote;
                            
                            // Set amount to balance if not already set
                            const amountField = document.getElementById('voucher-amount');
                            if (!amountField.value) {
                                amountField.value = invoice.balance;
                            }
                            
                            showToast('Fatura eşleştirmesi eklendi', 'success', 2000);
                        });
                        
                        suggestionsList.appendChild(item);
                    });
                    
                    suggestionBox.style.display = 'block';
                } else {
                    suggestionBox.style.display = 'none';
                }
            } catch (error) {
                console.error('Invoice suggestion error:', error);
            }
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!cpInput.contains(e.target) && !cpSuggestions.contains(e.target)) {
                cpSuggestions.style.display = 'none';
            }
        });

        let currentPage = 1;
        let lastPage = 1;
        const perPage = 20;

        function fmtDate(dstr){
            if(!dstr) return '';
            try{
                const dt = new Date(dstr);
                if(isNaN(dt)) return dstr;
                const y = dt.getFullYear();
                const m = String(dt.getMonth()+1).padStart(2,'0');
                const day = String(dt.getDate()).padStart(2,'0');
                // Turkish date format dd.MM.yyyy
                return `${day}.${m}.${y}`;
            }catch(e){ return dstr; }
        }

        // Enhanced list refresh with better UX and filtering
        async function refreshList(page = 1) {
            currentPage = page;
            
            try {
                // Show progress with animation
                const progress = document.getElementById('vouchers-progress');
                const progressBar = document.getElementById('vouchers-progress-bar');
                const tbody = document.querySelector('#recent-vouchers-table tbody');
                
                if (progress) {
                    progress.style.display = 'block';
                    progressBar.style.width = '10%';
                }
                
                // Show loading skeleton
                tbody.innerHTML = generateLoadingSkeleton(8);
                
                // Build query parameters
                let queryParams = new URLSearchParams({
                    limit: perPage,
                    page: page
                });
                
                if (currentFilter !== 'all') {
                    queryParams.append('type', currentFilter);
                }
                
                if (searchTerm) {
                    queryParams.append('search', searchTerm);
                }
                
                const res = await fetch(`{{ route('finance.accounts.vouchers.list') }}?${queryParams}`, {
                    headers: {'Accept': 'application/json'}
                });
                
                if (progress) progressBar.style.width = '60%';
                
                const json = await res.json();
                
                if (json.ok) {
                    tbody.innerHTML = '';
                    
                    // Reset stats
                    totalStats = {
                        collection: { count: 0, amount: 0 },
                        payment: { count: 0, amount: 0 },
                        journal: { count: 0, amount: 0 }
                    };
                    
                    json.data.forEach((d, index) => {
                        // Update stats
                        if (totalStats[d.type]) {
                            totalStats[d.type].count++;
                            totalStats[d.type].amount += parseFloat(d.amount || 0);
                        }
                        
                        const tr = document.createElement('tr');
                        tr.className = 'voucher-row';
                        tr.dataset.voucher = encodeURIComponent(JSON.stringify(d));
                        tr.style.animationDelay = `${index * 50}ms`;
                        
                        const id = d.id;
                        const voucherNo = d.no || (d.id ? `VCH-${String(d.id).padStart(6, '0')}` : '');
                        
                        // Permission checks
                        let canEdit = isAdmin || (d.created_by && currentUserId && parseInt(d.created_by) === parseInt(currentUserId));
                        let canDelete = canEdit;
                        
                        // Generate voucher type badge
                        const typeInfo = {
                            'collection': { text: 'Tahsilat', class: 'voucher-type-collection', icon: 'mdi-cash-plus' },
                            'payment': { text: 'Tediye', class: 'voucher-type-payment', icon: 'mdi-cash-minus' },
                            'journal': { text: 'Mahsup', class: 'voucher-type-journal', icon: 'mdi-swap-horizontal' }
                        };
                        
                        const typeData = typeInfo[d.type] || { text: d.type, class: 'bg-secondary', icon: 'mdi-file' };
                        
                        // Generate action buttons
                        const viewBtn = `<button class="btn btn-sm btn-outline-info me-1 btn-view-voucher" data-id="${id}" title="Görüntüle">
                                            <i class="mdi mdi-eye"></i>
                                        </button>`;
                        const editBtn = canEdit ? `<button class="btn btn-sm btn-outline-primary me-1 btn-edit-voucher" data-id="${id}" title="Düzenle">
                                                    <i class="mdi mdi-pencil"></i>
                                                  </button>` : '';
                        const deleteBtn = canDelete ? `<button class="btn btn-sm btn-outline-danger btn-delete-voucher" data-id="${id}" title="Sil">
                                                        <i class="mdi mdi-delete"></i>
                                                      </button>` : '';
                        
                        // Format amount with proper styling
                        const amount = parseFloat(d.amount || 0);
                        const amountClass = d.type === 'collection' ? 'positive' : (d.type === 'payment' ? 'negative' : '');
                        const formattedAmount = `<span class="voucher-amount ${amountClass}">${formatCurrency(amount)}</span>`;
                        
                        tr.innerHTML = `
                            <td data-label="Seç">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input select-voucher" data-id="${id}" />
                                </div>
                            </td>
                            <td data-label="Tarih">
                                <span class="voucher-date">${fmtDate(d.date)}</span>
                            </td>
                            <td data-label="Fiş No">
                                <span class="voucher-no">${voucherNo}</span>
                            </td>
                            <td data-label="Tür">
                                <span class="voucher-type-badge ${typeData.class}">
                                    <i class="mdi ${typeData.icon} me-1"></i>${typeData.text}
                                </span>
                            </td>
                            <td data-label="Cari">
                                <span class="fw-semibold">${d.counterparty || '-'}</span>
                            </td>
                            <td data-label="Tutar">
                                ${formattedAmount}
                            </td>
                            <td data-label="Açıklama">
                                <span class="text-muted" title="${d.notes || ''}">${truncateText(d.notes || '', 50)}</span>
                            </td>
                            <td data-label="İşlemler">
                                <div class="voucher-actions">
                                    ${viewBtn}${editBtn}${deleteBtn}
                                </div>
                            </td>
                        `;
                        
                        tbody.appendChild(tr);
                    });
                    
                    // Attach event handlers
                    attachVoucherEventHandlers();
                    
                    // Update stats display
                    updateStatsDisplay(json.meta || { total: json.data.length });
                    
                    // Update pagination
                    updatePagination(json.meta);
                    
                    if (progress) progressBar.style.width = '100%';
                    
                    // Add entrance animation
                    tbody.querySelectorAll('.voucher-row').forEach((row, index) => {
                        setTimeout(() => {
                            row.style.opacity = '1';
                            row.style.transform = 'translateY(0)';
                        }, index * 50);
                    });
                    
                } else {
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger p-4">
                                        <i class="mdi mdi-alert-circle me-2"></i>Veri yüklenirken hata oluştu: ${json.message || 'Bilinmeyen hata'}
                                      </td></tr>`;
                }
                
                if (progress) {
                    setTimeout(() => {
                        progress.style.display = 'none';
                        progressBar.style.width = '0%';
                    }, 300);
                }
                
            } catch (e) {
                console.error('refreshList error:', e);
                const tbody = document.querySelector('#recent-vouchers-table tbody');
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger p-4">
                                    <i class="mdi mdi-wifi-off me-2"></i>Bağlantı hatası oluştu
                                  </td></tr>`;
            }
        }

        function generateLoadingSkeleton(rows) {
            let skeleton = '';
            for (let i = 0; i < rows; i++) {
                skeleton += `
                    <tr>
                        <td><div class="loading-skeleton" style="height:20px;width:20px;border-radius:4px;"></div></td>
                        <td><div class="loading-skeleton" style="height:16px;width:80px;border-radius:4px;"></div></td>
                        <td><div class="loading-skeleton" style="height:16px;width:100px;border-radius:4px;"></div></td>
                        <td><div class="loading-skeleton" style="height:24px;width:70px;border-radius:12px;"></div></td>
                        <td><div class="loading-skeleton" style="height:16px;width:120px;border-radius:4px;"></div></td>
                        <td><div class="loading-skeleton" style="height:16px;width:80px;border-radius:4px;"></div></td>
                        <td><div class="loading-skeleton" style="height:16px;width:150px;border-radius:4px;"></div></td>
                        <td><div class="loading-skeleton" style="height:30px;width:100px;border-radius:15px;"></div></td>
                    </tr>
                `;
            }
            return skeleton;
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('tr-TR', {
                style: 'currency',
                currency: 'TRY',
                minimumFractionDigits: 2
            }).format(amount);
        }

        function truncateText(text, length) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        }

        function onDeleteClick(e){
            const id = e.currentTarget.getAttribute('data-id');
            (async ()=>{
                if(!confirm('Bu fişi silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) return;
                try{
                    const res = await fetch(`{{ url('finance/accounts/vouchers') }}/${id}`, {method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}});
                    const j = await res.json();
                    if(j.ok){ alert('Kayıt silindi'); refreshList(currentPage); }
                    else alert('Silme hatası: ' + (j.message || 'Bilinmeyen hata'));
                }catch(er){ console.error(er); alert('Sunucu hatası: ' + er.message); }
            })();
        }

        // bulk delete handler (single binding)
        document.getElementById('btn-bulk-delete').addEventListener('click', async ()=>{
            const ids = Array.from(document.querySelectorAll('.select-voucher:checked')).map(cb=>cb.getAttribute('data-id'));
            if(!ids.length){ alert('Lütfen en az bir kayıt seçin.'); return; }
            if(!confirm('Seçili kayıtları silmek istediğinizden emin misiniz?')) return;
            try{
                const res = await fetch(`{{ route('finance.accounts.vouchers.bulk-delete') }}`, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json','Content-Type':'application/json'}, body: JSON.stringify({ids: ids})});
                const j = await res.json();
                if(j.ok){ alert('Kayıtlar silindi'); refreshList(currentPage); } else alert('Silme hatası: ' + (j.message||'Hata'));
            }catch(er){ console.error(er); alert('Sunucu hatası: ' + er.message); }
        });

        // Pagination buttons are handled by the enhanced functions above

        // Attach event handlers for voucher actions
        function attachVoucherEventHandlers() {
            // View buttons
            document.querySelectorAll('.btn-view-voucher').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.getAttribute('data-id');
                    const tr = e.currentTarget.closest('tr');
                    if (tr && tr.dataset.voucher) {
                        try {
                            const voucher = JSON.parse(decodeURIComponent(tr.dataset.voucher));
                            showVoucherDetails(voucher);
                        } catch (err) {
                            console.error(err);
                            showToast('Veri okunamadı', 'error');
                        }
                    }
                });
            });

            // Edit buttons
            document.querySelectorAll('.btn-edit-voucher').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.getAttribute('data-id');
                    const tr = e.currentTarget.closest('tr');
                    if (tr && tr.dataset.voucher) {
                        try {
                            const voucher = JSON.parse(decodeURIComponent(tr.dataset.voucher));
                            openModalForEdit(voucher);
                        } catch (err) {
                            console.error(err);
                            showToast('Veri okunamadı', 'error');
                        }
                    }
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete-voucher').forEach(btn => {
                btn.addEventListener('click', onDeleteClick);
            });

            // Select all checkbox
            const selectAll = document.getElementById('select-all-vouchers');
            selectAll.checked = false;
            selectAll.onchange = () => {
                document.querySelectorAll('.select-voucher').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            };
        }

        function updateStatsDisplay(meta) {
            // Update main stats
            const totalElement = document.getElementById('total-vouchers');
            const dashboardTotal = document.getElementById('dashboard-total-vouchers');
            const monthlyElement = document.getElementById('monthly-vouchers');
            const monthlyCount = document.getElementById('monthly-count');
            const lastUpdated = document.getElementById('last-updated');

            const total = meta.total || 0;
            if (totalElement) totalElement.textContent = total;
            if (dashboardTotal) dashboardTotal.textContent = total;
            if (monthlyElement) monthlyElement.textContent = totalStats.collection.count + totalStats.payment.count + totalStats.journal.count;
            if (monthlyCount) monthlyCount.textContent = totalStats.collection.count + totalStats.payment.count + totalStats.journal.count;

            // Update collection/payment totals
            const collectionTotal = document.getElementById('collection-total');
            const paymentTotal = document.getElementById('payment-total');
            
            if (collectionTotal) {
                collectionTotal.textContent = formatCurrency(totalStats.collection.amount);
            }
            if (paymentTotal) {
                paymentTotal.textContent = formatCurrency(totalStats.payment.amount);
            }

            // Update last updated
            if (lastUpdated) {
                lastUpdated.textContent = new Date().toLocaleString('tr-TR');
            }
        }

        function updatePagination(meta) {
            if (meta) {
                lastPage = meta.last_page || 1;
                document.getElementById('vouchers-prev').disabled = (meta.current_page <= 1);
                document.getElementById('vouchers-next').disabled = (meta.current_page >= meta.last_page);
                
                // Update pagination info
                const paginationInfo = document.getElementById('pagination-info');
                if (paginationInfo) {
                    const start = ((meta.current_page - 1) * meta.per_page) + 1;
                    const end = Math.min(meta.current_page * meta.per_page, meta.total);
                    paginationInfo.textContent = `${start}-${end} / ${meta.total} kayıt gösteriliyor`;
                }
            }
        }

        // Filter functionality
        document.querySelectorAll('.voucher-filter-btn[data-filter]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Update active filter
                document.querySelectorAll('.voucher-filter-btn[data-filter]').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                currentFilter = btn.getAttribute('data-filter');
                currentPage = 1; // Reset to first page
                refreshList(1);
            });
        });

        // Search functionality with debounce
        let searchTimeout;
        document.getElementById('voucher-search').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTerm = e.target.value.trim();
            
            searchTimeout = setTimeout(() => {
                currentPage = 1; // Reset to first page
                refreshList(1);
            }, 500); // 500ms debounce
        });

        // Enhanced form submission
        document.getElementById('submit-voucher').addEventListener('click', async () => {
            const submitBtn = document.getElementById('submit-voucher');
            const originalText = submitBtn.innerHTML;
            
            try {
                // Show loading state
                submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2"></div>Kaydediliyor...';
                submitBtn.disabled = true;
                
                const data = new FormData(form);
                
                // Handle journal entries
                if (document.getElementById('voucher-type').value === 'journal') {
                    const rows = [];
                    document.querySelectorAll('#journal-rows .jr-row').forEach(row => {
                        const account = row.querySelector('.jr-account').value;
                        const debit = parseFloat(row.querySelector('.jr-debit').value || 0);
                        const credit = parseFloat(row.querySelector('.jr-credit').value || 0);
                        
                        if (account || debit || credit) {
                            rows.push({ account, debit, credit });
                        }
                    });
                    
                    data.append('lines', JSON.stringify(rows));
                    
                    // Validate journal balance
                    const totals = updateJournalTotals();
                    if (!totals.balanced) {
                        throw new Error('Mahsup fişi için toplam Borç ve Alacak eşit olmalıdır.');
                    }
                    
                    data.append('amount', totals.totalDebit);
                }
                
                // Check for edit mode
                const editId = form.dataset.editId;
                let response;
                
                if (editId) {
                    response = await fetch(`{{ url('finance/accounts/vouchers') }}/${editId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: data
                    });
                } else {
                    response = await fetch("{{ route('finance.accounts.vouchers.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: data
                    });
                }
                
                const result = await response.json();
                
                if (result.ok) {
                    modalEl.hide();
                    form.reset();
                    delete form.dataset.editId;
                    
                    showToast(editId ? 'Fiş başarıyla güncellendi!' : 'Fiş başarıyla kaydedildi!', 'success');
                    refreshList(currentPage);
                } else {
                    throw new Error(result.message || 'Kaydetme işlemi başarısız');
                }
                
            } catch (error) {
                console.error('Submit error:', error);
                showToast(error.message, 'error');
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Enhanced modal for editing
        function openModalForEdit(voucher) {
            // Fill form with voucher data
            document.getElementById('voucher-type').value = voucher.type;
            document.getElementById('voucher-date').value = voucher.date ? voucher.date.split('T')[0] : '';
            document.getElementById('voucher-no').value = voucher.no || '';
            document.getElementById('voucher-counterparty').value = voucher.counterparty || '';
            document.getElementById('voucher-counterparty-id').value = voucher.counterparty_id || '';
            document.getElementById('voucher-amount').value = voucher.amount || '';
            document.getElementById('voucher-payment-method').value = voucher.payment_method || '';
            document.getElementById('voucher-notes').value = voucher.notes || '';
            
            // Update modal title
            const titles = {
                'collection': '<i class="mdi mdi-cash-plus me-2"></i>Tahsilat Fişi Düzenle',
                'payment': '<i class="mdi mdi-cash-minus me-2"></i>Tediye Fişi Düzenle',
                'journal': '<i class="mdi mdi-swap-horizontal me-2"></i>Mahsup Fişi Düzenle'
            };
            
            document.getElementById('voucherModalTitle').innerHTML = titles[voucher.type] || 'Fiş Düzenle';
            document.getElementById('voucherModalSubtitle').textContent = 'Mevcut kayıt düzenleniyor';
            document.getElementById('submit-voucher-text').textContent = 'Değişiklikleri Kaydet';
            
            // Handle journal lines
            const journalLines = document.getElementById('journal-lines');
            const journalRows = document.getElementById('journal-rows');
            
            if (voucher.type === 'journal') {
                journalLines.style.display = 'block';
                journalRows.innerHTML = '';
                
                if (voucher.lines && Array.isArray(voucher.lines) && voucher.lines.length > 0) {
                    voucher.lines.forEach(line => {
                        addJournalRow();
                        const lastRow = journalRows.children[journalRows.children.length - 1];
                        lastRow.querySelector('.jr-account').value = line.account || '';
                        lastRow.querySelector('.jr-debit').value = line.debit || '';
                        lastRow.querySelector('.jr-credit').value = line.credit || '';
                    });
                } else {
                    addJournalRow();
                    addJournalRow();
                }
                
                updateJournalTotals();
            } else {
                journalLines.style.display = 'none';
            }
            
            // Set edit mode
            form.dataset.editId = voucher.id;
            
            // Handle permissions
            const voucherNoInput = document.getElementById('voucher-no');
            if (!isAdmin) {
                voucherNoInput.setAttribute('readonly', 'readonly');
            } else {
                voucherNoInput.removeAttribute('readonly');
            }
            
            modalEl.show();
        }

        // Enhanced voucher details modal
        function showVoucherDetails(voucher) {
            const detailsModal = document.createElement('div');
            detailsModal.className = 'modal fade';
            detailsModal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-receipt me-2"></i>Fiş Detayları
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Temel Bilgiler</h6>
                                    <table class="table table-sm">
                                        <tr><td><strong>Fiş No:</strong></td><td>${voucher.no || 'Otomatik'}</td></tr>
                                        <tr><td><strong>Tarih:</strong></td><td>${fmtDate(voucher.date)}</td></tr>
                                        <tr><td><strong>Tür:</strong></td><td>${getVoucherTypeText(voucher.type)}</td></tr>
                                        <tr><td><strong>Tutar:</strong></td><td class="fw-bold">${formatCurrency(voucher.amount || 0)}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Diğer Bilgiler</h6>
                                    <table class="table table-sm">
                                        <tr><td><strong>Cari Hesap:</strong></td><td>${voucher.counterparty || '-'}</td></tr>
                                        <tr><td><strong>Ödeme Yöntemi:</strong></td><td>${getPaymentMethodText(voucher.payment_method)}</td></tr>
                                        <tr><td><strong>Oluşturan:</strong></td><td>${voucher.created_by || '-'}</td></tr>
                                        <tr><td><strong>Oluşturma:</strong></td><td>${fmtDate(voucher.created_at)}</td></tr>
                                    </table>
                                </div>
                            </div>
                            ${voucher.notes ? `
                                <div class="mt-3">
                                    <h6 class="text-primary">Açıklama</h6>
                                    <div class="border rounded p-3 bg-light">${voucher.notes}</div>
                                </div>
                            ` : ''}
                            ${voucher.type === 'journal' && voucher.lines ? `
                                <div class="mt-3">
                                    <h6 class="text-primary">Mahsup Satırları</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-dark">
                                                <tr><th>Hesap</th><th>Borç</th><th>Alacak</th></tr>
                                            </thead>
                                            <tbody>
                                                ${voucher.lines.map(line => `
                                                    <tr>
                                                        <td>${line.account || ''}</td>
                                                        <td class="text-success">${line.debit ? formatCurrency(line.debit) : '-'}</td>
                                                        <td class="text-danger">${line.credit ? formatCurrency(line.credit) : '-'}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(detailsModal);
            const modal = new bootstrap.Modal(detailsModal);
            modal.show();
            
            detailsModal.addEventListener('hidden.bs.modal', () => {
                detailsModal.remove();
            });
        }

        function getVoucherTypeText(type) {
            const types = {
                'collection': 'Tahsilat',
                'payment': 'Tediye', 
                'journal': 'Mahsup'
            };
            return types[type] || type;
        }

        function getPaymentMethodText(method) {
            const methods = {
                'cash': 'Nakit',
                'bank_transfer': 'Banka Havalesi/EFT',
                'pos': 'Kredi Kartı / POS',
                'check': 'Çek',
                'credit_note': 'Kredi Notu'
            };
            return methods[method] || method || '-';
        }

        // Enhanced toast notification system
        function showToast(message, type = 'info', duration = 3000) {
            // Remove existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.className = `custom-toast alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} position-fixed shadow-lg`;
            toast.style.cssText = `
                top: 20px; right: 20px; z-index: 9999; min-width: 300px; 
                animation: slideInRight 0.3s ease;
            `;
            
            const icons = {
                'success': 'mdi-check-circle',
                'error': 'mdi-alert-circle',
                'info': 'mdi-information',
                'warning': 'mdi-alert'
            };
            
            toast.innerHTML = `
                <i class="mdi ${icons[type] || icons.info} me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .voucher-row {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);

        // Quick action handlers
        document.getElementById('btn-export-excel')?.addEventListener('click', () => {
            showToast('Excel dışa aktarma özelliği yakında eklenecek', 'info');
        });

        document.getElementById('btn-print-report')?.addEventListener('click', () => {
            window.print();
        });

        document.getElementById('btn-backup')?.addEventListener('click', () => {
            showToast('Yedekleme işlemi başlatıldı', 'info');
        });

        // Initialize
        refreshList();
        
        // Auto-refresh every 5 minutes
        setInterval(() => {
            refreshList(currentPage);
        }, 300000);

    })();
</script>
@endsection
