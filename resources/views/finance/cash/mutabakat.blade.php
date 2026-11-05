@extends('layouts.master')
@section('title', 'Banka Mutabakatı')
@section('topbar-title', 'Finans - Kasa & Banka')

@section('styles')
<style>
.step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2rem;
}
.step {
    display: flex;
    align-items: center;
    flex: 1;
    position: relative;
}
.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 20px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}
.step.active:not(:last-child)::after {
    background: #007bff;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    color: #6c757d;
    font-weight: bold;
    z-index: 2;
    position: relative;
}
.step.active .step-circle {
    background: #007bff;
    color: white;
}
.step.completed .step-circle {
    background: #28a745;
    color: white;
}
.step-text {
    margin-left: 10px;
    font-weight: 500;
}
.step.active .step-text {
    color: #007bff;
}
.balance-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}
.metric-card {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.1);
}
.metric-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
}
.metric-label {
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
}
.upload-area.dragover {
    border-color: #007bff;
    background: #e3f2fd;
}
.transaction-row.matched {
    background-color: #d4edda !important;
    border-left: 4px solid #28a745;
}
.transaction-row.unmatched {
    background-color: #f8d7da !important;
    border-left: 4px solid #dc3545;
}
.progress-bar-striped {
    background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);
    background-size: 1rem 1rem;
}
.animate-progress {
    animation: progress-bar-stripes 1s linear infinite;
}
@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-balance-scale fa-2x"></i>
                                </div>
                            </div>
                            <div>
                                <h2 class="mb-1">Banka Mutabakatı</h2>
                                <p class="text-muted mb-0">Banka ekstresi ile muhasebe kayıtlarınızı otomatik olarak eşleştirin ve mutabakat işlemlerini kolayca yönetin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" id="step1">
                <div class="step-circle">1</div>
                <div class="step-text">Hesap & Dosya Seçimi</div>
            </div>
            <div class="step" id="step2">
                <div class="step-circle">2</div>
                <div class="step-text">Otomatik Eşleştirme</div>
            </div>
            <div class="step" id="step3">
                <div class="step-circle">3</div>
                <div class="step-text">Manuel Kontrol</div>
            </div>
            <div class="step" id="step4">
                <div class="step-circle">4</div>
                <div class="step-text">Mutabakat Tamamlama</div>
            </div>
        </div>

        <!-- Upload Form Section -->
        <div class="card shadow-sm mb-4" id="uploadSection">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-upload me-2"></i>
                    Adım 1: Mutabakat Başlatma
                </h5>
            </div>
            <div class="card-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-university me-1"></i>
                                    Banka Hesabı
                                </label>
                                <select name="account_id" class="form-select" required>
                                    <option value="">-- Hesap Seçiniz --</option>
                                    @if(\Illuminate\Support\Facades\Schema::hasTable('accounts'))
                                        @foreach(\App\Models\Account::orderBy('name')->get() as $acc)
                                            <option value="{{ $acc->id }}" data-balance="{{ $acc->balance ?? 0 }}">
                                                {{ $acc->bank_name ?? $acc->name }} - {{ $acc->account_no }}
                                                <small>(Bakiye: {{ number_format($acc->balance ?? 0, 2) }} ₺)</small>
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>
                                    Ekstre Bitiş Tarihi
                                </label>
                                <input type="date" name="end_date" class="form-control" required />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-coins me-1"></i>
                                    Ekstre Bitiş Bakiyesi (₺)
                                </label>
                                <input type="number" step="0.01" name="closing_balance" class="form-control" placeholder="0.00" required />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-file-excel me-1"></i>
                            Banka Ekstresi
                        </label>
                        <div class="upload-area" id="uploadArea">
                            <input type="file" name="statement" class="form-control" accept=".csv,.xlsx,.xls" required id="fileInput" style="display: none;" />
                            <div class="upload-content">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                <h5>Dosya Yükle</h5>
                                <p class="text-muted mb-2">CSV veya Excel dosyanızı buraya sürükleyip bırakın veya <strong>tıklayarak seçin</strong></p>
                                <small class="text-muted">Desteklenen formatlar: .csv, .xlsx, .xls (Maksimum 10MB)</small>
                            </div>
                        </div>
                        <div id="fileInfo" style="display: none;" class="mt-2">
                            <div class="alert alert-info">
                                <i class="fas fa-file me-2"></i>
                                <span id="fileName"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="removeFile">
                                    <i class="fas fa-times"></i> Kaldır
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary btn-lg px-5" id="uploadBtn" type="submit">
                            <i class="fas fa-rocket me-2"></i>
                            Mutabakatı Başlat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="card shadow-sm mb-4" id="progressSection" style="display:none">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        İşlem Durumu
                    </h5>
                    <div class="spinner-border spinner-border-sm text-primary" role="status" id="loadingSpinner">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
                <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped animate-progress" id="progressBar" style="width: 0%"></div>
                </div>
                <div class="text-center">
                    <small class="text-muted" id="progressText">Dosya yükleniyor...</small>
                </div>
            </div>
        </div>

        <!-- Work Area -->
        <div id="workArea" style="display:none">
            <!-- Balance Overview -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-label">Ekstre Bitiş Bakiyesi</div>
                            <div class="metric-value" id="extClosing">0.00 ₺</div>
                            <i class="fas fa-university text-primary fa-2x mt-2"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-label">Eşleşen İşlemler</div>
                            <div class="metric-value text-success" id="matchedTotal">0.00 ₺</div>
                            <i class="fas fa-check-circle text-success fa-2x mt-2"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card metric-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-label">Muhasebe Bakiyesi</div>
                            <div class="metric-value text-info" id="acctTotal">0.00 ₺</div>
                            <i class="fas fa-calculator text-info fa-2x mt-2"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card balance-card h-100">
                        <div class="card-body text-center">
                            <div class="metric-label text-white-50">MUTABAKAT FARKI</div>
                            <div class="metric-value text-white" id="diff">0.00 ₺</div>
                            <i class="fas fa-balance-scale text-white fa-2x mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matching Progress -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>
                                Eşleştirme İlerlemesi
                            </h5>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark" id="matchStats">0/0 Eşleşti</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="progress mb-2" style="height: 12px;">
                        <div class="progress-bar bg-success" id="matchProgress" style="width: 0%"></div>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-success fw-bold" id="matchedCount">0</div>
                            <small class="text-muted">Eşleşen</small>
                        </div>
                        <div class="col-4">
                            <div class="text-warning fw-bold" id="pendingCount">0</div>
                            <small class="text-muted">Bekleyen</small>
                        </div>
                        <div class="col-4">
                            <div class="text-danger fw-bold" id="unmatchedCount">0</div>
                            <small class="text-muted">Eşleşmeyen</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                Banka Ekstresi Hareketleri
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px;">
                                <table class="table table-hover mb-0" id="bankTable">
                                    <thead class="table-primary sticky-top">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllBank" class="form-check-input">
                                            </th>
                                            <th>Tarih</th>
                                            <th>Açıklama</th>
                                            <th>Tutar</th>
                                            <th>Durum</th>
                                            <th>İşlem</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-book me-2"></i>
                                Muhasebe Kayıtları
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px;">
                                <table class="table table-hover mb-0" id="acctTable">
                                    <thead class="table-info sticky-top">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllAcct" class="form-check-input">
                                            </th>
                                            <th>Tarih</th>
                                            <th>Açıklama</th>
                                            <th>Tutar</th>
                                            <th>Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="btn-group me-2" role="group">
                                <button class="btn btn-outline-primary" id="autoMatchBtn">
                                    <i class="fas fa-magic me-2"></i>
                                    Otomatik Eşleştir
                                </button>
                                <button class="btn btn-outline-info" id="manualMatchBtn">
                                    <i class="fas fa-hand-pointer me-2"></i>
                                    Manuel Eşleştir
                                </button>
                            </div>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-warning" id="unmatchBtn">
                                    <i class="fas fa-unlink me-2"></i>
                                    Eşleştirmeyi Kaldır
                                </button>
                                <button class="btn btn-outline-secondary" id="refreshBtn">
                                    <i class="fas fa-sync me-2"></i>
                                    Yenile
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-success btn-lg px-4" id="completeBtn" disabled>
                                <i class="fas fa-check-double me-2"></i>
                                Mutabakatı Tamamla
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Report -->
            <div class="card shadow-sm mt-4" id="summarySection" style="display:none">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Mutabakat Özeti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="summaryChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Toplam Banka İşlemi:</strong></td>
                                        <td class="text-end" id="totalBankTransactions">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Eşleşen İşlemler:</strong></td>
                                        <td class="text-end text-success" id="totalMatched">0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Eşleşmeyen İşlemler:</strong></td>
                                        <td class="text-end text-danger" id="totalUnmatched">0</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Mutabakat Başarı Oranı:</strong></td>
                                        <td class="text-end fw-bold" id="successRate">0%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentStep = 1;
