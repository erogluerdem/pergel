@extends('layouts.master')
@section('title', 'Müşteri Hesapları')
@section('topbar-title', 'Finans - Müşteri Hesapları')

@push('styles')
<style>
    .metric-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .metric-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.18), 0 0 30px rgba(102, 126, 234, 0.1);
    }
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-gradient-start), var(--card-gradient-end));
    }
    .metric-card.primary { --card-gradient-start: #667eea; --card-gradient-end: #764ba2; }
    .metric-card.danger { --card-gradient-start: #f093fb; --card-gradient-end: #f5576c; }
    .metric-card.success { --card-gradient-start: #4facfe; --card-gradient-end: #00f2fe; }
    .metric-card.warning { --card-gradient-start: #43e97b; --card-gradient-end: #38f9d7; }

    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .metric-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }
    .metric-card:hover .metric-icon::before {
        left: 100%;
    }

    .metric-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 10px 0;
    }

    .metric-label {
        color: #7f8c8d;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .trend-indicator {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        margin-top: 8px;
    }

    .trend-up { color: #27ae60; }
    .trend-down { color: #e74c3c; }

    .customer-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .customer-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .customer-avatar::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
        transform: rotate(-45deg) translateX(-100%);
        transition: transform 0.6s ease;
    }
    .customer-avatar:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    .customer-avatar:hover::before {
        transform: rotate(-45deg) translateX(100%);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active { background: #d4edda; color: #155724; }
    .status-inactive { background: #f8d7da; color: #721c24; }

    .risk-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .risk-low { background: #d1ecf1; color: #0c5460; }
    .risk-medium { background: #fff3cd; color: #856404; }
    .risk-high { background: #f8d7da; color: #721c24; }

    .search-card {
        background: linear-gradient(135deg, rgba(248, 249, 250, 0.9) 0%, rgba(233, 236, 239, 0.7) 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 18px;
        padding: 30px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
    }
    .search-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        background-size: 200% 100%;
        animation: searchGlow 3s ease-in-out infinite;
    }
    @keyframes searchGlow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    /* Page Animation Classes */
    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .metric-card {
        animation: slideInUp 0.6s ease-out both;
    }
    .metric-card:nth-child(1) { animation-delay: 0.1s; }
    .metric-card:nth-child(2) { animation-delay: 0.2s; }
    .metric-card:nth-child(3) { animation-delay: 0.3s; }
    .metric-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Status Badge Animations */
    .status-badge, .risk-badge {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .status-badge::before, .risk-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s ease;
    }
    .status-badge:hover::before, .risk-badge:hover::before {
        left: 100%;
    }
    .status-badge:hover, .risk-badge:hover {
        transform: scale(1.05);
    }

    .chart-container {
        position: relative;
        height: 300px;
        background: white;
        border-radius: 12px;
        padding: 20px;
    }

    .table-modern {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-modern thead th {
        border: none;
        padding: 20px 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .table-modern tbody tr::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .table-modern tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.04) 0%, rgba(248, 249, 250, 0.8) 100%);
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .table-modern tbody tr:hover::before {
        transform: scaleY(1);
    }

    .table-modern tbody td {
        padding: 18px 15px;
        vertical-align: middle;
        border: none;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        margin: 0 3px;
        position: relative;
        overflow: hidden;
    }
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        transition: all 0.4s ease;
        transform: translate(-50%, -50%);
    }
    .action-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }
    .action-btn:hover::before {
        width: 120%;
        height: 120%;
    }
    .action-btn:active {
        transform: translateY(-1px) scale(1.02);
    }

    /* Ensure dropdown menus show above table/card and aren't clipped */
    .dropdown-menu {
        z-index: 12000;
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

    /* Tablo alignment ve spacing düzeltmeleri */
    .table-modern {
        width: 100%;
        table-layout: fixed;
    }

    .table-modern th:nth-child(1) { width: 25%; }
    .table-modern th:nth-child(2) { width: 18%; }
    .table-modern th:nth-child(3) { width: 12%; }
    .table-modern th:nth-child(4) { width: 10%; }
    .table-modern th:nth-child(5) { width: 25%; }
    .table-modern th:nth-child(6) { width: 10%; }

    .table-modern tbody td {
        padding: 15px 12px;
        vertical-align: top;
        border: none;
    }

    .table-modern tbody td:first-child {
        padding-left: 20px;
    }

    .table-modern thead th:first-child {
        padding-left: 20px;
    }

    .customer-info-cell {
        min-height: 80px;
        display: flex;
        align-items: flex-start;
        padding-top: 20px !important;
    }

    .customer-avatar {
        flex-shrink: 0;
        margin-right: 15px;
    }

    .customer-details {
        flex-grow: 1;
        min-width: 0;
    }

    /* Form floating düzeltmeleri */
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
    }

    .search-card .form-control, .search-card .form-select {
        background-color: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(0, 0, 0, 0.1);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
    }

    .search-card .form-control:focus, .search-card .form-select:focus {
        background-color: white;
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), 0 4px 12px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    /* Responsive düzeltmeler */
    @media (max-width: 768px) {
        .table-modern th:nth-child(1) { width: 30%; }
        .table-modern th:nth-child(2) { width: 20%; }
        .table-modern th:nth-child(3) { width: 15%; }
        .table-modern th:nth-child(4) { width: 10%; }
        .table-modern th:nth-child(5) { width: 20%; }
        .table-modern th:nth-child(6) { width: 5%; }
    }
</style>
@endpush

@section('content')
<div class="finance-customers fade-in">
    <!-- Enhanced Metrics Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card primary h-100">
                <div class="card-body p-4 text-center">
                    <div class="metric-icon primary mx-auto" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="metric-label">Toplam Alacak Bakiyesi</div>
                    <div class="metric-value">{{ number_format($totalReceivable ?? 0, 2, ',', '.') }} ₺</div>
                    <div class="trend-indicator trend-up justify-content-center">
                        <i class="fas fa-arrow-up"></i>
                        <span>+5.2% bu ay</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card danger h-100">
                <div class="card-body p-4 text-center">
                    <div class="metric-icon danger mx-auto" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="metric-label">Vadesi Geçmiş Alacaklar</div>
                    <div class="metric-value">{{ number_format($totalOverdue ?? 0, 2, ',', '.') }} ₺</div>
                    <div class="trend-indicator trend-down justify-content-center">
                        <i class="fas fa-arrow-down"></i>
                        <span>-2.1% bu ay</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card success h-100">
                <div class="card-body p-4 text-center">
                    <div class="metric-icon success mx-auto" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="metric-label">Bu Ayki Tahsilat</div>
                    <div class="metric-value">{{ number_format($thisMonthCollections ?? 0, 2, ',', '.') }} ₺</div>
                    <div class="trend-indicator trend-up justify-content-center">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12.8% geçen aya göre</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card metric-card warning h-100">
                <div class="card-body p-4 text-center">
                    <div class="metric-icon warning mx-auto" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <i class="fas fa-users-slash"></i>
                    </div>
                    <div class="metric-label">Riskli Müşteri</div>
                    <div class="metric-value">{{ $riskyCustomersCount ?? 0 }}</div>
                    <div class="trend-indicator trend-down justify-content-center">
                        <i class="fas fa-arrow-down"></i>
                        <span>-3 bu ay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card customer-card h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Alacak Yaşlandırma Analizi
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-calendar-alt me-1"></i> Bu Ay
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Bu Hafta</a></li>
                                <li><a class="dropdown-item" href="#">Bu Ay</a></li>
                                <li><a class="dropdown-item" href="#">Son 3 Ay</a></li>
                                <li><a class="dropdown-item" href="#">Bu Yıl</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ageingChart" width="400" height="200"></canvas>
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-center">
                            <div class="border-end">
                                <div class="h6 mb-0 text-success">{{ number_format(($totalReceivable ?? 0) * 0.6, 0, ',', '.') }} ₺</div>
                                <small class="text-muted">0-30 Gün</small>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="border-end">
                                <div class="h6 mb-0 text-warning">{{ number_format(($totalReceivable ?? 0) * 0.25, 0, ',', '.') }} ₺</div>
                                <small class="text-muted">31-60 Gün</small>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="border-end">
                                <div class="h6 mb-0 text-danger">{{ number_format(($totalReceivable ?? 0) * 0.10, 0, ',', '.') }} ₺</div>
                                <small class="text-muted">61-90 Gün</small>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="h6 mb-0 text-dark">{{ number_format(($totalReceivable ?? 0) * 0.05, 0, ',', '.') }} ₺</div>
                            <small class="text-muted">90+ Gün</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card customer-card h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        En Yüksek Bakiyeli Müşteriler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($topDebtors as $index => $c)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="customer-avatar me-3">
                                        {{ strtoupper(substr($c->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $c->name }}</h6>
                                                <p class="mb-0 text-muted small">
                                                    <i class="fas fa-user me-1"></i>{{ $c->contact_person }}
                                                    @if($c->city)
                                                        <i class="fas fa-map-marker-alt ms-2 me-1"></i>{{ $c->city }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-primary">{{ number_format($c->balance, 0, ',', '.') }} ₺</div>
                                                <div class="small text-muted">#{{ $index + 1 }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($index < count($topDebtors) - 1)
                                    <hr class="my-2">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search and Filters -->
    <div class="search-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-search text-primary me-2"></i>
                Gelişmiş Arama ve Filtreler
            </h5>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                <i class="fas fa-sliders-h me-1"></i> Gelişmiş Filtreler
            </button>
        </div>

        <form method="GET" action="" id="customerSearchForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="searchQuery" name="q" value="{{ request('q') }}" placeholder=" ">
                        <label for="searchQuery"><i class="fas fa-search me-2"></i>Müşteri adı, vergi no...</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="riskFilter" name="risk">
                            <option value="">Tüm Müşteriler</option>
                            <option value="high" {{ request('risk') == 'high' ? 'selected' : '' }}>Yüksek Riskli</option>
                            <option value="medium">Orta Riskli</option>
                            <option value="low">Düşük Riskli</option>
                        </select>
                        <label for="riskFilter"><i class="fas fa-exclamation-triangle me-2"></i>Risk Seviyesi</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">Tüm Durumlar</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                        </select>
                        <label for="statusFilter"><i class="fas fa-toggle-on me-2"></i>Durum</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 h-100">
                        <i class="fas fa-search me-2"></i>Ara
                    </button>
                </div>
            </div>

            <div class="collapse mt-3" id="advancedFilters">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="minBalance" name="min_balance" value="{{ request('min_balance') }}" placeholder="Min. Bakiye">
                            <label for="minBalance">Minimum Bakiye</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="maxBalance" name="max_balance" value="{{ request('max_balance') }}" placeholder="Max. Bakiye">
                            <label for="maxBalance">Maksimum Bakiye</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select" id="cityFilter" name="city">
                                <option value="">Tüm Şehirler</option>
                                <option value="istanbul">İstanbul</option>
                                <option value="ankara">Ankara</option>
                                <option value="izmir">İzmir</option>
                            </select>
                            <label for="cityFilter">Şehir</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select" id="sortBy" name="sort">
                                <option value="name">İsim A-Z</option>
                                <option value="balance_desc">Bakiye (Yüksek-Düşük)</option>
                                <option value="balance_asc">Bakiye (Düşük-Yüksek)</option>
                                <option value="risk_desc">Risk (Yüksek-Düşük)</option>
                            </select>
                            <label for="sortBy">Sıralama</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Customer List with Enhanced Table -->
    <div class="card table-modern">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="fas fa-users text-primary me-2"></i>
                        Müşteri Listesi
                    </h5>
                    <p class="text-muted mb-0">Toplam {{ $customersPaged->total() ?? 0 }} müşteri bulundu</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary action-btn" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-2"></i>Excel'e Aktar
                    </button>
                    <button class="btn btn-outline-secondary action-btn" onclick="printTable()">
                        <i class="fas fa-print me-2"></i>Yazdır
                    </button>
                    <a href="{{ route('finance.accounts.customers.recycle') }}" class="btn btn-outline-danger action-btn">
                        <i class="fas fa-trash-restore me-2"></i>Geri Dönüşüm
                    </a>
                    <a href="{{ route('finance.accounts.customers.create') }}" class="btn btn-success action-btn">
                        <i class="fas fa-plus me-2"></i>Yeni Müşteri
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern mb-0" id="customersTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-building me-2"></i>Müşteri Bilgileri</th>
                        <th><i class="fas fa-wallet me-2"></i>Finansal Durum</th>
                        <th><i class="fas fa-shield-alt me-2"></i>Risk Analizi</th>
                        <th><i class="fas fa-toggle-on me-2"></i>Durum</th>
                        <th><i class="fas fa-phone me-2"></i>İletişim</th>
                        <th><i class="fas fa-cogs me-2"></i>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customersPaged as $cust)
                    <tr>
                        <td class="customer-info-cell">
                            <div class="customer-avatar">
                                {{ strtoupper(substr($cust->name ?? 'M', 0, 1)) }}
                            </div>
                            <div class="customer-details">
                                <div class="fw-bold mb-1">
                                    <a href="{{ url('finance/accounts/customers/'.$cust->id) }}" class="text-decoration-none">
                                        {{ $cust->name }}
                                    </a>
                                </div>
                                @if($cust->tax_number)
                                    <small class="text-muted d-block">VKN: {{ $cust->tax_number }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold text-primary fs-6">{{ number_format($cust->balance ?? 0, 2, ',', '.') }} ₺</div>
                                <div class="small text-muted">
                                    Limit: {{ number_format($cust->credit_limit ?? 0, 2, ',', '.') }} ₺
                                </div>
                                @php
                                    $utilization = ($cust->credit_limit > 0) ? (($cust->balance ?? 0) / $cust->credit_limit) * 100 : 0;
                                @endphp
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar {{ $utilization > 80 ? 'bg-danger' : ($utilization > 60 ? 'bg-warning' : 'bg-success') }}"
                                         style="width: {{ min(100, $utilization) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $riskScore = $cust->risk_score ?? 0;
                                $riskLevel = $riskScore >= 70 ? 'high' : ($riskScore >= 40 ? 'medium' : 'low');
                                $riskText = $riskScore >= 70 ? 'Yüksek' : ($riskScore >= 40 ? 'Orta' : 'Düşük');
                                $riskClass = "risk-{$riskLevel}";
                            @endphp
                            <span class="risk-badge {{ $riskClass }}">{{ $riskText }}</span>
                            <div class="small text-muted mt-1">Skor: {{ $riskScore }}/100</div>
                        </td>
                        <td>
                            <span class="status-badge {{ $cust->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                @if($cust->status == 'active')
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                @else
                                    <i class="fas fa-times-circle me-1"></i>Pasif
                                @endif
                            </span>
                        </td>
                        <td>
                            <div>
                                @if($cust->contact_person)
                                    <div class="fw-bold">{{ $cust->contact_person }}</div>
                                @endif
                                @if($cust->phone)
                                    <div class="small text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $cust->phone }}
                                    </div>
                                @endif
                                @if($cust->email)
                                    <div class="small text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $cust->email }}
                                    </div>
                                @endif
                                @if($cust->city)
                                    <div class="small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $cust->city }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ url('finance/accounts/customers/'.$cust->id) }}">
                                            <i class="fas fa-eye me-2"></i>Detayları Görüntüle
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('finance.accounts.customers.edit', ['id'=>$cust->id]) }}">
                                            <i class="fas fa-edit me-2"></i>Düzenle
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="sendReconciliation({{ $cust->id }})">
                                            <i class="fas fa-paper-plane me-2"></i>Mutabakat Gönder
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('finance.accounts.customers.reconcile.download', ['id' => $cust->id]) }}" target="_blank">
                                            <i class="fas fa-download me-2"></i>Mutabakat İndir
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="openCustomerDeleteModal({{ $cust->id }}, '{{ addslashes($cust->name) }}')">
                                            <i class="fas fa-trash me-2"></i>Sil
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($customersPaged->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        {{ $customersPaged->firstItem() }}-{{ $customersPaged->lastItem() }} / {{ $customersPaged->total() }} müşteri gösteriliyor
                    </div>
                    <div>
                        {{ $customersPaged->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div> <!-- finance-customers -->

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Ageing Chart
    initializeAgeingChart();

    // Initialize Search
    initializeSearch();

    // Initialize Table Actions
    initializeTableActions();
});

function initializeAgeingChart() {
    const ctx = document.getElementById('ageingChart');
    if (!ctx) return;

    const totalReceivable = {{ $totalReceivable ?? 0 }};

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['0-30 Gün', '31-60 Gün', '61-90 Gün', '90+ Gün'],
            datasets: [{
                data: [
                    totalReceivable * 0.6,
                    totalReceivable * 0.25,
                    totalReceivable * 0.10,
                    totalReceivable * 0.05
                ],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return context.label + ': ' + new Intl.NumberFormat('tr-TR', {
                                style: 'currency',
                                currency: 'TRY'
                            }).format(value) + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

function initializeSearch() {
    const searchForm = document.getElementById('customerSearchForm');
    const searchInput = document.getElementById('searchQuery');

    // Live search with debounce
    let searchTimeout;
    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 3 || this.value.length === 0) {
                searchForm.submit();
            }
        }, 500);
    });

    // Filter change handlers
    ['riskFilter', 'statusFilter', 'cityFilter', 'sortBy'].forEach(id => {
        const element = document.getElementById(id);
        element?.addEventListener('change', () => searchForm.submit());
    });
}

function initializeTableActions() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize dropdowns and ensure they render into body to avoid clipping inside table/card
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function (dropdownToggleEl) {
        // cache the menu on the parent so we can always find it even after moving to body
        const p = dropdownToggleEl.closest('.btn-group, .dropdown');
        if (p && !p._menu) {
            p._menu = p.querySelector('.dropdown-menu');
            if (p._menu) {
                // keep original parent refs
                p._menu._origParent = p._menu.parentNode;
                p._menu._origNext = p._menu.nextSibling;
            }
        }
        return new bootstrap.Dropdown(dropdownToggleEl, {
            popperConfig: function(defaultBsPopper) {
                const config = Object.assign({}, defaultBsPopper);
                // Use fixed strategy so Popper positions relative to viewport and not clipped by transformed ancestors
                config.strategy = 'fixed';
                config.modifiers = config.modifiers || [];
                config.modifiers.push({ name: 'preventOverflow', options: { boundary: document.body } });
                // Disable adaptive computeStyles to avoid subpixel/overflow shifts in some browsers
                config.modifiers.push({ name: 'computeStyles', options: { adaptive: false } });
                return config;
            }
        });
    });

    // Use document-level handlers so we catch show/hide for any dropdown, even dynamic rows
    // Use 'shown' so menu is actually visible (not display:none) before we measure it
    document.addEventListener('shown.bs.dropdown', function(e) {
        try {
            const toggle = e.target.closest('.dropdown-toggle');
            const parent = e.target.closest('.btn-group, .dropdown');
            if (!parent) return;
            const menu = parent._menu || parent.querySelector('.dropdown-menu');
            if (!menu) return;

            // Avoid double-moving
            if (menu.dataset._moved === '1') return;

            console.debug('[dropdown] show for parent:', parent, 'toggle:', toggle);
            try {
                const menuRect = menu.getBoundingClientRect();
                const toggleRect = (toggle || parent.querySelector('.dropdown-toggle')).getBoundingClientRect();
                const menuStyle = window.getComputedStyle(menu);
                console.debug('[dropdown-debug] toggleRect:', toggleRect, 'menuRect(before move):', menuRect, 'menuStyle.display:', menuStyle.display, 'menuStyle.visibility:', menuStyle.visibility, 'menuStyle.zIndex:', menuStyle.zIndex);
            } catch (dbe) {
                console.debug('Could not read rect/style for dropdown menu', dbe);
            }

            // store original place if not stored (we may have cached it during init)
            if (!menu._origParent) {
                menu._origParent = menu.parentNode;
                menu._origNext = menu.nextSibling;
            }

            // move to body
            document.body.appendChild(menu);
            // use fixed positioning so coordinates map to viewport and are not affected by ancestor transforms
            menu.style.position = 'fixed';
            menu.style.transform = 'none';
            menu.style.minWidth = parent.getBoundingClientRect().width + 'px';
            menu.style.zIndex = 12000;
            menu.dataset._moved = '1';

            const actualToggle = toggle || parent.querySelector('.dropdown-toggle');
            // try to identify customer name in the same row for clearer debug
            let custName = 'unknown';
            const tr = parent.closest('tr');
            if (tr) {
                const nameEl = tr.querySelector('.customer-details a');
                if (nameEl) custName = nameEl.textContent.trim();
            }
            console.debug('[dropdown-debug] opening for customer:', custName);

            // destroy Bootstrap/Popper instance for this toggle so it doesn't overwrite our manual positioning
            try {
                const inst = bootstrap.Dropdown.getInstance(actualToggle);
                if (inst && inst._popper) {
                    try { inst._popper.destroy(); } catch(e) { console.debug('popper destroy failed', e); }
                    inst._popper = null;
                }
            } catch (e) {
                console.debug('Could not destroy popper for toggle', e);
            }

            // menu should now be visible; measure again
            try {
                const menuRectAfter = menu.getBoundingClientRect();
                console.debug('[dropdown-debug] menuRect(after show):', menuRectAfter);
            } catch (merr) {
                console.debug('Could not read menu rect after shown', merr);
            }
            positionDropdownMenu(actualToggle, menu);

            menu._repositionHandler = function() { positionDropdownMenu(actualToggle, menu); };
            window.addEventListener('scroll', menu._repositionHandler, true);
            window.addEventListener('resize', menu._repositionHandler);
        } catch (err) {
            console.error('Error in show.bs.dropdown handler', err);
        }
    });

    // Use 'hidden' to restore after it's fully closed
    document.addEventListener('hidden.bs.dropdown', function(e) {
        try {
            const parent = e.target.closest('.btn-group, .dropdown');
            if (!parent) return;
            const menu = parent.querySelector('.dropdown-menu');
            if (!menu) return;

            console.debug('[dropdown] hide for parent:', parent);

            window.removeEventListener('scroll', menu._repositionHandler, true);
            window.removeEventListener('resize', menu._repositionHandler);

            if (menu._origParent) {
                if (menu._origNext) {
                    menu._origParent.insertBefore(menu, menu._origNext);
                } else {
                    menu._origParent.appendChild(menu);
                }
            }

            menu.style.position = '';
            menu.style.left = '';
            menu.style.top = '';
            menu.style.minWidth = '';
            menu.style.zIndex = '';
            menu.dataset._moved = '0';
        } catch (err) {
            console.error('Error in hide.bs.dropdown handler', err);
        }
    });

    function positionDropdownMenu(toggle, menu) {
        if (!toggle || !menu) return;
        const rect = toggle.getBoundingClientRect();
        // For fixed positioning use viewport coordinates (rect.left/top are already viewport-based)
        let left = rect.left;
        // Determine menu dimensions (may be zero if not yet in DOM flow)
        let menuHeight = menu.offsetHeight || menu.getBoundingClientRect().height || 200;
        let menuWidth = menu.offsetWidth || menu.getBoundingClientRect().width || 150;

        // Ensure menu is scrollable if too tall
        menu.style.maxHeight = Math.max(window.innerHeight * 0.6, 200) + 'px';
        menu.style.overflowY = 'auto';

        // Space calculations
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;

        let top;
        if (spaceBelow < menuHeight && spaceAbove > menuHeight) {
            // open above the toggle
            top = rect.top - menuHeight;
        } else if (spaceBelow < 120 && spaceAbove > spaceBelow) {
            // fallback: if very little space below but some above, open above
            top = Math.max(8, rect.top - Math.min(menuHeight, spaceAbove));
        } else {
            // open below
            top = rect.bottom;
        }

        // Keep menu within viewport horizontally
        const rightLimit = window.innerWidth - 8;
        if (left + menuWidth > rightLimit) {
            left = Math.max(8, rightLimit - menuWidth);
        }
        left = Math.max(8, left);

        menu.style.left = left + 'px';
        menu.style.top = top + 'px';
    }

    // --- Manual dropdown manager (fallback) -------------------------------------------------
    // Use this to avoid Bootstrap/Popper conflicts: intercept clicks and open/close menus ourselves.
    (function manualDropdowns() {
        // close all open menus
        function closeAll() {
            document.querySelectorAll('.dropdown-menu').forEach(function(m) {
                if (m.dataset._open === '1') {
                    // restore
                    if (m._origParent) {
                        if (m._origNext) m._origParent.insertBefore(m, m._origNext);
                        else m._origParent.appendChild(m);
                    }
                    m.classList.remove('show');
                    m.dataset._open = '0';
                    const t = m._toggleEl;
                    if (t) t.classList.remove('show'), t.setAttribute('aria-expanded', 'false');
                    m.style.position = '';
                    m.style.left = '';
                    m.style.top = '';
                    m.style.minWidth = '';
                    m.style.zIndex = '';
                }
            });
        }

        // delegated click handler
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.dropdown-toggle');
            if (!toggle) return;
            e.preventDefault();
            e.stopPropagation();

            const parent = toggle.closest('.btn-group, .dropdown');
            if (!parent) return;
            const menu = parent._menu || parent.querySelector('.dropdown-menu');
            if (!menu) return;

            // if already open, close it
            if (menu.dataset._open === '1') {
                closeAll();
                return;
            }

            // close others then open this
            closeAll();

            // move to body
            if (!menu._origParent) {
                menu._origParent = menu.parentNode;
                menu._origNext = menu.nextSibling;
            }
            document.body.appendChild(menu);
            menu._toggleEl = toggle;
            menu.style.position = 'fixed';
            menu.style.transform = 'none';
            menu.style.minWidth = parent.getBoundingClientRect().width + 'px';
            menu.style.zIndex = 12000;
            menu.classList.add('show');
            menu.dataset._open = '1';
            toggle.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');

            positionDropdownMenu(toggle, menu);
        }, true);

        // close on outside click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown, .btn-group')) return;
            closeAll();
        });

        // close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAll();
        });
    })();
}

