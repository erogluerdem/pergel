@extends('layouts.master')

@section('title', 'Hakediş Onay Süreci')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">
    <!-- Sayfa Başlığı -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Hakediş Onay Süreci</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('subcontractor.companies') }}">Taşeron Yönetimi</a></li>
                        <li class="breadcrumb-item active">Hakediş Onayları</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreleme -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Filtreler</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('subcontractor.progress.approval') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="project_id" class="form-label">Proje</label>
                            <select class="form-select" id="project_id" name="project_id">
                                <option value="">Tümü</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="subcontractor_id" class="form-label">Taşeron Firma</label>
                            <select class="form-select" id="subcontractor_id" name="subcontractor_id">
                                <option value="">Tümü</option>
                                @foreach($subcontractors as $subcontractor)
                                    <option value="{{ $subcontractor->id }}" {{ request('subcontractor_id') == $subcontractor->id ? 'selected' : '' }}>
                                        {{ $subcontractor->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-magnify"></i> Filtrele
                                </button>
                                <a href="{{ route('subcontractor.progress.approval') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-refresh"></i> Temizle
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Onay Bekleyen Hakedişler -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Onay Bekleyen Hakedişler ({{ $pendingClaims->total() }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Öncelik</th>
                                    <th>Hakediş No</th>
                                    <th>Proje</th>
                                    <th>Taşeron Firma</th>
                                    <th>Talep Edilen Tutar</th>
                                    <th>Gelen Tarih</th>
                                    <th>Onay Durumu</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingClaims as $claim)
                                <tr>
                                    <td>
                                        @if($claim->submitted_at && $claim->submitted_at->diffInDays(now()) > 7)
                                            <span class="badge bg-danger">🔥 Acil</span>
                                        @else
                                            <span class="badge bg-secondary">Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $claim->claim_number }}</strong>
                                    </td>
                                    <td>{{ $claim->project->name ?? 'N/A' }}</td>
                                    <td>{{ $claim->subcontractorContract->subcontractor->company_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($claim->total_amount, 0, ',', '.') }} TL</td>
                                    <td>
                                        @if($claim->submitted_at)
                                            {{ $claim->submitted_at->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Saha Onayı Bekliyor</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('subcontractor.progress.approval.detail', $claim->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> İncele ve Karar Ver
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-check-circle-outline font-size-48 mb-2"></i>
                                            <p>Onayınızı bekleyen hakediş bulunmuyor.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Sayfalama -->
                    @if($pendingClaims->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pendingClaims->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
