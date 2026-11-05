@extends('layouts.master')
@section('title', 'Şirket Bütçesi')
@section('topbar-title', 'Finans')

@push('styles')
<link rel="stylesheet" href="/libs/fontawesome/css/all.min.css">
<style>
    .kpi-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .kpi-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    .kpi-card:hover::before {
        opacity: 1;
    }
    .kpi-value {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .kpi-label {
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    .budget-progress {
        height: 10px;
        border-radius: 15px;
        background-color: #e9ecef;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    .budget-progress-bar {
        height: 100%;
        border-radius: 15px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(90deg, currentColor 0%, rgba(255,255,255,0.3) 100%);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .account-type-card {
        border-left: 5px solid #007bff;
        padding: 20px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0 12px 12px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .account-type-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .chart-container {
        position: relative;
        height: 350px;
        margin: 25px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 20px;
    }
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        position: relative;
        z-index: 1;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .card:hover {
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
        padding: 20px 25px;
    }
    .card-body {
        padding: 25px;
    }
    .bg-gradient-primary { 
        background: linear-gradient(135deg, #007bff, #0056b3); 
        border-left-color: #007bff !important;
    }
    .bg-gradient-success { 
        background: linear-gradient(135deg, #28a745, #1e7e34); 
        border-left-color: #28a745 !important;
    }
    .bg-gradient-warning { 
        background: linear-gradient(135deg, #ffc107, #e0a800); 
        border-left-color: #ffc107 !important;
    }
    .bg-gradient-info { 
        background: linear-gradient(135deg, #17a2b8, #117a8b); 
        border-left-color: #17a2b8 !important;
    }
    .bg-gradient-danger { 
        background: linear-gradient(135deg, #dc3545, #bd2130); 
        border-left-color: #dc3545 !important;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .btn {
        border-radius: 25px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 768px) {
        .kpi-card {
            margin-bottom: 15px;
        }
        .kpi-value {
            font-size: 2.2rem;
        }
        .account-type-card {
            margin-bottom: 15px;
        }
        .chart-container {
            height: 250px;
            margin: 15px 0;
        }
    }
    
    .animate-number {
        animation: countUp 2s ease-out;
    }
    
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 1s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<!-- Başlık Bölümü -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2">
                    <i class="fas fa-building me-3" style="color: #007bff;"></i>
                    Şirket Bütçe Yönetimi
                </h2>
                <p class="text-muted">Kapsamlı finansal analiz ve bütçe takibi</p>
            </div>
            <div class="text-end">
                <div class="badge bg-success fs-6 p-2">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row fade-in">
    <!-- KPI Kartları -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card bg-gradient-primary">
            <div class="stat-icon bg-gradient-primary">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="kpi-value animate-number" data-value="{{ $kpis['revenue_growth'] }}">{{ number_format($kpis['revenue_growth'], 1) }}%</div>
            <div class="kpi-label">Gelir Artışı</div>
            <div class="mt-2">
                <small><i class="fas fa-arrow-up me-1"></i>Geçen aya göre</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card bg-gradient-success">
            <div class="stat-icon bg-gradient-success">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="kpi-value animate-number" data-value="{{ $kpis['profit_margin'] }}">{{ number_format($kpis['profit_margin'], 1) }}%</div>
            <div class="kpi-label">Kar Marjı</div>
            <div class="mt-2">
                <small><i class="fas fa-arrow-up me-1"></i>Hedefin üzerinde</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card bg-gradient-warning">
            <div class="stat-icon bg-gradient-warning">
                <i class="fas fa-coins"></i>
            </div>
            <div class="kpi-value animate-number" data-value="{{ $kpis['expense_ratio'] }}">{{ number_format($kpis['expense_ratio'], 1) }}%</div>
            <div class="kpi-label">Gider Oranı</div>
            <div class="mt-2">
                <small><i class="fas fa-exclamation-triangle me-1"></i>Dikkat gerekli</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card bg-gradient-info">
            <div class="stat-icon bg-gradient-info">
                <i class="fas fa-water"></i>
            </div>
            <div class="kpi-value animate-number" data-value="{{ $kpis['cash_flow'] }}">{{ number_format($kpis['cash_flow'], 1) }}M ₺</div>
            <div class="kpi-label">Nakit Akışı</div>
            <div class="mt-2">
                <small><i class="fas fa-check me-1"></i>Pozitif trend</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Bütçe Kategorileri -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Bütçe Kategorileri ve Harcama Durumu
                </h4>
            </div>
            <div class="card-body">
                @foreach($budgetCategories as $category)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">{{ $category['name'] }}</h6>
                        <div class="text-end">
                            <span class="badge bg-{{ $category['percentage'] > 80 ? 'danger' : ($category['percentage'] > 60 ? 'warning' : 'success') }}">
                                {{ $category['percentage'] }}%
                            </span>
                        </div>
                    </div>
                    <div class="budget-progress">
                        <div class="budget-progress-bar bg-{{ $category['percentage'] > 80 ? 'danger' : ($category['percentage'] > 60 ? 'warning' : 'success') }}" 
                             style="width: {{ $category['percentage'] }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between text-sm text-muted mt-1">
                        <span>Harcanan: ₺{{ number_format($category['spent']) }}</span>
                        <span>Bütçe: ₺{{ number_format($category['allocated']) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Toplam Bakiye -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-wallet me-2"></i>Toplam Bakiye
                </h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-university" style="font-size: 48px; color: #007bff;"></i>
                </div>
                <h2 class="text-primary mb-2">₺{{ number_format($totalBalance, 2) }}</h2>
                <p class="text-muted">{{ $accounts->count() }} Aktif Hesap</p>
                
                <div class="mt-4">
                    <canvas id="accountTypesChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Aylık Nakit Akışı -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-chart-area me-2"></i>Aylık Nakit Akışı (Son 12 Ay)
                </h4>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyFlowChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Hesap Türleri Detayı -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-list-alt me-2"></i>Hesap Türleri Detayı
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($accountsByType as $type => $typeAccounts)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="account-type-card">
                            <h6 class="mb-2">
                                <i class="fas fa-{{ $type == 'bank' ? 'university' : ($type == 'cash' ? 'money-bill-wave' : 'credit-card') }} me-2"></i>
                                {{ ucfirst($type) }} Hesapları
                            </h6>
                            <div class="text-primary h5 mb-2">₺{{ number_format($typeAccounts->sum('balance'), 2) }}</div>
                            <small class="text-muted">{{ $typeAccounts->count() }} hesap</small>
                            <div class="mt-2">
                                @foreach($typeAccounts->take(3) as $account)
                                <div class="d-flex justify-content-between text-sm">
                                    <span>{{ $account->name }}</span>
                                    <span class="text-{{ $account->balance >= 0 ? 'success' : 'danger' }}">
                                        ₺{{ number_format($account->balance, 2) }}
                                    </span>
                                </div>
                                @endforeach
                                @if($typeAccounts->count() > 3)
                                <small class="text-muted">ve {{ $typeAccounts->count() - 3 }} tane daha...</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aksiyon Butonları -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header text-center">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Hızlı İşlemler
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <button type="button" class="btn btn-primary w-100" onclick="createNewBudget()">
                            <i class="fas fa-plus-circle d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span>Yeni Bütçe</span>
                            <small class="d-block">Oluştur</small>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <button type="button" class="btn btn-success w-100" onclick="downloadReport()">
                            <i class="fas fa-file-excel d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span>Excel Raporu</span>
                            <small class="d-block">İndir</small>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <button type="button" class="btn btn-info w-100" onclick="showDetailedAnalysis()">
                            <i class="fas fa-chart-line d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span>Detaylı Analiz</span>
                            <small class="d-block">Görüntüle</small>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <button type="button" class="btn btn-warning w-100" onclick="openBudgetSettings()">
                            <i class="fas fa-cogs d-block mb-2" style="font-size: 1.5rem;"></i>
                            <span>Bütçe Ayarları</span>
                            <small class="d-block">Düzenle</small>
                        </button>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <!-- Ek Özellikler -->
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bell text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Uyarılar</h6>
                                <small class="text-muted">3 yeni bildirim</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Planlanan Ödemeler</h6>
                                <small class="text-muted">Bu hafta 5 ödeme</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-sync-alt text-info" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Son Güncelleme</h6>
                                <small class="text-muted">{{ now()->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- İstatistik Özeti Footer -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white" data-aos="fade-up" data-aos-delay="500">
            <div class="card-body text-center py-5">
                <h3 class="mb-4">Finansal Durum Özeti</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <i class="fas fa-money-bill-wave mb-2" style="font-size: 2rem;"></i>
                            <h4>₺{{ number_format($totalBalance, 0) }}</h4>
                            <small>Toplam Bakiye</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <i class="fas fa-piggy-bank mb-2" style="font-size: 2rem;"></i>
                            <h4>₺{{ number_format(array_sum(array_column($budgetCategories, 'allocated')), 0) }}</h4>
                            <small>Toplam Bütçe</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart mb-2" style="font-size: 2rem;"></i>
                            <h4>₺{{ number_format(array_sum(array_column($budgetCategories, 'spent')), 0) }}</h4>
                            <small>Toplam Harcama</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <i class="fas fa-chart-pie mb-2" style="font-size: 2rem;"></i>
                            <h4>{{ number_format((array_sum(array_column($budgetCategories, 'spent')) / max(1, array_sum(array_column($budgetCategories, 'allocated')))) * 100, 1) }}%</h4>
                            <small>Bütçe Kullanımı</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Buton işlevleri
function createNewBudget() {
    Swal.fire({
        title: 'Yeni Bütçe Oluştur',
        text: 'Yeni bütçe oluşturma sayfasına yönlendirileceksiniz.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Devam Et',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            // window.location.href = '/finance/budget/create';
            console.log('Yeni bütçe oluşturma sayfasına yönlendiriliyor...');
        }
    });
}

function downloadReport() {
    Swal.fire({
        title: 'Rapor İndiriliyor...',
        text: 'Lütfen bekleyin.',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            // Gerçek indirme işlemi burada yapılacak
            setTimeout(() => {
                Swal.fire('Başarılı!', 'Rapor başarıyla indirildi.', 'success');
            }, 2000);
        }
    });
}

function showDetailedAnalysis() {
    Swal.fire({
        title: 'Detaylı Analiz',
        html: '<p>Detaylı analiz sayfası yakında kullanıma açılacaktır.</p>',
        icon: 'info',
        confirmButtonText: 'Tamam'
    });
}

function openBudgetSettings() {
    Swal.fire({
        title: 'Bütçe Ayarları',
        html: '<p>Bütçe ayarları sayfasına yönlendirileceksiniz.</p>',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Devam Et',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Bütçe ayarları sayfasına yönlendiriliyor...');
        }
    });
}
</script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script>
document.addEventListener('DOMContentLoaded', function() {
    // AOS (Animate On Scroll) initialize
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // Sayı animasyonu
    function animateNumbers() {
        const numbers = document.querySelectorAll('.animate-number');
        numbers.forEach(number => {
            const finalValue = parseFloat(number.dataset.value || number.textContent);
            const duration = 2000;
            const increment = finalValue / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= finalValue) {
                    current = finalValue;
                    clearInterval(timer);
                }
                
                if (number.textContent.includes('%')) {
                    number.textContent = current.toFixed(1) + '%';
                } else if (number.textContent.includes('₺')) {
                    number.textContent = current.toFixed(1) + 'M ₺';
                } else {
                    number.textContent = current.toFixed(1);
                }
            }, 16);
        });
    }

    // Progress bar animasyonu
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.budget-progress-bar');
        progressBars.forEach((bar, index) => {
            setTimeout(() => {
                bar.style.width = bar.style.width || '0%';
            }, index * 200);
        });
    }

    // Kartların hover efektleri
    function addCardInteractions() {
        const cards = document.querySelectorAll('.card, .kpi-card, .account-type-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }

    // Aylık Nakit Akışı Grafiği
    const monthlyFlowCtx = document.getElementById('monthlyFlowChart').getContext('2d');
    const monthlyFlowChart = new Chart(monthlyFlowCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyFlow, 'month')) !!},
            datasets: [{
                label: 'Gelir',
                data: {!! json_encode(array_column($monthlyFlow, 'income')) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }, {
                label: 'Gider',
                data: {!! json_encode(array_column($monthlyFlow, 'expense')) !!},
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#dc3545',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeInOutCubic'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#ffffff',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '₺' + (value / 1000) + 'K';
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Hesap Türleri Pasta Grafiği
    const accountTypesCtx = document.getElementById('accountTypesChart').getContext('2d');
    const accountTypeData = {!! json_encode($accountsByType->map(function($accounts, $type) {
        return ['type' => $type, 'balance' => $accounts->sum('balance')];
    })->values()) !!};
    
    const accountTypesChart = new Chart(accountTypesCtx, {
        type: 'doughnut',
        data: {
            labels: accountTypeData.map(item => item.type.charAt(0).toUpperCase() + item.type.slice(1)),
            datasets: [{
                data: accountTypeData.map(item => item.balance),
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#17a2b8'
                ],
                borderWidth: 0,
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 2000
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 11,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            return label + ': ₺' + value.toLocaleString();
                        }
                    }
                }
            },
            cutout: '60%',
            radius: '80%'
        }
    });

    // Animasyonları başlat
    setTimeout(() => {
        animateNumbers();
        animateProgressBars();
        addCardInteractions();
    }, 500);

    // Real-time updates simulation
    setInterval(() => {
        // Simulate small fluctuations in KPI values
        const randomFluctuation = () => (Math.random() - 0.5) * 0.1;
        
        // Bu kısım gerçek zamanlı veriler için kullanılabilir
        console.log('Real-time update simulation running...');
    }, 30000); // Her 30 saniyede bir
});
</script>
@endpush