function exportToExcel() {
    // Create a simple CSV export
    const table = document.getElementById('customersTable');
    const rows = [];

    // Add headers
    const headers = ['Müşteri Adı', 'Bakiye', 'Kredi Limiti', 'Risk Skoru', 'Durum', 'İletişim'];
    rows.push(headers.join(','));

    // Add data rows
    const tbody = table.querySelector('tbody');
    for (const row of tbody.rows) {
        const cells = [];
        cells.push(`"${row.cells[0].textContent.trim()}"`);
        cells.push(`"${row.cells[1].textContent.trim()}"`);
        cells.push(`"${row.cells[2].textContent.trim()}"`);
        cells.push(`"${row.cells[3].textContent.trim()}"`);
        cells.push(`"${row.cells[4].textContent.trim()}"`);
        rows.push(cells.join(','));
    }

    // Download CSV
    const csvContent = rows.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'musteriler_' + new Date().toISOString().slice(0, 10) + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Show success message
    showToast('Excel dosyası indirildi!', 'success');
}

function printTable() {
    const printContent = document.getElementById('customersTable').outerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
        <head>
            <title>Müşteri Listesi</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                @media print {
                    .btn, .dropdown { display: none !important; }
                }
            </style>
        </head>
        <body>
            <h2>Müşteri Listesi - ${new Date().toLocaleDateString('tr-TR')}</h2>
            ${printContent}
        </body>
        </html>
    `;

    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

function sendReconciliation(customerId) {
    if (confirm('Bu müşteriye mutabakat e-postası göndermek istediğinize emin misiniz?')) {
        fetch(`/finance/accounts/customers/${customerId}/reconcile`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Mutabakat e-postası gönderildi!', 'success');
            } else {
                showToast('E-posta gönderilirken hata oluştu!', 'error');
            }
        })
        .catch(error => {
            showToast('Bir hata oluştu!', 'error');
        });
    }
}

function downloadReconciliation(customerId) {
    window.open(`/finance/accounts/customers/${customerId}/reconcile/download`, '_blank');
    showToast('Mutabakat dosyası indiriliyor...', 'info');
}

function deleteCustomer(customerId, customerName) {
    // Deprecated: use modal-based flow
    openCustomerDeleteModal(customerId, customerName);
}

function openCustomerDeleteModal(customerId, customerName) {
    // create modal if not exists
    let modal = document.getElementById('customerDeleteModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'customerDeleteModal';
        modal.className = 'modal fade';
        modal.tabIndex = -1;
        modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Müşteriyi Sil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body">
                    <p id="customerDeleteMessage"></p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="permanentDeleteCheck">
                        <label class="form-check-label" for="permanentDeleteCheck">Kalıcı olarak sil (geri alınamaz)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" id="confirmDeleteCustomerBtn" class="btn btn-danger">Sil</button>
                </div>
            </div>
        </div>`;
        document.body.appendChild(modal);
    }

    document.getElementById('customerDeleteMessage').textContent = `"${customerName}" adlı müşteriyi silmek istediğinize emin misiniz?`;

    const confirmBtn = document.getElementById('confirmDeleteCustomerBtn');
    confirmBtn.onclick = function() {
        const permanent = document.getElementById('permanentDeleteCheck').checked ? 1 : 0;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/finance/accounts/customers/${customerId}`;
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden'; csrfToken.name = '_token'; csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const methodField = document.createElement('input'); methodField.type = 'hidden'; methodField.name = '_method'; methodField.value = 'DELETE';
        const permField = document.createElement('input'); permField.type = 'hidden'; permField.name = 'permanent'; permField.value = permanent;
        form.appendChild(csrfToken); form.appendChild(methodField); form.appendChild(permField);
        document.body.appendChild(form);
        form.submit();
    };

    // show modal using bootstrap
    const bsModal = new bootstrap.Modal(document.getElementById('customerDeleteModal'));
    bsModal.show();
}

function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);
    }

    // Create toast
    const toast = document.createElement('div');
    toast.style.cssText = `
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
        color: white;
        padding: 12px 20px;
        margin: 5px 0;
        border-radius: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    toast.textContent = message;

    toastContainer.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            toastContainer.removeChild(toast);
        }, 300);
    }, 3000);
}

