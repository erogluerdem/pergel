@extends('layouts.master')

@section('title', 'Trend Detayı - ' . ($trend->project->name ?? 'N/A'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-chart-bar"></i>
                {{ $trend->project->name ?? 'N/A' }} - {{ ucfirst($trend->period_type) }} Trend
            </h2>
            <small class="text-muted">Periyod: {{ $trend->period_start->format('d.m.Y') }} - {{ $trend->period_end->format('d.m.Y') }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.trends.edit', $trend) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Düzenle
            </a>
            <a href="{{ route('finance.trends.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Health Score -->
            <div class="card bg-light border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Sağlık Puanı</p>
                            @php
                                $score = $trend->health_score ?? 0;
                                $color = $score >= 75 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                            @endphp
                            <h2 class="text-{{ $color }}">{{ round($score, 1) }}/100</h2>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $score }}%;"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Net Nakit Akışı</p>
                            <h2 class="text-{{ $trend->net_cash_flow >= 0 ? 'success' : 'danger' }}">
                                ₺{{ number_format($trend->net_cash_flow, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <label class="text-muted small">Ödeme Zamanlaması</label>
                            <h4>{{ round($trend->payment_timeliness, 1) }}%</h4>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ min($trend->payment_timeliness, 100) }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <label class="text-muted small">Tahsilat Oranı</label>
                            <h4>{{ round($trend->collection_ratio, 1) }}%</h4>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($trend->collection_ratio, 100) }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Metrics -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Detaylı Metrikleri</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Gider Oranı</td>
                                <td class="text-end">{{ round($trend->expense_ratio, 2) }}%</td>
                            </tr>
                            <tr>
                                <td>Bütçe Varyansı</td>
                                <td class="text-end text-{{ $trend->budget_variance >= 0 ? 'danger' : 'success' }}">
                                    {{ round($trend->budget_variance, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Büyüme Oranı</td>
                                <td class="text-end text-{{ $trend->growth_rate >= 0 ? 'success' : 'danger' }}">
                                    {{ round($trend->growth_rate, 2) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Toplam Gelir</td>
                                <td class="text-end fw-500">₺{{ number_format($trend->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Toplam Gider</td>
                                <td class="text-end fw-500">₺{{ number_format($trend->total_expenses, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Analysis -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Analiz Notları</h5>
                </div>
                <div class="card-body">
                    <p>
                        @if($trend->notes)
                            {{ $trend->notes }}
                        @else
                            <em class="text-muted">Not bulunmamaktadır</em>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.trends.edit', $trend) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Yazdır
                        </button>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fas fa-download"></i> PDF İndir
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Özet Bilgiler</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <label class="text-muted">Proje</label>
                        <p class="fw-500">{{ $trend->project->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Periyod Türü</label>
                        <p class="fw-500">{{ ucfirst($trend->period_type) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Durum</label>
                        <p class="fw-500">
                            <span class="badge bg-{{ $trend->status === 'analyzed' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($trend->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Analiz Tarihi</label>
                        <p class="fw-500">{{ $trend->updated_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Health Status -->
            <div class="card border-0 shadow-sm bg-{{ $color }}-light">
                <div class="card-body">
                    <h6 class="mb-2">Finansal Sağlık Durumu</h6>
                    @if($score >= 75)
                        <p class="small mb-0">✅ <strong>İYİ</strong> - Finansal durum sağlıklı</p>
                    @elseif($score >= 50)
                        <p class="small mb-0">⚠️ <strong>UYARI</strong> - Bazı göstergeler izlensin</p>
                    @else
                        <p class="small mb-0">❌ <strong>KRİTİK</strong> - Acil iyileştirme gerekli</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
