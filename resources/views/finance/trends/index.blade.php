@extends('layouts.master')

@section('title', 'Finansal Trendler')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0"><i class="fas fa-list"></i> Tüm Finansal Trendler</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.trends.index') }}" class="btn btn-primary">
                <i class="fas fa-plus\"></i> Yeni Trend
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Proje adı ara...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>Periyod: Tümü</option>
                        <option>Günlük</option>
                        <option>Haftalık</option>
                        <option>Aylık</option>
                        <option>Üç Aylık</option>
                        <option>Yıllık</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>Durum: Tümü</option>
                        <option>Analiz Edildi</option>
                        <option>Beklemede</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Ara</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th>Proje</th>
                            <th>Periyod</th>
                            <th>Sağlık Puanı</th>
                            <th>Net Akış</th>
                            <th>Tahsilat Oranı</th>
                            <th>Gider Oranı</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trends as $trend)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-500">{{ $trend->project->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-light text-dark">{{ ucfirst($trend->period_type) }}</span></td>
                                <td>
                                    @php
                                        $score = $trend->health_score ?? 0;
                                        $color = $score >= 75 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ round($score, 1) }}/100</span>
                                </td>
                                <td class="text-{{ $trend->net_cash_flow >= 0 ? 'success' : 'danger' }}">
                                    ₺{{ number_format($trend->net_cash_flow, 0, ',', '.') }}
                                </td>
                                <td>{{ round($trend->collection_ratio, 1) }}%</td>
                                <td>{{ round($trend->expense_ratio, 1) }}%</td>
                                <td><span class="badge bg-{{ $trend->status === 'analyzed' ? 'primary' : 'secondary' }}">{{ ucfirst($trend->status) }}</span></td>
                                <td>
                                    <a href="{{ route('finance.trends.show', $trend) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('finance.trends.edit', $trend) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Trend kaydı bulunmamaktadır</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
