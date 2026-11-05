@extends('layouts.master')

@section('title', 'Nakit Akış Karşılaştırması (Planlanan vs Gerçekleşen)')
@section('topbar-title', 'Finans - Nakit Akış Karşılaştırma')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h4 class="fw-bold mb-1">
                <i class="ri-scales-3-line text-success me-2"></i>Nakit Akış Karşılaştırması: {{ $project->name ?? 'Tüm Projeler' }}
            </h4>
            <p class="text-muted mb-0">Aylık bazda planlanan ve gerçekleşen nakit giriş/çıkışlarının sapma analizi.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <form method="GET" action="{{ route('finance.forecast.comparison') }}" class="d-inline-flex gap-2 align-items-center">
                <select name="project_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($projects ?? [] as $proj)
                        <option value="{{ $proj->id }}" {{ isset($project) && $project->id == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('finance.forecast.dashboard', ['project_id' => $project->id ?? null]) }}" class="btn btn-sm btn-outline-secondary text-nowrap">
                    <i class="ri-arrow-left-line me-1"></i>Tahmin Panosu
                </a>
            </form>
        </div>
    </div>

    @php
        $items = collect($comparisonData ?? []);
        $totalPlannedIn = $items->sum('planned_inflow');
        $totalActualIn = $items->sum('actual_inflow');
        $totalPlannedOut = $items->sum('planned_outflow');
        $totalActualOut = $items->sum('actual_outflow');
    @endphp

    <!-- Özet KPI Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Planlanan Toplam Giriş</small>
                    <h4 class="fw-bold mt-1 mb-0">₺{{ number_format($totalPlannedIn, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Gerçekleşen Toplam Giriş</small>
                    <h4 class="fw-bold text-success mt-1 mb-0">₺{{ number_format($totalActualIn, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Planlanan Toplam Çıkış</small>
                    <h4 class="fw-bold text-warning mt-1 mb-0">₺{{ number_format($totalPlannedOut, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-danger border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Gerçekleşen Toplam Çıkış</small>
                    <h4 class="fw-bold text-danger mt-1 mb-0">₺{{ number_format($totalActualOut, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Karşılaştırma Tablosu -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Dönemsel Tahmin vs Gerçekleşme Tablosu</h5>
            <span class="badge bg-primary">{{ $items->count() }} Dönem</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Dönem</th>
                            <th class="text-end">Planlanan Giriş</th>
                            <th class="text-end">Gerçekleşen Giriş</th>
                            <th class="text-end">Giriş Sapması</th>
                            <th class="text-end">Planlanan Çıkış</th>
                            <th class="text-end">Gerçekleşen Çıkış</th>
                            <th class="text-end">Planlanan Net</th>
                            <th class="text-end">Gerçekleşen Net</th>
                            <th class="text-center pe-4">Güven Seviyesi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $row)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $row['period'] }}</td>
                                <td class="text-end font-monospace">₺{{ number_format($row['planned_inflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-success">₺{{ number_format($row['actual_inflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace {{ ($row['inflow_variance'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ ($row['inflow_variance'] ?? 0) >= 0 ? '+' : '' }}₺{{ number_format($row['inflow_variance'] ?? 0, 2, ',', '.') }}
                                </td>
                                <td class="text-end font-monospace">₺{{ number_format($row['planned_outflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-danger">₺{{ number_format($row['actual_outflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace fw-semibold">₺{{ number_format($row['planned_net'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace fw-bold {{ ($row['actual_net'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₺{{ number_format($row['actual_net'] ?? 0, 2, ',', '.') }}
                                </td>
                                <td class="text-center pe-4">
                                    <span class="badge bg-info">%{{ $row['confidence'] ?? 0 }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    Karşılaştırma verisi bulunamadı.
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
