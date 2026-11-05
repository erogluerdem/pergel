@extends('layouts.master')

@section('title', 'Bütçe Yönetimi')
@section('topbar-title', '💰 Bütçe Yönetimi')

@section('css')
<style>
.stats-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.stats-card h3 {
    font-size: 2rem;
    font-weight: 700;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="page-title">💰 Bütçe Yönetimi</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('purchasing.budgets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Bütçe Oluştur
            </a>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Toplam Bütçe</h6>
                            <h3 class="mb-0">₺{{ number_format($totalAllocated, 2) }}</h3>
                        </div>
                        <i class="fas fa-piggy-bank fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Harcanan</h6>
                            <h3 class="mb-0">₺{{ number_format($totalSpent, 2) }}</h3>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Kalan</h6>
                            <h3 class="mb-0">₺{{ number_format($totalRemaining, 2) }}</h3>
                        </div>
                        <i class="fas fa-money-bill fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Harcama %</h6>
                            <h3 class="mb-0">{{ $totalAllocated > 0 ? round(($totalSpent / $totalAllocated) * 100) : 0 }}%</h3>
                        </div>
                        <i class="fas fa-percent fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Proje</label>
                    <select name="project_id" class="form-select">
                        <option value="">Tüm Projeler</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter"></i> Filtrele
                    </button>
                    <a href="{{ route('purchasing.budgets.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bütçe Tablosu -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Proje</th>
                        <th>Kategori</th>
                        <th>Tahsis Edilen</th>
                        <th>Harcanan</th>
                        <th>Kalan</th>
                        <th>Kullanım %</th>
                        <th>Düzenleme</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($budgets as $budget)
                        <tr>
                            <td><strong>{{ $budget->project->name }}</strong></td>
                            <td>{{ $budget->category }}</td>
                            <td>₺{{ number_format($budget->allocated_amount, 2) }}</td>
                            <td>₺{{ number_format($budget->spent_amount, 2) }}</td>
                            <td>₺{{ number_format($budget->remaining_amount, 2) }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $budget->spent_percentage > 80 ? 'bg-danger' : ($budget->spent_percentage > 60 ? 'bg-warning' : 'bg-success') }}" 
                                         role="progressbar" 
                                         style="width: {{ $budget->spent_percentage }}%">
                                        {{ round($budget->spent_percentage) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('purchasing.budgets.edit', $budget) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>Henüz bütçe tanımlanmamış</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $budgets->links() }}
        </div>
    </div>
</div>
@endsection