let uploadedData = null;
let matchedTransactions = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeUploadArea();
    initializeEventListeners();
});

function initializeUploadArea() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const removeFileBtn = document.getElementById('removeFile');

    // Click to select file
    uploadArea.addEventListener('click', () => fileInput.click());

    // Drag and drop handlers
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showFileInfo(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            showFileInfo(e.target.files[0]);
        }
    });

    // Remove file
    removeFileBtn.addEventListener('click', () => {
        fileInput.value = '';
        fileInfo.style.display = 'none';
        uploadArea.style.display = 'block';
    });

    function showFileInfo(file) {
        fileName.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        fileInfo.style.display = 'block';
        uploadArea.style.display = 'none';
    }
}

function initializeEventListeners() {
    // Form submission
    document.getElementById('uploadForm').addEventListener('submit', handleFormSubmit);
    
    // Action buttons
    document.getElementById('autoMatchBtn')?.addEventListener('click', performAutoMatch);
    document.getElementById('manualMatchBtn')?.addEventListener('click', performManualMatch);
    document.getElementById('unmatchBtn')?.addEventListener('click', performUnmatch);
    document.getElementById('refreshBtn')?.addEventListener('click', refreshData);
    document.getElementById('completeBtn')?.addEventListener('click', completeReconciliation);
    
    // Select all checkboxes
    document.getElementById('selectAllBank')?.addEventListener('change', toggleSelectAllBank);
    document.getElementById('selectAllAcct')?.addEventListener('change', toggleSelectAllAcct);
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const uploadBtn = document.getElementById('uploadBtn');
    const progressSection = document.getElementById('progressSection');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    // Validate form
    if (!formData.get('account_id') || !formData.get('end_date') || !formData.get('closing_balance') || !formData.get('statement')) {
        showAlert('Lütfen tüm alanları doldurun.', 'warning');
        return;
    }
    
    // Update UI
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Yükleniyor...';
    progressSection.style.display = 'block';
    updateStep(2);
    
    // Simulate upload progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressBar.style.width = progress + '%';
        
        if (progress < 30) progressText.textContent = 'Dosya yükleniyor...';
        else if (progress < 60) progressText.textContent = 'Veriler işleniyor...';
        else if (progress < 90) progressText.textContent = 'Otomatik eşleştirme yapılıyor...';
    }, 200);
    
    // Simulate API call (since endpoints don't exist yet)
    setTimeout(() => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        progressText.textContent = 'Tamamlandı!';
        
        // Simulate successful response
        const simulatedData = generateSimulatedData();
        handleUploadSuccess(simulatedData);
        
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-rocket me-2"></i>Mutabakatı Başlat';
        
        setTimeout(() => {
            progressSection.style.display = 'none';
        }, 1000);
    }, 3000);
    
    /* Real API call would be:
    fetch("{{ route('finance.cash.mutabakat.upload') }}", { 
        method: 'POST', 
        body: formData, 
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
    })
    .then(r => r.json())
    .then(data => {
        clearInterval(progressInterval);
        if (!data.ok) {
            showAlert(data.message || 'Hata oluştu', 'error');
            return;
        }
        handleUploadSuccess(data);
    })
    .catch(err => {
        clearInterval(progressInterval);
        showAlert('Yükleme hatası', 'error');
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-rocket me-2"></i>Mutabakatı Başlat';
        progressSection.style.display = 'none';
    });
    */
}

