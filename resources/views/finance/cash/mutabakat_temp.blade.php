@extends('layouts.master')
@section('title', 'Banka MutabakatÄ±')
@section('topbar-title', 'Finans - Kasa & Banka')

@section('styles')
<style>
/* Modern color palette */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    --danger-gradient: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    --info-gradient: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    --shadow-primary: 0 8px 25px rgba(102, 126, 234, 0.15);
    --shadow-success: 0 8px 25px rgba(40, 167, 69, 0.15);
    --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.08);
    --border-radius-lg: 12px;
    --border-radius-xl: 16px;
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced step indicator */
.step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2.5rem;
    padding: 0 1rem;
}
.step {
    display: flex;
    align-items: center;
    flex: 1;
    position: relative;
    transition: var(--transition-smooth);
}
.step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 22px;
    right: -50%;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #e9ecef 0%, #dee2e6 100%);
    border-radius: 2px;
    z-index: 1;
    transition: var(--transition-smooth);
}
.step.active:not(:last-child)::after {
    background: var(--primary-gradient);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}
.step.completed:not(:last-child)::after {
    background: var(--success-gradient);
    box-shadow: var(--shadow-success);
}
.step-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border: 3px solid #e9ecef;
    color: #6c757d;
    font-weight: 600;
    z-index: 2;
    position: relative;
    transition: var(--transition-smooth);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.step.active .step-circle {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    transform: scale(1.1);
    box-shadow: var(--shadow-primary);
}
.step.completed .step-circle {
    background: var(--success-gradient);
    border-color: transparent;
    color: white;
    transform: scale(1.05);
    box-shadow: var(--shadow-success);
}
.step-text {
    margin-left: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition-smooth);
}
.step.active .step-text {
    color: #667eea;
    transform: translateX(3px);
}
.step.completed .step-text {
    color: #28a745;
}

/* Enhanced balance and metric cards */
.balance-card {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: var(--border-radius-lg);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-primary);
}
.balance-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 50%);
    pointer-events: none;
}
.metric-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: none;
    border-radius: var(--border-radius-lg);
    border-left: 4px solid transparent;
    border-image: var(--primary-gradient) 1;
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-card);
}
.metric-card::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--primary-gradient);
}
.metric-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}
.metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.metric-label {
    color: #6c757d;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}
.upload-area {
    border: 3px dashed #dee2e6;
    border-radius: var(--border-radius-lg);
    padding: 3rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    transition: var(--transition-smooth);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.upload-area::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    transition: var(--transition-smooth);
}
.upload-area:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
}
.upload-area:hover::before {
    width: 300px;
    height: 300px;
}
.upload-area.dragover {
    border-color: #667eea;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    transform: scale(1.02);
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
}
.transaction-row {
    transition: var(--transition-smooth);
    border-radius: 4px;
    margin-bottom: 2px;
}
.transaction-row.matched {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.05) 100%) !important;
    border-left: 4px solid transparent;
    border-image: var(--success-gradient) 1;
    position: relative;
}
.transaction-row.matched::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--success-gradient);
    border-radius: 0 2px 2px 0;
}
.transaction-row.unmatched {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(232, 62, 140, 0.05) 100%) !important;
    border-left: 4px solid transparent;
    position: relative;
}
.transaction-row.unmatched::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--danger-gradient);
    border-radius: 0 2px 2px 0;
}
.progress-bar-striped {
    background-image: linear-gradient(45deg, rgba(255,255,255,.2) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.2) 50%, rgba(255,255,255,.2) 75%, transparent 75%, transparent);
    background-size: 1.5rem 1.5rem;
}
.animate-progress {
    animation: progress-bar-stripes 2s linear infinite;
}
@keyframes progress-bar-stripes {
    0% { background-position: 1.5rem 0; }
    100% { background-position: 0 0; }
}
.status-badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.table-hover tbody tr {
    transition: var(--transition-smooth);
}
.table-hover tbody tr:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.05) 100%);
    transform: translateX(3px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Enhanced card styles */
.card {
    border: none;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-card);
    transition: var(--transition-smooth);
    overflow: hidden;
}
.card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}
.card-header {
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0 !important;
    border-bottom: none;
    padding: 1.25rem 1.5rem;
    position: relative;
}
.card-header.bg-primary {
    background: var(--primary-gradient) !important;
    color: white;
}
.card-header.bg-success {
    background: var(--success-gradient) !important;
    color: white;
}
.card-header.bg-info {
    background: var(--info-gradient) !important;
    color: white;
}

