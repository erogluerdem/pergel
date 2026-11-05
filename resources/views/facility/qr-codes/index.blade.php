@extends('layouts.master')

@section('title', 'QR Kod Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-qrcode"></i> QR Kod Yönetimi
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('facility.qr.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni QR Kod Oluştur
            </a>
            @if(isset($projects) && count($projects) > 0)
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-magic"></i> Proje QR Kodlarını Oluştur
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach($projects as $project)
                            <li>
                                <a class="dropdown-item" href="{{ route('facility.qr.generate-all', $project->id) }}">
                                    {{ $project->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <button class="btn btn-success" disabled title="Önce proje oluşturun">
                    <i class="fas fa-magic"></i> Proje QR Kodlarını Oluştur
                </button>
            @endif
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
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Toplam QR Kodlar</h6>
                            <h2 class="mb-0">{{ $totalQrCodes ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-qrcode fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Aktif Kodlar</h6>
                            <h2 class="mb-0">{{ $activeQrCodes ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Toplam Taramalar</h6>
                            <h2 class="mb-0">{{ $totalScans ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-scanner fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Deaktif Kodlar</h6>
                            <h2 class="mb-0">{{ $inactiveQrCodes ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-ban fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtreler --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('facility.qr.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="QR Token veya Etiket Ara..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="project_id" class="form-select">
                        <option value="">Tüm Projeler</option>
                        @if(isset($projects))
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tüm Durumlar</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Deaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Ara
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- QR Kodları Tablosu --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">QR Token</th>
                        <th width="20%">Etiket</th>
                        <th width="15%">Proje</th>
                        <th width="10%">Taramalar</th>
                        <th width="10%">Durum</th>
                        <th width="15%">Son Taranan</th>
                        <th width="10%" class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qrCodes ?? [] as $qr)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <code class="bg-light p-2 rounded">{{ substr($qr->qr_token, 0, 12) }}...</code>
                            </td>
                            <td>
                                @if($qr->qr_label)
                                    <strong>{{ $qr->qr_label }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($qr->project)
                                    {{ $qr->project->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $qr->scans_count ?? 0 }}</span>
                            </td>
                            <td>
                                @if($qr->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Deaktif</span>
                                @endif
                            </td>
                            <td>
                                @if($qr->last_scanned_at)
                                    <small>{{ $qr->last_scanned_at->format('d.m.Y H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('facility.qr.download', $qr->id) }}" class="btn btn-info" title="İndir">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if($qr->is_active)
                                        <form action="{{ route('facility.qr.disable', $qr->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Deaktif Et" onclick="return confirm('Deaktif etmek istediğinize emin misiniz?')">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('facility.qr.enable', $qr->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Aktif Et">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('facility.qr.show', $qr->id) }}" class="btn btn-primary" title="Detaylar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox text-muted fa-2x mb-2"></i>
                                <p class="text-muted">Henüz QR kodu yok.</p>
                                <a href="{{ route('facility.qr.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> İlk QR Kodu Oluştur
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Sayfalama --}}
    @if(isset($qrCodes) && $qrCodes instanceof \Illuminate\Pagination\Paginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $qrCodes->links() }}
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