function generateSimulatedData() {
    const transactions = [
        { date: '2024-10-01', description: 'Maaş Ödemesi', amount: -15000, type: 'expense' },
        { date: '2024-10-02', description: 'Müşteri Ödemesi - ABC Şirketi', amount: 25000, type: 'income' },
        { date: '2024-10-03', description: 'Elektrik Faturası', amount: -1200, type: 'expense' },
        { date: '2024-10-04', description: 'Satış Tahsilatı', amount: 8500, type: 'income' },
        { date: '2024-10-05', description: 'Ofis Kirası', amount: -5000, type: 'expense' },
        { date: '2024-10-06', description: 'Ürün Satışı', amount: 12000, type: 'income' },
        { date: '2024-10-07', description: 'Telefon Faturası', amount: -350, type: 'expense' },
        { date: '2024-10-08', description: 'Danışmanlık Ücreti', amount: 7500, type: 'income' }
    ];
    
    return {
        ok: true,
        closingBalance: 31450,
        transactions: transactions,
        accountingRecords: transactions.slice(0, 6), // Simulate partial matching
        message: 'Başarıyla yüklendi'
    };
}

function handleUploadSuccess(data) {
    uploadedData = data;
    
    // Update balance displays
    document.getElementById('extClosing').textContent = formatCurrency(data.closingBalance);
    
    // Show work area
    document.getElementById('workArea').style.display = 'block';
    updateStep(3);
    
    // Populate tables
    populateBankTable(data.transactions);
    populateAccountingTable(data.accountingRecords || []);
    
    // Perform initial auto-matching
    performAutoMatch();
    
    showAlert('Dosya başarıyla yüklendi ve otomatik eşleştirme tamamlandı!', 'success');
}

