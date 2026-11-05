@extends('layouts.master')

@section('title', 'Detaylı Finansal Trend Raporu')
@section('topbar-title', 'Finans - Detaylı Trend Raporu')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h4 class="fw-bold mb-1">
                <i class="ri-file-chart-line text-info me-2"></i>Detaylı Finansal Trend Raporu: {{ $project->name ?? 'Seçili Proje' }}
            </h4>
            <p class="text-muted mb-0">Proje finansal göstergelerinin dönemsel ortalamaları ve detaylı kayıt raporu.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <form method="GET" action="{{ route('finance.trends.report') }}" class="d-inline-flex gap-2 align-items-center">
                <select name="project_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($projects ?? [] as $proj)
                        <option value="{{ $proj->id }}" {{ isset($project) && $project->id == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('finance.trends.export', ['project_id' => $project->id ?? null, 'period' => $periodType ?? 'monthly']) }}" class="btn btn-sm btn-success text-nowrap">
                    <i class="ri-download-2-line me-1"></i>CSV İndir
                </a>
            </form>
        </div>
    </div>

    <!-- Ortalamalar Özeti -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Ort. Net Nakit Akışı</small>
                    <h4 class="fw-bold mt-1 mb-0">₺{{ number_format($averages['avg_net_flow'] ?? ($trends->avg('net_cash_flow') ?? 0), 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Ort. Tahsilat Oranı</small>
                    <h4 class="fw-bold text-success mt-1 mb-0">%{{ number_format($averages['avg_collection_ratio'] ?? ($trends->avg('collection_ratio') ?? 0), 1) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Ort. Gider Oranı</small>
                    <h4 class="fw-bold text-warning mt-1 mb-0">%{{ number_format($averages['avg_expense_ratio'] ?? ($trends->avg('expense_ratio') ?? 0), 1) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-info border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Ort. Ödeme Zamanlılığı</small>
                    <h4 class="fw-bold text-info mt-1 mb-0">%{{ number_format($averages['avg_payment_timeliness'] ?? ($trends->avg('payment_timeliness_ratio') ?? 0), 1) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Detaylı Tablo -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Dönemsel Trend Kayıtları</h5>
            <span class="badge bg-primary">{{ count($trends ?? []) }} Kayıt</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Dönem Aralığı</th>
                            <th class="text-end">Toplam Giriş</th>
                            <th class="text-end">Toplam Çıkış</th>
                            <th class="text-end">Net Nakit Akışı</th>
                            <th class="text-center">Büyüme Oranı</th>
                            <th class="text-center">Bütçe Sapması</th>
                            <th class="text-end pe-4">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trends ?? [] as $trend)
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    {{ $trend->period_start ? $trend->period_start->format('d.m.Y') : '-' }} —
                                    {{ $trend->period_end ? $trend->period_end->format('d.m.Y') : '-' }}
                                </td>
                                <td class="text-end font-monospace text-success">₺{{ number_format($trend->total_inflow ?? $trend->starting_value ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-danger">₺{{ number_format($trend->total_outflow ?? $trend->ending_value ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace fw-bold">₺{{ number_format($trend->net_cash_flow ?? $trend->value_difference ?? 0, 2, ',', '.') }}</td>
                                <td class="text-center">%{{ number_format($trend->growth_rate ?? $trend->percent_change ?? 0, 1) }}</td>
                                <td class="text-center">%{{ number_format($trend->budget_variance ?? 0, 1) }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('finance.trends.show', $trend->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line me-1"></i>Detay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Raporlanacak trend kaydı bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
