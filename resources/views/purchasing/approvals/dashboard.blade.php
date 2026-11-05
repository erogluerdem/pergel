@extends('layouts.master')

@section('title', 'Onay Paneli')
@section('topbar-title', '📊 Onay Paneli')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="page-title">📊 Onay Paneli</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Beklemede Onaylar</h6>
                            <h3 class="mb-0">{{ $pendingApprovals }}</h3>
                        </div>
                        <i class="fas fa-hourglass-half fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Bugün Onaylanan</h6>
                            <h3 class="mb-0">{{ $approvedToday }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Reddedilen</h6>
                            <h3 class="mb-0">{{ $rejectedCount }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body text-center">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h5>Onay Paneli Aktif</h5>
            <p class="text-muted">Tüm onay işlemleri burada takip edilebilir.</p>
            <a href="{{ route('purchasing.approvals.pending') }}" class="btn btn-primary">
                <i class="fas fa-list"></i> Beklemede Onayları Görüntüle
            </a>
        </div>
    </div>
</div>
@endsection