function populateBankTable(transactions) {
    const tbody = document.querySelector('#bankTable tbody');
    tbody.innerHTML = '';
    
    transactions.forEach((transaction, index) => {
        const row = document.createElement('tr');
        row.className = 'transaction-row';
        row.dataset.index = index;
        
        const statusBadge = `<span class="badge bg-secondary status-badge">Bekliyor</span>`;
        const actionBtn = `<button class="btn btn-sm btn-outline-primary createVoucher" data-idx="${index}">
            <i class="fas fa-plus me-1"></i>Kayıt Oluştur
        </button>`;
        
        row.innerHTML = `
            <td><input type="checkbox" data-idx="${index}" class="form-check-input bankChk" /></td>
            <td>${transaction.date}</td>
            <td>${transaction.description}</td>
            <td class="text-end fw-bold ${transaction.amount >= 0 ? 'text-success' : 'text-danger'}">
                ${formatCurrency(Math.abs(transaction.amount))}
                ${transaction.amount >= 0 ? '<i class="fas fa-arrow-up ms-1"></i>' : '<i class="fas fa-arrow-down ms-1"></i>'}
            </td>
            <td>${statusBadge}</td>
            <td>${actionBtn}</td>
        `;
        
        tbody.appendChild(row);
    });
}

function populateAccountingTable(records) {
    const tbody = document.querySelector('#acctTable tbody');
    tbody.innerHTML = '';
    
    records.forEach((record, index) => {
        const row = document.createElement('tr');
        row.className = 'transaction-row';
        row.dataset.index = index;
        
        const statusBadge = `<span class="badge bg-secondary status-badge">Bekliyor</span>`;
        
        row.innerHTML = `
            <td><input type="checkbox" data-idx="${index}" class="form-check-input acctChk" /></td>
            <td>${record.date}</td>
            <td>${record.description}</td>
            <td class="text-end fw-bold ${record.amount >= 0 ? 'text-success' : 'text-danger'}">
                ${formatCurrency(Math.abs(record.amount))}
                ${record.amount >= 0 ? '<i class="fas fa-arrow-up ms-1"></i>' : '<i class="fas fa-arrow-down ms-1"></i>'}
            </td>
            <td>${statusBadge}</td>
        `;
        
        tbody.appendChild(row);
    });
}

