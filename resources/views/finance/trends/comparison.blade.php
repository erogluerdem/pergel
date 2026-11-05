@extends('layouts.master')

@section('title', 'Finansal Trend Karşılaştırması')
@section('topbar-title', 'Finans - Trend Karşılaştırma')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h4 class="fw-bold mb-1">
                <i class="ri-line-chart-line text-primary me-2"></i>Finansal Trend Karşılaştırması: {{ $project->name ?? 'Seçili Proje' }}
            </h4>
            <p class="text-muted mb-0">Dönemler arası nakit akışı, tahsilat performansı, gider oranı ve büyüme karşılaştırması.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <form method="GET" action="{{ route('finance.trends.comparison') }}" class="d-inline-flex gap-2 align-items-center">
                <select name="project_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($projects ?? [] as $proj)
                        <option value="{{ $proj->id }}" {{ isset($project) && $project->id == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="monthly" {{ ($periodType ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Aylık</option>
                    <option value="quarterly" {{ ($periodType ?? '') === 'quarterly' ? 'selected' : '' }}>Çeyreklik</option>
                    <option value="yearly" {{ ($periodType ?? '') === 'yearly' ? 'selected' : '' }}>Yıllık</option>
                </select>
                <a href="{{ route('finance.trends.dashboard', ['project_id' => $project->id ?? null]) }}" class="btn btn-sm btn-outline-secondary text-nowrap">
                    <i class="ri-arrow-left-line me-1"></i>Trend Panosu
                </a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Dönemsel Performans Karşılaştırma Tablosu</h5>
            <span class="badge bg-primary">{{ count($comparisonData ?? []) }} Dönem</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Dönem</th>
                            <th class="text-end">Toplam Giriş</th>
                            <th class="text-end">Toplam Çıkış</th>
                            <th class="text-end">Net Akış</th>
                            <th class="text-center">Ödeme Zamanlılığı</th>
                            <th class="text-center">Tahsilat Oranı</th>
                            <th class="text-center">Gider Oranı</th>
                            <th class="text-center">Bütçe Sapması</th>
                            <th class="text-center">Sağlık Skoru</th>
                            <th class="text-end pe-4">Trend Yönü</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comparisonData ?? [] as $row)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $row['period'] }}</td>
                                <td class="text-end font-monospace text-success">₺{{ number_format($row['inflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-danger">₺{{ number_format($row['outflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace fw-bold {{ ($row['net_flow'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₺{{ number_format($row['net_flow'] ?? 0, 2, ',', '.') }}
                                </td>
                                <td class="text-center">%{{ number_format($row['payment_performance'] ?? 0, 1) }}</td>
                                <td class="text-center">%{{ number_format($row['collection_rate'] ?? 0, 1) }}</td>
                                <td class="text-center">%{{ number_format($row['expense_ratio'] ?? 0, 1) }}</td>
                                <td class="text-center">%{{ number_format($row['budget_variance'] ?? 0, 1) }}</td>
                                <td class="text-center">
                                    <span class="badge {{ ($row['health_score'] ?? 0) >= 70 ? 'bg-success' : (($row['health_score'] ?? 0) >= 40 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ round($row['health_score'] ?? 0) }}/100
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                        {{ $row['trend_direction'] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    Karşılaştırılacak trend verisi bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
