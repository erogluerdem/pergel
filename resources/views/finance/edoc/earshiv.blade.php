@extends('layouts.master')
@section('title', 'E-Arşiv Yönetimi')
@section('topbar-title', 'Finans > E-Belgeler > E-Arşiv')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        border-radius: 15px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stats-card:hover::before {
        opacity: 1;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-approved {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #FF9800, #f57c00);
        color: white;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
    }
    
    .document-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
    
    .document-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }
    
    .btn-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .search-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }
    
    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .table-modern {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .table-modern thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    
    .table-modern tbody td {
        padding: 1rem;
        border-color: #f8f9fa;
        vertical-align: middle;
    }
    
    .compliance-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }
    
    .compliance-good { background: #4CAF50; }
    .compliance-warning { background: #FF9800; }
    .compliance-danger { background: #f44336; }
    
    .progress-modern {
        height: 8px;
        border-radius: 10px;
        background: #e9ecef;
        overflow: hidden;
    }
    
    .progress-bar-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        transition: width 0.6s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1" style="font-weight: 700; color: #2c3e50;">E-Arşiv Yönetim Sistemi</h2>
                    <p class="text-muted mb-0">Elektronik belgelerin arşivlenmesi ve yönetimi</p>
                </div>
                <div>
                    <button class="btn btn-primary-gradient me-2">
                        <i class="fas fa-upload me-2"></i>Yeni Belge Yükle
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-download me-2"></i>Rapor İndir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-archive"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($earshivStats['total_documents']) }}</h3>
                    <p class="mb-0 small">Toplam Belge</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="mb-1">{{ $earshivStats['pending_approval'] }}</h3>
                    <p class="mb-0 small">Onay Bekleyen</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="mb-1">{{ $earshivStats['approved_today'] }}</h3>
                    <p class="mb-0 small">Bugün Onaylanan</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="mb-1">{{ $earshivStats['archived_this_month'] }}</h3>
                    <p class="mb-0 small">Bu Ay Arşivlenen</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <h3 class="mb-1">{{ $earshivStats['storage_used'] }}</h3>
                    <p class="mb-0 small">Depolama Kullanımı</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card stats-card h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="mb-1">%{{ $earshivStats['compliance_rate'] }}</h3>
                    <p class="mb-0 small">Uyumluluk Oranı</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-container">
        <div class="row">
            <div class="col-md-3">
                <label class="form-label fw-bold">Belge Arama</label>
                <input type="text" class="form-control form-control-modern" placeholder="Belge No, Müşteri Adı...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Belge Türü</label>
                <select class="form-control form-control-modern">
                    <option value="">Tümü</option>
                    <option value="efatura">E-Fatura</option>
                    <option value="eirsaliye">E-İrsaliye</option>
                    <option value="emustahsil">E-Müstahsil</option>
                    <option value="ebilet">E-Bilet</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Durum</label>
                <select class="form-control form-control-modern">
                    <option value="">Tümü</option>
                    <option value="approved">Onaylandı</option>
                    <option value="pending">Onay Bekliyor</option>
                    <option value="rejected">Reddedildi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Başlangıç Tarihi</label>
                <input type="date" class="form-control form-control-modern">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Bitiş Tarihi</label>
                <input type="date" class="form-control form-control-modern">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary-gradient w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Documents -->
        <div class="col-xl-8 mb-4">
            <div class="card chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Son Arşivlenen Belgeler</h5>
                    <a href="#" class="text-decoration-none">Tümünü Görüntüle <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-modern table-hover">
                        <thead>
                            <tr>
                                <th>Belge No</th>
                                <th>Tür</th>
                                <th>Müşteri</th>
                                <th>Tutar</th>
                                <th>Tarih</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDocuments as $document)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $document['id'] }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $document['document_type'] }}</span>
                                </td>
                                <td>{{ $document['customer_name'] }}</td>
                                <td class="fw-bold">₺{{ number_format($document['amount'], 2) }}</td>
                                <td>{{ date('d.m.Y', strtotime($document['date'])) }}</td>
                                <td>
                                    @if($document['status'] == 'approved')
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check me-1"></i>Onaylandı
                                        </span>
                                    @elseif($document['status'] == 'pending')
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i>Beklemede
                                        </span>
                                    @else
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times me-1"></i>Reddedildi
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Görüntüle">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success" title="İndir">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" title="Paylaş">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="col-xl-4 mb-4">
            <!-- Document Type Distribution -->
            <div class="card chart-container mb-4">
                <h6 class="fw-bold mb-3">Belge Türü Dağılımı</h6>
                <canvas id="documentTypeChart" style="max-height: 250px;"></canvas>
            </div>

            <!-- Compliance Status -->
            <div class="card chart-container">
                <h6 class="fw-bold mb-3">Uyumluluk Durumu</h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="compliance-indicator compliance-good"></span>Uyumlu</span>
                        <strong>{{ number_format($complianceData['compliant']) }}</strong>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar progress-bar-modern" style="width: {{ array_sum($complianceData) > 0 ? ($complianceData['compliant'] / array_sum($complianceData)) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="compliance-indicator compliance-warning"></span>İnceleme Bekleyen</span>
                        <strong>{{ number_format($complianceData['pending_review']) }}</strong>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar" style="width: {{ array_sum($complianceData) > 0 ? ($complianceData['pending_review'] / array_sum($complianceData)) * 100 : 0 }}%; background: #FF9800;"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><span class="compliance-indicator compliance-danger"></span>Uyumsuz</span>
                        <strong>{{ number_format($complianceData['non_compliant']) }}</strong>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar" style="width: {{ array_sum($complianceData) > 0 ? ($complianceData['non_compliant'] / array_sum($complianceData)) * 100 : 0 }}%; background: #f44336;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Archive Trend -->
    <div class="row">
        <div class="col-12">
            <div class="card chart-container">
                <h5 class="fw-bold mb-3">Aylık Arşivleme Trendi</h5>
                <canvas id="monthlyArchiveChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Document Type Distribution Chart
    const docTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
    new Chart(docTypeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($documentTypeDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($documentTypeDistribution)) !!},
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Monthly Archive Trend Chart
    const monthlyCtx = document.getElementById('monthlyArchiveChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyArchiveData['months']) !!},
            datasets: [{
                label: 'Arşivlenen Belgeler',
                data: {!! json_encode($monthlyArchiveData['documents']) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }, {
                label: 'Depolama (GB)',
                data: {!! json_encode($monthlyArchiveData['storage']) !!},
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#764ba2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Belge Sayısı'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Depolama (GB)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 10,
                    displayColors: true
                }
            }
        }
    });

    // Add some interactive features
    document.addEventListener('DOMContentLoaded', function() {
        // Animate statistics cards on page load
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.transform = 'translateY(0)';
                card.style.opacity = '1';
            }, index * 100);
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
