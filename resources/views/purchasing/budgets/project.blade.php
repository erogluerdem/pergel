@extends('layouts.master')

@section('title', 'Proje Bütçe Detayı')
@section('topbar-title', 'Satın Alma - Proje Bütçesi')

@section('content')
<div class="container-fluid">
    @php
        $projectModel = \App\Models\Project::find($projectId);
        $usagePercent = ($totalAllocated ?? 0) > 0 ? round((($totalSpent ?? 0) / $totalAllocated) * 100, 1) : 0;
    @endphp

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-funds-box-line text-primary me-2"></i>Proje Bütçe Tahsisi: {{ $projectModel->name ?? ('Proje #' . $projectId) }}
            </h4>
            <p class="text-muted mb-0">Kategori bazlı bütçe tahsisleri, gerçekleşen harcamalar ve kalan limitler.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('purchasing.budgets.create', ['project_id' => $projectId]) }}" class="btn btn-primary">
                <i class="ri-add-line me-1"></i>Yeni Kalem Ekle
            </a>
            <a href="{{ route('purchasing.budgets.index') }}" class="btn btn-outline-secondary ms-1">
                <i class="ri-arrow-left-line me-1"></i>Tüm Bütçeler
            </a>
        </div>
    </div>

    <!-- KPI Özeti -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Toplam Tahsis Edilen</small>
                    <h3 class="fw-bold mt-1 mb-0">₺{{ number_format($totalAllocated ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Toplam Harcanan</small>
                    <h3 class="fw-bold text-warning mt-1 mb-0">₺{{ number_format($totalSpent ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Kalan Bütçe</small>
                    <h3 class="fw-bold text-success mt-1 mb-0">₺{{ number_format($totalRemaining ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-info border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Bütçe Kullanım Oranı</small>
                    <h3 class="fw-bold text-info mt-1 mb-0">%{{ $usagePercent }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Tablosu -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Kategori Bazlı Bütçe Kalemleri</h5>
            <span class="badge bg-primary">{{ count($budgets ?? []) }} Kategori</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Kategori</th>
                            <th>Mali Yıl</th>
                            <th class="text-end">Tahsis Edilen</th>
                            <th class="text-end">Harcanan</th>
                            <th class="text-end">Kalan</th>
                            <th style="width: 200px;">Kullanım Oranı</th>
                            <th class="text-end pe-4">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            @php
                                $pct = ($budget->allocated_amount ?? 0) > 0
                                    ? round((($budget->spent_amount ?? 0) / $budget->allocated_amount) * 100, 1)
                                    : 0;
                                $barClass = $pct > 85 ? 'bg-danger' : ($pct > 65 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $budget->category }}</td>
                                <td>{{ $budget->fiscal_year ?? date('Y') }}</td>
                                <td class="text-end font-monospace">₺{{ number_format($budget->allocated_amount ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-warning">₺{{ number_format($budget->spent_amount ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end font-monospace text-success">₺{{ number_format($budget->remaining_amount ?? 0, 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $barClass }}" style="width: {{ min(100, $pct) }}%"></div>
                                        </div>
                                        <small class="fw-semibold">%{{ $pct }}</small>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('purchasing.budgets.edit', $budget) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-pencil-line me-1"></i>Düzenle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Bu proje için henüz bütçe kalemi tanımlanmamış.
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