// Add loading states
function showLoading(element) {
    element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Yükleniyor...';
    element.disabled = true;
}

function hideLoading(element, originalText) {
    element.innerHTML = originalText;
    element.disabled = false;
}

// Advanced filtering
function applyQuickFilter(type) {
    const form = document.getElementById('customerSearchForm');
    const riskFilter = document.getElementById('riskFilter');

    switch(type) {
        case 'high-risk':
            riskFilter.value = 'high';
            break;
        case 'active':
            document.getElementById('statusFilter').value = 'active';
            break;
        case 'high-balance':
            document.getElementById('sortBy').value = 'balance_desc';
            break;
    }

    form.submit();
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + F for search
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchQuery').focus();
    }

    // Ctrl + N for new customer
    if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        window.location.href = '{{ route("finance.accounts.customers.create") }}';
    }

    // Ctrl + E for export
    if (e.ctrlKey && e.key === 'e') {
        e.preventDefault();
        exportToExcel();
    }
});

// Auto-refresh every 5 minutes
setInterval(function() {
    if (document.hidden) return; // Don't refresh if tab is not active

    // Only refresh if no modals are open
    if (!document.querySelector('.modal.show')) {
        location.reload();
    }
}, 300000); // 5 minutes

// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Table row click highlighting
    document.querySelectorAll('.table-modern tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            // Remove previous selections
            document.querySelectorAll('.table-modern tbody tr').forEach(r => r.classList.remove('selected'));
            // Add selection to clicked row
            this.classList.add('selected');
        });
    });

    // Smooth hover effects for buttons
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Form focus enhancements
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.form-floating')?.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.closest('.form-floating')?.classList.remove('focused');
        });
    });
});
</script>

<style>
    /* Additional interactive styles */
    .table-modern tbody tr.selected {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.08) 0%, rgba(248, 249, 250, 0.9) 100%) !important;
        border-left: 4px solid #667eea !important;
    }

    .table-modern tbody tr.selected::before {
        transform: scaleY(1) !important;
    }

    .form-floating.focused {
        transform: scale(1.02);
    }

    .form-floating.focused label {
        color: #667eea !important;
        font-weight: 600;
    }
</style>
@endpush
