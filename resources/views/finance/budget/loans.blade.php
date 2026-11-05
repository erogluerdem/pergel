@extends('layouts.master')
@section('title', 'Krediler ve Taksitler')
@section('topbar-title', 'Finans - Finansman & Bütçe')

@push('styles')
<link rel="stylesheet" href="/libs/fontawesome/css/all.min.css">
<style>
    .loan-card {
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .loan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }
    .loan-card.active {
        border-left: 5px solid #28a745;
    }
    .loan-card.completed {
        border-left: 5px solid #6c757d;
        opacity: 0.8;
    }
    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .summary-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .summary-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .progress-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 0 auto;
    }
    .payment-calendar-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    .payment-calendar-item:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        transform: translateX(5px);
    }
    .risk-indicator {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .risk-low { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745; }
    .risk-medium { background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-left: 4px solid #ffc107; }
    .risk-high { background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-left: 4px solid #dc3545; }
    .stat-icon {
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
    .loan-status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-active { background-color: #d4edda; color: #155724; }
    .status-completed { background-color: #d1ecf1; color: #0c5460; }
    .chart-container {
        position: relative;
        height: 300px;
        margin: 20px 0;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    }
    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
        border-radius: 15px 15px 0 0 !important;
        padding: 20px 25px;
    }
    .btn-action {
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
                    <i class="fas fa-university me-3" style="color: #007bff;"></i>
                    Krediler ve Taksitler
                </h2>
                <p class="text-muted">Detaylı kredi yönetimi ve ödeme takibi</p>
            </div>
            <div class="text-end">
                <button class="btn btn-primary btn-action me-2" onclick="addNewLoan()">
                    <i class="fas fa-plus me-1"></i>Yeni Kredi
                </button>
                <button class="btn btn-success btn-action" onclick="exportLoans()">
                    <i class="fas fa-download me-1"></i>Rapor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Özet Kartları -->
<div class="row fade-in">
    <div class="col-xl-3 col-md-6">
        <div class="summary-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="summary-value">₺{{ number_format($summary['total_loan_amount'] / 1000000, 1) }}M</div>
            <div class="summary-label">Toplam Kredi Tutarı</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="summary-card" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
            <div class="stat-icon bg-success">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="summary-value">₺{{ number_format($summary['total_remaining'] / 1000000, 1) }}M</div>
            <div class="summary-label">Kalan Borç</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="summary-card" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
            <div class="stat-icon bg-warning">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="summary-value">₺{{ number_format($summary['monthly_total_payment'] / 1000, 0) }}K</div>
            <div class="summary-label">Aylık Toplam Ödeme</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="summary-card" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
            <div class="stat-icon bg-info">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="summary-value">{{ $summary['active_loans_count'] }}</div>
            <div class="summary-label">Aktif Kredi Sayısı</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Kredi Listesi -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Kredi Portföyü
                </h4>
            </div>
            <div class="card-body">
                @foreach($loans as $loan)
                <div class="loan-card {{ $loan['status'] }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-university text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <h6 class="mb-1">{{ $loan['bank_name'] }}</h6>
                                        <small class="text-muted">{{ $loan['loan_type'] }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <div class="progress-circle">
                                        <svg width="100" height="100">
                                            <circle cx="50" cy="50" r="40" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                            <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $loan['status'] == 'active' ? '#28a745' : '#6c757d' }}" 
                                                    stroke-width="8" stroke-dasharray="{{ 2 * pi() * 40 }}" 
                                                    stroke-dashoffset="{{ 2 * pi() * 40 * (1 - ($loan['installment_count'] > 0 ? ($loan['paid_installments'] / $loan['installment_count']) : 0)) }}"
                                                    transform="rotate(-90 50 50)"/>
                                        </svg>
                                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                                            <div style="font-size: 0.8rem; font-weight: bold;">
                                                {{ $loan['installment_count'] > 0 ? round(($loan['paid_installments'] / $loan['installment_count']) * 100) : 0 }}%
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $loan['paid_installments'] }}/{{ $loan['installment_count'] }} Taksit</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <div class="mb-2">
                                        <strong>Kalan Borç:</strong>
                                        <span class="text-danger">₺{{ number_format($loan['remaining_amount']) }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Aylık Ödeme:</strong>
                                        <span class="text-info">₺{{ number_format($loan['monthly_payment']) }}</span>
                                    </div>
                                    <div>
                                        <strong>Faiz Oranı:</strong>
                                        <span class="text-warning">%{{ $loan['interest_rate'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <div class="loan-status-badge status-{{ $loan['status'] }}">
                                        {{ $loan['status'] == 'active' ? 'Aktif' : 'Tamamlandı' }}
                                    </div>
                                    @if($loan['next_payment'])
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-clock me-1"></i>{{ date('d.m.Y', strtotime($loan['next_payment'])) }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewLoanDetails({{ $loan['id'] }})">
                                            <i class="fas fa-eye me-2"></i>Detaylar
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="makePayment({{ $loan['id'] }})">
                                            <i class="fas fa-credit-card me-2"></i>Ödeme Yap
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="viewSchedule({{ $loan['id'] }})">
                                            <i class="fas fa-calendar me-2"></i>Ödeme Planı
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Risk Analizi -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Risk Analizi
                </h4>
            </div>
            <div class="card-body">
                <div class="risk-indicator {{ $riskAnalysis['debt_to_equity_ratio'] > 50 ? 'risk-high' : ($riskAnalysis['debt_to_equity_ratio'] > 30 ? 'risk-medium' : 'risk-low') }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Borç/Özkaynak Oranı</h6>
                            <small class="text-muted">Finansal kaldıraç seviyesi</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">%{{ $riskAnalysis['debt_to_equity_ratio'] }}</h5>
                        </div>
                    </div>
                </div>

                <div class="risk-indicator risk-low">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Borç Servisi Karşılama</h6>
                            <small class="text-muted">Ödeme gücü göstergesi</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">{{ $riskAnalysis['debt_service_coverage'] }}</h5>
                        </div>
                    </div>
                </div>

                <div class="risk-indicator risk-low">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Faiz Karşılama Oranı</h6>
                            <small class="text-muted">Faiz ödeme kapasitesi</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">{{ $riskAnalysis['interest_coverage_ratio'] }}</h5>
                        </div>
                    </div>
                </div>

                <div class="risk-indicator {{ $riskAnalysis['credit_utilization'] > 80 ? 'risk-high' : ($riskAnalysis['credit_utilization'] > 60 ? 'risk-medium' : 'risk-low') }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Kredi Kullanım Oranı</h6>
                            <small class="text-muted">Mevcut limit kullanımı</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">%{{ $riskAnalysis['credit_utilization'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ödeme Takvimi -->
<div class="row mt-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Aylık Ödeme Takvimi
                </h4>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="paymentCalendarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-calendar-check me-2"></i>Yaklaşan Ödemeler
                </h4>
            </div>
            <div class="card-body">
                @foreach(collect($paymentCalendar)->take(6) as $payment)
                <div class="payment-calendar-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $payment['month'] }}</h6>
                            <small class="text-muted">
                                Anapara: ₺{{ number_format($payment['principal']) }} | 
                                Faiz: ₺{{ number_format($payment['interest']) }}
                            </small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0 text-primary">₺{{ number_format($payment['total_payment']) }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ödeme Takvimi Grafiği
    const paymentCtx = document.getElementById('paymentCalendarChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($paymentCalendar, 'month')) !!},
            datasets: [{
                label: 'Anapara',
                data: {!! json_encode(array_column($paymentCalendar, 'principal')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
                borderWidth: 2
            }, {
                label: 'Faiz',
                data: {!! json_encode(array_column($paymentCalendar, 'interest')) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: '#dc3545',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₺' + (value / 1000) + 'K';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₺' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

// İşlev fonksiyonları
function addNewLoan() {
    Swal.fire({
        title: 'Yeni Kredi Ekle',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Banka Adı</label>
                    <input type="text" class="form-control" id="bankName" placeholder="Banka adını giriniz">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kredi Türü</label>
                    <select class="form-control" id="loanType">
                        <option>İşletme Kredisi</option>
                        <option>Yatırım Kredisi</option>
                        <option>Nakdi Kredi</option>
                        <option>Araç Kredisi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kredi Tutarı (₺)</label>
                    <input type="number" class="form-control" id="loanAmount" placeholder="0">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Ekle',
        cancelButtonText: 'İptal',
        preConfirm: () => {
            const bankName = document.getElementById('bankName').value;
            const loanType = document.getElementById('loanType').value;
            const loanAmount = document.getElementById('loanAmount').value;
            
            if (!bankName || !loanAmount) {
                Swal.showValidationMessage('Lütfen tüm alanları doldurunuz');
                return false;
            }
            return { bankName, loanType, loanAmount };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Başarılı!', 'Yeni kredi eklendi.', 'success');
        }
    });
}

function exportLoans() {
    Swal.fire({
        title: 'Rapor İndiriliyor...',
        text: 'Kredi raporu hazırlanıyor.',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                Swal.fire('Başarılı!', 'Kredi raporu başarıyla indirildi.', 'success');
            }, 2000);
        }
    });
}

function viewLoanDetails(loanId) {
    Swal.fire({
        title: 'Kredi Detayları',
        html: '<p>Kredi ID: ' + loanId + '</p><p>Detaylı bilgiler yakında...</p>',
        icon: 'info'
    });
}

function makePayment(loanId) {
    Swal.fire({
        title: 'Ödeme Yap',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Ödeme Tutarı (₺)</label>
                    <input type="number" class="form-control" id="paymentAmount" placeholder="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ödeme Tipi</label>
                    <select class="form-control" id="paymentType">
                        <option>Normal Taksit</option>
                        <option>Erken Ödeme</option>
                        <option>Kısmi Ödeme</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Öde',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Başarılı!', 'Ödeme işlemi tamamlandı.', 'success');
        }
    });
}

function viewSchedule(loanId) {
    Swal.fire({
        title: 'Ödeme Planı',
        html: '<p>Kredi ID: ' + loanId + '</p><p>Ödeme planı yakında...</p>',
        icon: 'info',
        width: '800px'
    });
}
</script>
@endpush
