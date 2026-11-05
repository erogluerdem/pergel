@extends('layouts.master')

@section('title', 'Finansal Sağlık Skoru Panosu')
@section('topbar-title', 'Finans - Sağlık Skoru')

@section('content')
<div class="container-fluid">
    @php
        $scores = collect($healthScores ?? []);
        $avgScore = round($scores->avg('score') ?? 0, 1);
    @endphp

    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h4 class="fw-bold mb-1">
                <i class="ri-heart-pulse-line text-danger me-2"></i>Finansal Sağlık Skoru: {{ $project->name ?? 'Seçili Proje' }}
            </h4>
            <p class="text-muted mb-0">Projenin likidite, tahsilat ve ödeme disiplini bazlı finansal sağlık endeksi.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <form method="GET" action="{{ route('finance.trends.health-score') }}" class="d-inline-flex gap-2 align-items-center">
                <select name="project_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($projects ?? [] as $proj)
                        <option value="{{ $proj->id }}" {{ isset($project) && $project->id == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('finance.trends.dashboard', ['project_id' => $project->id ?? null]) }}" class="btn btn-sm btn-outline-secondary text-nowrap">
                    <i class="ri-arrow-left-line me-1"></i>Trend Panosu
                </a>
            </form>
        </div>
    </div>

    <!-- Genel Skor Kartı -->
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h6 class="text-muted text-uppercase mb-1">Ortalama Finansal Sağlık Puanı</h6>
                <h2 class="fw-extrabold mb-0 {{ $avgScore >= 70 ? 'text-success' : ($avgScore >= 40 ? 'text-warning' : 'text-danger') }}">
                    {{ $avgScore }} / 100
                </h2>
            </div>
            <div class="flex-grow-1" style="max-width: 400px;">
                <div class="progress" style="height: 14px;">
                    <div class="progress-bar {{ $avgScore >= 70 ? 'bg-success' : ($avgScore >= 40 ? 'bg-warning' : 'bg-danger') }}"
                         style="width: {{ min(100, max(0, $avgScore)) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dönemsel Sağlık Skorları Tablosu -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Dönemsel Sağlık Skoru Dağılımı</h5>
            <span class="badge bg-primary">{{ $scores->count() }} Dönem</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Dönem</th>
                            <th class="text-center">Sağlık Skoru</th>
                            <th class="text-end">Nakit Girişi</th>
                            <th class="text-end">Nakit Çıkışı</th>
                            <th class="text-center">Ödeme Zamanlılığı</th>
                            <th class="text-center pe-4">Tahsilat Oranı</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scores as $item)
                            @php
                                $sc = round($item['score'] ?? 0, 1);
                                $badgeClass = $sc >= 70 ? 'bg-success' : ($sc >= 40 ? 'bg-warning text-dark' : 'bg-danger');
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold">{{ $item['period'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass }} px-3 py-1">{{ $sc }} / 100</span>
                                </td>
                                <td class="text-end font-monospace text-success">₺{{ number_format($item['inflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-danger">₺{{ number_format($item['outflow'] ?? 0, 2, ',', '.') }}</td>
                                <td class="text-center">%{{ number_format($item['payment_timeliness'] ?? 0, 1) }}</td>
                                <td class="text-center pe-4">%{{ number_format($item['collection_ratio'] ?? 0, 1) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Sağlık skoru verisi bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