function performAutoMatch() {
    if (!uploadedData) return;
    
    let matchedCount = 0;
    let matchedTotal = 0;
    
    // Simple matching logic - match by amount and date
    uploadedData.transactions.forEach((bankTrans, bankIndex) => {
        const matchingRecord = uploadedData.accountingRecords?.find((accRecord, accIndex) => {
            return Math.abs(bankTrans.amount) === Math.abs(accRecord.amount) && 
                   bankTrans.date === accRecord.date &&
                   !matchedTransactions.some(m => m.accIndex === accIndex);
        });
        
        if (matchingRecord) {
            const accIndex = uploadedData.accountingRecords.indexOf(matchingRecord);
            matchedTransactions.push({ bankIndex, accIndex, amount: bankTrans.amount });
            
            // Update UI
            markAsMatched(bankIndex, accIndex);
            matchedCount++;
            matchedTotal += Math.abs(bankTrans.amount);
        }
    });
    
    updateMatchingStats();
    updateBalanceDisplays();
    
    if (matchedCount > 0) {
        showAlert(`${matchedCount} işlem otomatik olarak eşleştirildi!`, 'success');
    }
}

function markAsMatched(bankIndex, accIndex) {
    // Update bank table row
    const bankRow = document.querySelector(`#bankTable [data-index="${bankIndex}"]`);
    if (bankRow) {
        bankRow.classList.add('matched');
        bankRow.classList.remove('unmatched');
        const checkbox = bankRow.querySelector('.bankChk');
        const statusBadge = bankRow.querySelector('.status-badge');
        if (checkbox) {
            checkbox.checked = true;
            checkbox.disabled = true;
        }
        if (statusBadge) {
            statusBadge.className = 'badge bg-success status-badge';
            statusBadge.textContent = 'Eşleşti';
        }
    }
    
    // Update accounting table row
    const accRow = document.querySelector(`#acctTable [data-index="${accIndex}"]`);
    if (accRow) {
        accRow.classList.add('matched');
        accRow.classList.remove('unmatched');
        const checkbox = accRow.querySelector('.acctChk');
        const statusBadge = accRow.querySelector('.status-badge');
        if (checkbox) {
            checkbox.checked = true;
            checkbox.disabled = true;
        }
        if (statusBadge) {
            statusBadge.className = 'badge bg-success status-badge';
            statusBadge.textContent = 'Eşleşti';
        }
    }
}

function updateMatchingStats() {
    const totalTransactions = uploadedData?.transactions?.length || 0;
    const matchedCount = matchedTransactions.length;
    const unmatchedCount = totalTransactions - matchedCount;
    const pendingCount = 0; // For future use
    
    document.getElementById('matchedCount').textContent = matchedCount;
    document.getElementById('unmatchedCount').textContent = unmatchedCount;
    document.getElementById('pendingCount').textContent = pendingCount;
    document.getElementById('matchStats').textContent = `${matchedCount}/${totalTransactions} Eşleşti`;
    
    // Update progress bar
    const progressPercent = totalTransactions > 0 ? (matchedCount / totalTransactions) * 100 : 0;
    document.getElementById('matchProgress').style.width = progressPercent + '%';
    
    // Enable complete button if all matched
    const completeBtn = document.getElementById('completeBtn');
    if (unmatchedCount === 0 && matchedCount > 0) {
        completeBtn.disabled = false;
        updateStep(4);
    }
}