/* Button enhancements */
.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.6rem 1.2rem;
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
}
.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: var(--transition-smooth);
}
.btn:hover::before {
    width: 300px;
    height: 300px;
}
.btn-primary {
    background: var(--primary-gradient);
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}
.btn-lg {
    padding: 0.8rem 2rem;
    font-size: 1.1rem;
    border-radius: 10px;
}

/* Form enhancements */
.form-control, .form-select {
    border-radius: 8px !important;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: var(--transition-smooth);
}
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

/* Table enhancements */
.table {
    border-radius: var(--border-radius-lg);
    overflow: hidden;
}
.table thead th {
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    padding: 1rem 0.75rem;
}
.table tbody td {
    border-color: #f1f3f4;
    padding: 0.9rem 0.75rem;
    vertical-align: middle;
}

/* Loading and animation effects */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
.loading {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .step-indicator {
        flex-direction: column;
        gap: 1rem;
    }
    .step:not(:last-child)::after {
        display: none;
    }
    .metric-card:hover {
        transform: none;
    }
    .upload-area {
        padding: 2rem 1rem;
    }
}
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-lg" style="background: var(--primary-gradient); color: white; position: relative; overflow: hidden;">
                    <div class="card-body py-4">
                        <div class="position-absolute" style="top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 1;"></div>
                        <div class="position-absolute" style="bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; z-index: 1;"></div>
                        <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                            <div class="me-4">
                                <div class="bg-white bg-opacity-20 text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 80px; height: 80px; backdrop-filter: blur(10px);">
                                    <i class="fas fa-balance-scale fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h1 class="mb-2 fw-bold" style="font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Banka MutabakatÄ±</h1>
                                <p class="mb-3 opacity-90" style="font-size: 1.1rem; line-height: 1.6;">Banka ekstresi ile muhasebe kayÄ±tlarÄ±nÄ±zÄ± otomatik olarak eÅŸleÅŸtirin ve mutabakat iÅŸlemlerini kolayca yÃ¶netin.</p>
                                <div class="d-flex gap-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle me-2 text-warning"></i>
                                        <small class="opacity-75">Otomatik EÅŸleÅŸtirme</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chart-line me-2 text-warning"></i>
                                        <small class="opacity-75">AnlÄ±k Raporlama</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shield-alt me-2 text-warning"></i>
                                        <small class="opacity-75">GÃ¼venli Ä°ÅŸlem</small>
                                    </div>
                                </div>
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
                <div class="step-text">Hesap & Dosya SeÃ§imi</div>
            </div>
            <div class="step" id="step2">
                <div class="step-circle">2</div>
                <div class="step-text">Otomatik EÅŸleÅŸtirme</div>
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
        <div class="card shadow-lg mb-5" id="uploadSection">
            <div class="card-header text-white position-relative" style="background: var(--primary-gradient); padding: 1.5rem;">
                <div class="position-absolute" style="top: 0; right: 0; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(30px, -30px);"></div>
                <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-upload fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">AdÄ±m 1: Mutabakat BaÅŸlatma</h5>
                        <small class="opacity-75">Banka ekstrenizi yÃ¼kleyin ve hesap bilgilerini girin</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-university me-1"></i>
                                    Banka HesabÄ±
                                </label>
                                <select name="account_id" class="form-select" required>
                                    <option value="">-- Hesap SeÃ§iniz --</option>
                                    @if(\Illuminate\Support\Facades\Schema::hasTable('accounts'))
                                        @foreach(\App\Models\Account::orderBy('name')->get() as $acc)
                                            <option value="{{ $acc->id }}" data-balance="{{ $acc->balance ?? 0 }}">
                                                {{ $acc->bank_name ?? $acc->name }} - {{ $acc->account_no }}
                                                <small>(Bakiye: {{ number_format($acc->balance ?? 0, 2) }} â‚º)</small>
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
                                    Ekstre BitiÅŸ Tarihi
                                </label>
                                <input type="date" name="end_date" class="form-control" required />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-coins me-1"></i>
                                    Ekstre BitiÅŸ Bakiyesi (â‚º)
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
                                <h5>Dosya YÃ¼kle</h5>
                                <p class="text-muted mb-2">CSV veya Excel dosyanÄ±zÄ± buraya sÃ¼rÃ¼kleyip bÄ±rakÄ±n veya <strong>tÄ±klayarak seÃ§in</strong></p>
                                <small class="text-muted">Desteklenen formatlar: .csv, .xlsx, .xls (Maksimum 10MB)</small>
                            </div>
                        </div>
                        <div id="fileInfo" style="display: none;" class="mt-2">
                            <div class="alert alert-info">
                                <i class="fas fa-file me-2"></i>
                                <span id="fileName"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="removeFile">
                                    <i class="fas fa-times"></i> KaldÄ±r
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary btn-lg px-5" id="uploadBtn" type="submit">
                            <i class="fas fa-rocket me-2"></i>
                            MutabakatÄ± BaÅŸlat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="card shadow-lg mb-5" id="progressSection" style="display:none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-cogs text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-primary">Ä°ÅŸlem Durumu</h5>
                            <small class="text-muted">Verileriniz iÅŸleniyor, lÃ¼tfen bekleyin</small>
                        </div>
                    </div>
                    <div class="spinner-border text-primary" role="status" id="loadingSpinner" style="width: 2rem; height: 2rem;">
                        <span class="visually-hidden">YÃ¼kleniyor...</span>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 12px; border-radius: 10px; background: #e9ecef;">
                    <div class="progress-bar progress-bar-striped animate-progress" id="progressBar" style="width: 0%; background: var(--primary-gradient); border-radius: 10px;"></div>
                </div>
                <div class="text-center">
                    <div class="fw-semibold text-primary" id="progressText">Dosya yÃ¼kleniyor...</div>
                </div>
            </div>
        </div>

        <!-- Work Area -->
        <div id="workArea" style="display:none">
            <!-- Balance Overview -->
            <div class="row mb-5 g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card h-100 border-0">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-university text-primary fa-xl"></i>
                            </div>
                            <div class="metric-label">Ekstre BitiÅŸ Bakiyesi</div>
                            <div class="metric-value" id="extClosing">0.00 â‚º</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card h-100 border-0">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-check-circle text-success fa-xl"></i>
                            </div>
                            <div class="metric-label">EÅŸleÅŸen Ä°ÅŸlemler</div>
                            <div class="metric-value text-success" id="matchedTotal">0.00 â‚º</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card metric-card h-100 border-0">
                        <div class="card-body text-center p-4">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-calculator text-info fa-xl"></i>
                            </div>
                            <div class="metric-label">Muhasebe Bakiyesi</div>
                            <div class="metric-value text-info" id="acctTotal">0.00 â‚º</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card balance-card h-100 border-0">
                        <div class="card-body text-center p-4 position-relative">
                            <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-balance-scale text-white fa-xl"></i>
                            </div>
                            <div class="metric-label text-white-50">MUTABAKAT FARKI</div>
                            <div class="metric-value text-white" id="diff">0.00 â‚º</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matching Progress -->
            <div class="card shadow-lg mb-5">
                <div class="card-header text-white position-relative" style="background: var(--success-gradient); padding: 1.5rem;">
                    <div class="position-absolute" style="top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div class="row align-items-center position-relative" style="z-index: 2;">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                    <i class="fas fa-tasks fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">EÅŸleÅŸtirme Ä°lerlemesi</h5>
                                    <small class="opacity-75">Ä°ÅŸlemlerin otomatik eÅŸleÅŸtirme durumu</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-white text-success fw-bold px-3 py-2" id="matchStats" style="font-size: 0.9rem;">0/0 EÅŸleÅŸti</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="progress mb-4" style="height: 16px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar" id="matchProgress" style="width: 0%; background: var(--success-gradient); border-radius: 10px;"></div>
                    </div>
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <div class="text-success fw-bold fs-4" id="matchedCount">0</div>
                                <div class="text-success fw-semibold small">EÅŸleÅŸen</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <div class="text-warning fw-bold fs-4" id="pendingCount">0</div>
                                <div class="text-warning fw-semibold small">Bekleyen</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                <div class="text-danger fw-bold fs-4" id="unmatchedCount">0</div>
                                <div class="text-danger fw-semibold small">EÅŸleÅŸmeyen</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-header text-white position-relative" style="background: var(--primary-gradient); padding: 1.25rem;">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Banka Ekstresi Hareketleri</h6>
                                    <small class="opacity-75">YÃ¼klenen ekstre verileri</small>
                                </div>
                            </div>
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
                                            <th>AÃ§Ä±klama</th>
                                            <th>Tutar</th>
                                            <th>Durum</th>
                                            <th>Ä°ÅŸlem</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-header text-white position-relative" style="background: var(--info-gradient); padding: 1.25rem;">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Muhasebe KayÄ±tlarÄ±</h6>
                                    <small class="opacity-75">Sistem kayÄ±tlarÄ±</small>
                                </div>
                            </div>
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
                                            <th>AÃ§Ä±klama</th>
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
            <div class="card shadow-lg mt-5 border-0">
                <div class="card-body p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary" id="autoMatchBtn">
                                    <i class="fas fa-magic me-2"></i>
                                    Otomatik EÅŸleÅŸtir
                                </button>
                                <button class="btn btn-outline-primary" id="manualMatchBtn">
                                    <i class="fas fa-hand-pointer me-2"></i>
                                    Manuel EÅŸleÅŸtir
                                </button>
                                <button class="btn btn-outline-warning" id="unmatchBtn">
                                    <i class="fas fa-unlink me-2"></i>
                                    EÅŸleÅŸtirmeyi KaldÄ±r
                                </button>
                                <button class="btn btn-outline-secondary" id="refreshBtn">
                                    <i class="fas fa-sync me-2"></i>
                                    Yenile
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <button class="btn btn-success btn-lg px-5 shadow" id="completeBtn" disabled style="background: var(--success-gradient); border: none;">
                                <i class="fas fa-check-double me-2"></i>
                                MutabakatÄ± Tamamla
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Report -->
            <div class="card shadow-lg mt-5 border-0" id="summarySection" style="display:none">
                <div class="card-header text-white position-relative" style="background: var(--success-gradient); padding: 1.5rem;">
                    <div class="position-absolute" style="top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                            <i class="fas fa-chart-pie fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">Mutabakat Ã–zeti</h5>
                            <small class="opacity-75">DetaylÄ± eÅŸleÅŸtirme raporu ve istatistikler</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="text-center">
                                <canvas id="summaryChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="h-100 d-flex flex-column justify-content-center">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="text-primary fw-bold fs-3" id="totalBankTransactions">0</div>
                                            <div class="text-primary fw-semibold small">Toplam Ä°ÅŸlem</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="text-success fw-bold fs-3" id="totalMatched">0</div>
                                            <div class="text-success fw-semibold small">EÅŸleÅŸen</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-danger bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="text-danger fw-bold fs-3" id="totalUnmatched">0</div>
                                            <div class="text-danger fw-semibold small">EÅŸleÅŸmeyen</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="rounded-3 p-3 text-center" style="background: var(--success-gradient);">
                                            <div class="text-white fw-bold fs-3" id="successRate">0%</div>
                                            <div class="text-white fw-semibold small">BaÅŸarÄ± OranÄ±</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
