@extends('layouts.master')

@section('title', 'Finansal Risk Analizi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-exclamation-triangle text-danger"></i>
                Finansal Risk Analizi
            </h2>
            <small class="text-muted">Proje ve şirket düzeyinde finansal risk değerlendirmesi</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.risks.index') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Risk
            </a>
        </div>
    </div>

    <!-- Risk Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Kritik Riskler</p>
                            <h3 class="mb-0 text-danger">{{ $stats['critical_count'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-danger rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-skull-crossbones text-white" style="font-size: 24px;"></i>
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
                            <p class="text-muted mb-1 small">Yüksek Riskler</p>
                            <h3 class="mb-0 text-warning">{{ $stats['high_count'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-warning rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exclamation text-white" style="font-size: 24px;"></i>
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
                            <p class="text-muted mb-1 small">Orta Riskler</p>
                            <h3 class="mb-0 text-info">{{ $stats['medium_count'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-info rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-circle text-white" style="font-size: 24px;"></i>
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
                            <p class="text-muted mb-1 small">Toplam Riskler</p>
                            <h3 class="mb-0">{{ $stats['total_risks'] ?? 0 }}</h3>
                        </div>
                        <div class="icon-bg bg-secondary rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-pie text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Critical Risks -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">🔴 Kritik Riskler (Acil İşlem Gerekli)</h5>
                </div>
                <div class="card-body">
                    @if($criticalRisks && $criticalRisks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th>Risk Türü</th>
                                        <th>Proje</th>
                                        <th>Risk Puanı</th>
                                        <th>Olasılık</th>
                                        <th>Etki</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalRisks as $risk)
                                        <tr class="table-danger opacity-50">
                                            <td class="fw-500">{{ ucfirst(str_replace('_', ' ', $risk->risk_type)) }}</td>
                                            <td>{{ $risk->project->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $risk->risk_score }}</span>
                                            </td>
                                            <td>{{ round($risk->probability, 1) }}%</td>
                                            <td>{{ round($risk->impact, 1) }}%</td>
                                            <td>
                                                <a href="{{ route('finance.risks.show', $risk) }}" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle"></i> Kritik risk bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>

            <!-- All Risks -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tüm Finansal Riskler</h5>
                </div>
                <div class="card-body">
                    @if($risks && $risks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="table-light">
                                        <th>Risk Türü</th>
                                        <th>Proje</th>
                                        <th>Seviye</th>
                                        <th>Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($risks as $risk)
                                        <tr>
                                            <td class="fw-500">{{ ucfirst(str_replace('_', ' ', $risk->risk_type)) }}</td>
                                            <td>{{ $risk->project->name ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $level = $risk->risk_score;
                                                    $badge = $level >= 75 ? 'danger' : ($level >= 50 ? 'warning' : ($level >= 25 ? 'info' : 'success'));
                                                    $levelText = $level >= 75 ? 'Kritik' : ($level >= 50 ? 'Yüksek' : ($level >= 25 ? 'Orta' : 'Düşük'));
                                                @endphp
                                                <span class="badge bg-{{ $badge }}">{{ $levelText }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $risk->status === 'active' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($risk->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('finance.risks.show', $risk) }}" class="btn btn-sm btn-outline-primary">
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
                            <i class="fas fa-info-circle"></i> Risk kaydı bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Hızlı İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.risks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Yeni Risk Kayıt
                        </a>
                        <a href="{{ route('finance.risks.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Tüm Riskleri Görüntüle
                        </a>
                        <a href="{{ route('finance.risks.analyze', $project->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-microscope"></i> Risk Analizi Yap
                        </a>
                    </div>
                </div>
            </div>

            <!-- Risk Distribution -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Risk Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">🔴 Kritik</span>
                            <span class="badge bg-danger">{{ $stats['critical_count'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $stats['critical_percent'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">🟠 Yüksek</span>
                            <span class="badge bg-warning">{{ $stats['high_count'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['high_percent'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">🔵 Orta</span>
                            <span class="badge bg-info">{{ $stats['medium_count'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stats['medium_percent'] ?? 0 }}%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">🟢 Düşük</span>
                            <span class="badge bg-success">{{ $stats['low_count'] ?? 0 }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['low_percent'] ?? 0 }}%;"></div>
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
        // Risk dashboard specific scripts
    });
</script>
@endsection