function updateBalanceDisplays() {
    const matchedTotal = matchedTransactions.reduce((sum, match) => sum + Math.abs(match.amount), 0);
    const closingBalance = uploadedData?.closingBalance || 0;
    const difference = closingBalance - matchedTotal;
    
    document.getElementById('matchedTotal').textContent = formatCurrency(matchedTotal);
    document.getElementById('acctTotal').textContent = formatCurrency(matchedTotal); // Simplified
    document.getElementById('diff').textContent = formatCurrency(Math.abs(difference));
    
    // Color code the difference
    const diffElement = document.getElementById('diff');
    diffElement.className = difference === 0 ? 'metric-value text-success' : 'metric-value text-danger';
}

function updateStep(step) {
    // Remove active/completed classes from all steps
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active', 'completed'));
    
    // Mark previous steps as completed
    for (let i = 1; i < step; i++) {
        document.getElementById(`step${i}`)?.classList.add('completed');
    }
    
    // Mark current step as active
    document.getElementById(`step${step}`)?.classList.add('active');
    currentStep = step;
}

function completeReconciliation() {
    updateStep(4);
    document.getElementById('summarySection').style.display = 'block';
    generateSummaryChart();
    updateSummaryStats();
    
    showAlert('Mutabakat başarıyla tamamlandı!', 'success');
}

function generateSummaryChart() {
    const ctx = document.getElementById('summaryChart').getContext('2d');
    const totalTransactions = uploadedData?.transactions?.length || 0;
    const matchedCount = matchedTransactions.length;
    const unmatchedCount = totalTransactions - matchedCount;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Eşleşen', 'Eşleşmeyen'],
            datasets: [{
                data: [matchedCount, unmatchedCount],
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 2,
                borderColor: '#fff'
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

function updateSummaryStats() {
    const totalTransactions = uploadedData?.transactions?.length || 0;
    const matchedCount = matchedTransactions.length;
    const unmatchedCount = totalTransactions - matchedCount;
    const successRate = totalTransactions > 0 ? ((matchedCount / totalTransactions) * 100).toFixed(1) : 0;
    
    document.getElementById('totalBankTransactions').textContent = totalTransactions;
    document.getElementById('totalMatched').textContent = matchedCount;
    document.getElementById('totalUnmatched').textContent = unmatchedCount;
    document.getElementById('successRate').textContent = successRate + '%';
}

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY',
        minimumFractionDigits: 2
    }).format(amount);
}

function showAlert(message, type) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Placeholder functions for future implementation
function performManualMatch() {
    showAlert('Manuel eşleştirme özelliği yakında eklenecek.', 'info');
}

function performUnmatch() {
    showAlert('Eşleştirme kaldırma özelliği yakında eklenecek.', 'info');
}

function refreshData() {
    showAlert('Veriler yenileniyor...', 'info');
    // Refresh logic here
}

function toggleSelectAllBank(e) {
    document.querySelectorAll('.bankChk:not(:disabled)').forEach(chk => {
        chk.checked = e.target.checked;
    });
}

function toggleSelectAllAcct(e) {
    document.querySelectorAll('.acctChk:not(:disabled)').forEach(chk => {
        chk.checked = e.target.checked;
    });
}

// Delegate event handlers for dynamic content
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('createVoucher')) {
        const idx = e.target.dataset.idx;
        const transaction = uploadedData.transactions[idx];
        
        if (confirm('Bu banka hareketi için otomatik muhasebe kaydı oluşturulsun mu?')) {
            e.target.disabled = true;
            e.target.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Oluşturuluyor...';
            
            // Simulate voucher creation
            setTimeout(() => {
                markAsMatched(idx, -1); // -1 indicates created voucher
                updateMatchingStats();
                updateBalanceDisplays();
                showAlert('Muhasebe kaydı başarıyla oluşturuldu!', 'success');
                
                e.target.disabled = false;
                e.target.innerHTML = '<i class="fas fa-check me-1"></i>Oluşturuldu';
                e.target.className = 'btn btn-sm btn-success';
            }, 1500);
        }
    }
});
</script>
@endsection
