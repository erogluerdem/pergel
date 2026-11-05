@extends('layouts.master')

@section('title', 'SLA Ayarları')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-cog"></i> SLA Ayarları
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('facility.sla.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni SLA Hedefi Tanımla
            </a>
        </div>
    </div>

    {{-- Başarı Mesajı --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- İstatistikler --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Tanımlı SLA Hedefleri</h6>
                            <h2 class="mb-0">{{ $totalSlaConfigs ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-tasks fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Uyarı Uyarıları</h6>
                            <h2 class="mb-0">{{ $warningAlerts ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Kritik Uyarıları</h6>
                            <h2 class="mb-0">{{ $criticalAlerts ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-bell fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtreler --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('facility.sla.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Proje veya Kategori Ara..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Tüm Durumlar</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Deaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Ara
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SLA Hedefleri Tablosu --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Proje</th>
                        <th width="15%">Kategori</th>
                        <th width="12%">Yanıt Süresi</th>
                        <th width="12%">Çözüm Süresi</th>
                        <th width="10%">Durum</th>
                        <th width="10%" class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slaConfigs ?? [] as $sla)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($sla->project)
                                    <strong>{{ $sla->project->name }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $sla->issue_category }}</span>
                            </td>
                            <td>
                                <strong>{{ $sla->response_time }} saat</strong>
                            </td>
                            <td>
                                <strong>{{ $sla->resolution_time }} saat</strong>
                            </td>
                            <td>
                                @if($sla->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Deaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('facility.sla.edit', $sla->id) }}" class="btn btn-warning" title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('facility.sla.destroy', $sla->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Sil" onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox text-muted fa-2x mb-2"></i>
                                <p class="text-muted">Henüz SLA hedefi tanımlanmamış.</p>
                                <a href="{{ route('facility.sla.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> İlk SLA Hedefini Tanımla
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Sayfalama --}}
    @if(isset($slaConfigs) && $slaConfigs instanceof \Illuminate\Pagination\Paginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $slaConfigs->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection
