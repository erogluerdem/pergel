@extends('layouts.master')

@section('title', 'Trend Analizi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-chart-bar text-info"></i>
                Finansal Trend Analizi
            </h2>
            <small class="text-muted">Zaman içinde finansal göstergeler ve performans metrikleri</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.trends.index') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Trend
            </a>
        </div>
    </div>

    <!-- Health Score Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Ortalama Sağlık Puanı</p>
                            <h3 class="mb-0">{{ round($stats['avg_health_score'] ?? 0, 1) }}/100</h3>
                        </div>
                        <div class="icon-bg bg-success rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-heart text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Toplam Trends</p>
                            <h3 class="mb-0">{{ $stats['total_trends'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-primary rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-trend-up text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pozitif Trends</p>
                            <h3 class="mb-0 text-success">{{ $stats['positive_trends'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-success rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-angle-up text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Negatif Trends</p>
                            <h3 class="mb-0 text-danger">{{ $stats['negative_trends'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-danger rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-angle-down text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Trends List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Finans Trend Analizleri</h5>
                </div>
                <div class="card-body">
                    @if($trendRecords && $trendRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th>Proje</th>
                                        <th>Periyod</th>
                                        <th>Sağlık Puanı</th>
                                        <th>Net Akış</th>
                                        <th>Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trendRecords as $trend)
                                        <tr>
                                            <td class="fw-500">{{ $trend->project->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ ucfirst($trend->period_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $score = $trend->health_score ?? 0;
                                                    $color = $score >= 75 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ round($score, 1) }}/100
                                                </span>
                                            </td>
                                            <td class="text-{{ $trend->net_cash_flow >= 0 ? 'success' : 'danger' }}">
                                                ₺{{ number_format($trend->net_cash_flow, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $trend->status === 'analyzed' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($trend->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('finance.trends.show', $trend) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> Henüz trend analizi bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Insights -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Hızlı İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.trends.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Yeni Trend Oluştur
                        </a>
                        <a href="{{ route('finance.trends.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Tüm Trendleri Görüntüle
                        </a>
                        <a href="{{ route('finance.trends.analyze', ['project_id' => $project->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-bar"></i> Detaylı Analiz
                        </a>
                        <a href="{{ route('finance.trends.export', ['project_id' => $project->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-download"></i> Excel'e Aktar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Performance Indicators -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Kilit Performans Göstergeleri</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ödeme Zamanlaması</span>
                            <strong>{{ round($stats['avg_payment_timeliness'] ?? 0, 1) }}%</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ min($stats['avg_payment_timeliness'] ?? 0, 100) }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tahsilat Oranı</span>
                            <strong>{{ round($stats['avg_collection_ratio'] ?? 0, 1) }}%</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($stats['avg_collection_ratio'] ?? 0, 100) }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Gider Oranı</span>
                            <strong>{{ round($stats['avg_expense_ratio'] ?? 0, 1) }}%</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($stats['avg_expense_ratio'] ?? 0, 100) }}%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Bütçe Varyansı</span>
                            <strong>{{ round($stats['avg_budget_variance'] ?? 0, 1) }}%</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(abs($stats['avg_budget_variance'] ?? 0), 100) }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trend dashboard specific scripts
    });
</script>
@endsection
