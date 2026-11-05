@extends('layouts.master')
@section('title', 'Döviz Kurları ve Pozisyonları')
@section('topbar-title', 'Finans - Döviz Yönetimi')

@push('meta')
<meta name="description" content="Güncel döviz kurları, pozisyon takibi ve döviz işlemleri. USD, EUR, GBP, CHF, JPY ve CAD kurlarını canlı olarak takip edin.">
<meta name="keywords" content="döviz, kur, USD, EUR, GBP, CHF, JPY, CAD, finans, pozisyon">
<meta property="og:title" content="Döviz Kurları ve Pozisyonları - Pergel">
<meta property="og:description" content="Güncel döviz kurları ve pozisyon yönetimi sistemi">
<meta property="og:type" content="website">
@endpush

@push('styles')
<link rel="preload" href="https://cdn.jsdelivr.net/npm/chart.js" as="script">
<style>
    .currency-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
        position: relative;
    }
    
    .currency-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    
    .currency-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .currency-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }
    
    .currency-flag {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .currency-symbol {
        font-size: 1.8rem;
        font-weight: bold;
        margin-left: 0.5rem;
    }
    
    .rate-display {
        background: #f8f9ff;
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1rem;
    }
    
    .rate-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2d3748;
    }
    
    .rate-change {
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        display: inline-block;
        margin-top: 0.5rem;
    }
    
    .rate-change.positive {
        background: #e6fffa;
        color: #047857;
    }
    
    .rate-change.negative {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .stats-overview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .stats-overview::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .position-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .position-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .transaction-item {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }
    
    .transaction-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-currency {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-currency:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .live-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
        margin-right: 0.5rem;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        height: 300px;
    }
    
    .metric-card {
        text-align: center;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .refresh-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }
    
    .refresh-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.6);
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Responsive tasarım */
    @media (max-width: 768px) {
        .currency-card {
            margin-bottom: 1rem;
        }
        
        .currency-header {
            padding: 1rem;
        }
        
        .currency-flag {
            font-size: 2rem;
        }
        
        .rate-value {
            font-size: 1.2rem;
        }
        
        .stats-overview {
            padding: 1.5rem;
        }
        
        .metric-value {
            font-size: 1.5rem;
        }
        
        .refresh-btn {
            bottom: 1rem;
            right: 1rem;
            width: 50px;
            height: 50px;
        }
        
        .chart-container {
            height: 250px;
        }
    }
    
    @media (max-width: 576px) {
        .btn-currency {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .stats-overview .row [class*="col-"] {
            margin-bottom: 1rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
    }
    
    /* Yazdırma stilleri */
    @media print {
        .refresh-btn,
        .btn,
        .modal {
            display: none !important;
        }
        
        .currency-card {
            break-inside: avoid;
            box-shadow: none;
            border: 1px solid #dee2e6;
        }
        
        .stats-overview {
            background: #f8f9fa !important;
            color: #495057 !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Özet İstatistikler -->
<div class="stats-overview">
    <div class="row">
        <div class="col-md-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">{{ count($exchangeRates) }}</div>
                <div style="color: rgba(255,255,255,0.8);">Takip Edilen Döviz</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">₺{{ number_format($totalPositionTL, 0, ',', '.') }}</div>
                <div style="color: rgba(255,255,255,0.8);">Toplam Pozisyon</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">{{ $totalAccounts }}</div>
                <div style="color: rgba(255,255,255,0.8);">Döviz Hesabı</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">
                    <span class="live-indicator"></span>CANLI
                </div>
                <div style="color: rgba(255,255,255,0.8);">Kur Durumu</div>
            </div>
        </div>
    </div>
</div>

<!-- Döviz Kurları -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>
                Güncel Döviz Kurları
                <span class="live-indicator"></span>
            </h4>
            <div>
                <button class="btn btn-currency me-2" onclick="refreshRates()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Kurları Güncelle
                </button>
                <button class="btn btn-currency me-2" data-bs-toggle="modal" data-bs-target="#exchangeModal">
                    <i class="fas fa-exchange-alt me-1"></i>
                    Döviz İşlemi
                </button>
                <button class="btn btn-currency" data-bs-toggle="modal" data-bs-target="#alertModal">
                    <i class="fas fa-bell me-1"></i>
                    Kur Alarmı
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach($exchangeRates as $code => $rate)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="currency-card" data-currency="{{ $code }}">
            <div class="currency-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="currency-flag">{{ $rate['flag'] }}</span>
                        <h5 class="mb-1">{{ $code }}<span class="currency-symbol">{{ $rate['symbol'] }}</span></h5>
                        <small style="opacity: 0.8;">{{ $rate['name'] }}</small>
                    </div>
                    <div class="text-end">
                        <small style="opacity: 0.8;">Son güncelleme</small>
                        <div style="font-weight: 600;">{{ $rate['last_update'] }}</div>
                    </div>
                </div>
            </div>
            
            <div class="rate-display">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <small class="text-muted">ALIŞ</small>
                            <div class="rate-value text-success buy-rate">{{ number_format($rate['buy'], 4) }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <small class="text-muted">SATIŞ</small>
                            <div class="rate-value text-danger sell-rate">{{ number_format($rate['sell'], 4) }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <span class="rate-change {{ $rate['change'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="fas fa-{{ $rate['change'] >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                        {{ $rate['change'] >= 0 ? '+' : '' }}{{ number_format($rate['change'], 4) }}
                        ({{ $rate['change_percent'] >= 0 ? '+' : '' }}{{ number_format($rate['change_percent'], 2) }}%)
                    </span>
                </div>
                
                @if(isset($positions[$code]))
                <div class="mt-3 pt-3" style="border-top: 1px solid #e2e8f0;">
                    <small class="text-muted">Pozisyonunuz</small>
                    <div style="font-weight: 600; color: #667eea;">
                        {{ number_format($positions[$code]['amount'], 2) }} {{ $code }}
                    </div>
                    <small class="text-muted">
                        ≈ ₺{{ number_format($positions[$code]['tl_value'], 0, ',', '.') }}
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Döviz Grafikleri -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area me-2"></i>
                    Döviz Kuru Trendleri
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active" onclick="changeChartPeriod('1D')">1G</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changeChartPeriod('1W')">1H</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changeChartPeriod('1M')">1A</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="changeChartPeriod('3M')">3A</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="currencyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pozisyon Özeti ve Son İşlemler -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-wallet me-2"></i>
                    Döviz Pozisyonları
                </h5>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-download me-1"></i>
                    Rapor Al
                </button>
            </div>
            <div class="card-body">
                @foreach($positions as $code => $position)
                <div class="position-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $code }}</h6>
                            <div class="text-muted small">{{ $position['accounts'] }} hesapta</div>
                        </div>
                        <div class="text-end">
                            <div style="font-weight: 600; font-size: 1.1rem;">
                                {{ number_format($position['amount'], 2) }}
                            </div>
                            <div class="text-muted small">
                                ≈ ₺{{ number_format($position['tl_value'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar" style="width: {{ $totalPositionTL > 0 ? ($position['tl_value'] / $totalPositionTL) * 100 : 0 }}%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Döviz Hesaplayıcı
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Çevrilecek Miktar</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="fromAmount" placeholder="0.00" oninput="calculateCurrency()">
                            <select class="form-select" id="fromCurrency" onchange="calculateCurrency()">
                                <option value="TRY">TRY</option>
                                @foreach($exchangeRates as $code => $rate)
                                <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hedef Para Birimi</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="toAmount" placeholder="0.00" readonly>
                            <select class="form-select" id="toCurrency" onchange="calculateCurrency()">
                                @foreach($exchangeRates as $code => $rate)
                                <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                                <option value="TRY">TRY</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-outline-secondary btn-sm" onclick="swapCurrencies()">
                        <i class="fas fa-exchange-alt"></i>
                    </button>
                </div>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">Kullanılan Kur:</small>
                    <div id="usedRate" class="fw-bold">-</div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Son Döviz İşlemleri
                </h5>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-list me-1"></i>
                    Tümünü Görüntüle
                </button>
            </div>
            <div class="card-body">
                @forelse($recentTransactions as $transaction)
                <div class="transaction-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $transaction['type'] }}</h6>
                            <div class="text-muted small">{{ $transaction['account'] }}</div>
                            <div class="text-muted small">{{ $transaction['date'] }}</div>
                        </div>
                        <div class="text-end">
                            <div style="font-weight: 600;">
                                {{ number_format($transaction['amount']) }} {{ $transaction['currency'] }}
                            </div>
                            <div class="text-muted small">
                                Kur: {{ number_format($transaction['rate'], 4) }}
                            </div>
                            <div style="color: #667eea; font-weight: 600;">
                                ₺{{ number_format($transaction['tl_amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Henüz döviz işlemi bulunmuyor</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Döviz İşlem Modalı -->
<div class="modal fade" id="exchangeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Döviz İşlemi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">İşlem Türü</label>
                            <select class="form-select">
                                <option>Döviz Alış</option>
                                <option>Döviz Satış</option>
                                <option>Döviz Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Döviz Cinsi</label>
                            <select class="form-select">
                                @foreach($exchangeRates as $code => $rate)
                                <option value="{{ $code }}">{{ $code }} - {{ $rate['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Miktar</label>
                            <input type="number" class="form-control" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kur</label>
                            <input type="number" class="form-control" step="0.0001" placeholder="0.0000">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Hesap</label>
                        <select class="form-select">
                            <option>Ziraat Bankası USD</option>
                            <option>İş Bankası EUR</option>
                            <option>Garanti USD</option>
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-currency">İşlemi Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- Kur Alarmı Modalı -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-bell me-2"></i>
                    Kur Alarmı Oluştur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Döviz Cinsi</label>
                        <select class="form-select">
                            @foreach($exchangeRates as $code => $rate)
                            <option value="{{ $code }}">{{ $code }} - {{ $rate['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Alarm Türü</label>
                            <select class="form-select">
                                <option>Kur Yükseldiğinde</option>
                                <option>Kur Düştüğünde</option>
                                <option>Hedef Kura Ulaştığında</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hedef Kur</label>
                            <input type="number" class="form-control" step="0.0001" placeholder="0.0000">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Bildirim Türü</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                            <label class="form-check-label" for="emailNotif">
                                E-posta Bildirimi
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="smsNotif">
                            <label class="form-check-label" for="smsNotif">
                                SMS Bildirimi
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pushNotif" checked>
                            <label class="form-check-label" for="pushNotif">
                                Push Bildirimi
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Açıklama (İsteğe Bağlı)</label>
                        <textarea class="form-control" rows="2" placeholder="Alarm için kısa bir açıklama..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-currency">Alarmı Oluştur</button>
            </div>
        </div>
    </div>
</div>

<!-- Yenileme Butonu -->
<button class="refresh-btn" onclick="refreshRates()" title="Kurları Yenile">
    <i class="fas fa-sync-alt"></i>
</button>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function refreshRates() {
    // Yenileme animasyonu
    const refreshBtn = document.querySelector('.refresh-btn i');
    refreshBtn.style.animation = 'spin 1s linear infinite';
    
    // Simüle edilmiş API çağrısı
    setTimeout(() => {
        refreshBtn.style.animation = 'none';
        
        // Toast bildirim
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.style.cssText = 'position: fixed; bottom: 100px; right: 2rem; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>
                    Döviz kurları güncellendi
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
        
        // Sayfayı yenile (gerçek uygulamada AJAX ile kurlar güncellenebilir)
        location.reload();
    }, 1000);
}

// CSS animasyonu için style bölümüne taşındı

// Döviz kurları (JavaScript için)
const exchangeRates = @json($exchangeRates);

// Döviz hesaplayıcı fonksiyonları
function calculateCurrency() {
    const fromAmount = parseFloat(document.getElementById('fromAmount').value) || 0;
    const fromCurrency = document.getElementById('fromCurrency').value;
    const toCurrency = document.getElementById('toCurrency').value;
    
    if (fromAmount === 0) {
        document.getElementById('toAmount').value = '';
        document.getElementById('usedRate').textContent = '-';
        return;
    }
    
    let rate = 1;
    let rateText = '';
    
    if (fromCurrency === 'TRY' && toCurrency !== 'TRY') {
        // TRY'den başka para birimine
        rate = 1 / exchangeRates[toCurrency].buy;
        rateText = `1 TRY = ${rate.toFixed(4)} ${toCurrency}`;
    } else if (fromCurrency !== 'TRY' && toCurrency === 'TRY') {
        // Başka para biriminden TRY'ye
        rate = exchangeRates[fromCurrency].sell;
        rateText = `1 ${fromCurrency} = ${rate.toFixed(4)} TRY`;
    } else if (fromCurrency !== 'TRY' && toCurrency !== 'TRY') {
        // İki farklı para birimi arası
        const fromRate = exchangeRates[fromCurrency].sell;
        const toRate = exchangeRates[toCurrency].buy;
        rate = fromRate / toRate;
        rateText = `1 ${fromCurrency} = ${rate.toFixed(4)} ${toCurrency}`;
    } else if (fromCurrency === toCurrency) {
        // Aynı para birimi
        rate = 1;
        rateText = `1 ${fromCurrency} = 1 ${toCurrency}`;
    }
    
    const result = fromAmount * rate;
    document.getElementById('toAmount').value = result.toFixed(2);
    document.getElementById('usedRate').textContent = rateText;
}

function swapCurrencies() {
    const fromCurrency = document.getElementById('fromCurrency');
    const toCurrency = document.getElementById('toCurrency');
    const fromAmount = document.getElementById('fromAmount');
    const toAmount = document.getElementById('toAmount');
    
    // Para birimlerini değiştir
    const tempCurrency = fromCurrency.value;
    fromCurrency.value = toCurrency.value;
    toCurrency.value = tempCurrency;
    
    // Miktarları değiştir
    const tempAmount = fromAmount.value;
    fromAmount.value = toAmount.value;
    toAmount.value = tempAmount;
    
    // Yeniden hesapla
    calculateCurrency();
}

// Döviz Grafik Verileri
const chartData = {
    labels: ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
    datasets: [{
        label: 'USD/TRY',
        data: [34.12, 34.18, 34.15, 34.22, 34.19, 34.21, 34.18, 34.21],
        borderColor: '#667eea',
        backgroundColor: 'rgba(102, 126, 234, 0.1)',
        tension: 0.4,
        fill: true
    }, {
        label: 'EUR/TRY',
        data: [37.05, 37.12, 37.08, 37.15, 37.11, 37.14, 37.09, 37.12],
        borderColor: '#f093fb',
        backgroundColor: 'rgba(240, 147, 251, 0.1)',
        tension: 0.4,
        fill: true
    }, {
        label: 'GBP/TRY',
        data: [43.75, 43.82, 43.78, 43.88, 43.83, 43.87, 43.81, 43.85],
        borderColor: '#4facfe',
        backgroundColor: 'rgba(79, 172, 254, 0.1)',
        tension: 0.4,
        fill: true
    }]
};

// Chart.js Konfigürasyonu
const ctx = document.getElementById('currencyChart').getContext('2d');
const currencyChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: '#667eea',
                borderWidth: 1
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Zaman'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            y: {
                display: true,
                title: {
                    display: true,
                    text: 'Kur (TRY)'
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});

// Grafik periyodu değiştirme
function changeChartPeriod(period) {
    // Aktif buton stilini güncelle
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    let newLabels = [];
    let newData = {};
    
    switch(period) {
        case '1D':
            newLabels = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
            break;
        case '1W':
            newLabels = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
            break;
        case '1M':
            newLabels = ['Hft 1', 'Hft 2', 'Hft 3', 'Hft 4'];
            break;
        case '3M':
            newLabels = ['Ock', 'Şub', 'Mar'];
            break;
    }
    
    // Grafik verilerini güncelle
    currencyChart.data.labels = newLabels;
    currencyChart.update();
}

// Real-time kur güncellemeleri simülasyonu
function simulateRateUpdates() {
    const currencies = Object.keys(exchangeRates);
    
    currencies.forEach(currency => {
        // Rastgele değişim (-0.5% ile +0.5% arası)
        const changePercent = (Math.random() - 0.5) * 1;
        const currentBuy = exchangeRates[currency].buy;
        const currentSell = exchangeRates[currency].sell;
        
        // Yeni kurları hesapla
        const newBuy = currentBuy * (1 + changePercent / 100);
        const newSell = currentSell * (1 + changePercent / 100);
        
        // DOM'u güncelle
        const buyElement = document.querySelector(`[data-currency="${currency}"] .buy-rate`);
        const sellElement = document.querySelector(`[data-currency="${currency}"] .sell-rate`);
        
        if (buyElement && sellElement) {
            // Animasyonlu güncelleme
            buyElement.style.transition = 'all 0.5s ease';
            sellElement.style.transition = 'all 0.5s ease';
            
            if (changePercent > 0) {
                buyElement.style.color = '#10b981';
                sellElement.style.color = '#10b981';
            } else {
                buyElement.style.color = '#ef4444';
                sellElement.style.color = '#ef4444';
            }
            
            buyElement.textContent = newBuy.toFixed(4);
            sellElement.textContent = newSell.toFixed(4);
            
            // Rengi geri döndür
            setTimeout(() => {
                buyElement.style.color = '';
                sellElement.style.color = '';
            }, 2000);
        }
        
        // Verileri güncelle
        exchangeRates[currency].buy = newBuy;
        exchangeRates[currency].sell = newSell;
    });
}

// Sayfa yüklenme efektleri
document.addEventListener('DOMContentLoaded', function() {
    // Kartları sırayla animasyonla göster
    const cards = document.querySelectorAll('.currency-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
    
    // İlk hesaplayıcı değerlerini ayarla
    document.getElementById('fromCurrency').value = 'USD';
    document.getElementById('toCurrency').value = 'TRY';
});

// Real-time güncellemeler (30 saniyede bir)
setInterval(simulateRateUpdates, 30000);

// Otomatik güncelleme (5 dakikada bir - tam sayfa yenileme)
setInterval(() => {
    console.log('Sayfa otomatik güncelleniyor...');
    // location.reload(); // Gerçek uygulamada AJAX ile yapılabilir
}, 300000); // 5 dakika
</script>
@endpush
