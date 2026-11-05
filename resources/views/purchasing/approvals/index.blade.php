@extends('layouts.master')

@section('title', 'Satın Alma Onay Süreçleri')
@section('topbar-title', 'Satın Alma - Onay Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-checkbox-multiple-line text-warning me-2"></i>Satın Alma Onay Süreçleri
            </h4>
            <p class="text-muted mb-0">Tüm satın alma talep onaylarını seviye ve durum bazında görüntüleyin ve yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('purchasing.approvals.pending') }}" class="btn btn-warning text-dark fw-semibold rounded-pill px-4">
                <i class="ri-time-line me-1"></i>Onay Bekleyenler
            </a>
            <a href="{{ route('purchasing.approvals.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3 ms-1">
                <i class="ri-dashboard-line me-1"></i>Özet Panel
            </a>
        </div>
    </div>

    @php
        $levelLabels = [
            'manager' => 'Proje Müdürü',
            'finance' => 'Finans & Bütçe',
            'executive' => 'Üst Yönetim',
            'final' => 'Yönetim Kurulu (Final)',
        ];
    @endphp

    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('purchasing.approvals.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Onay Durumu</label>
                    <select name="status" class="form-select">
                        <option value="">Tüm Durumlar</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Onaylandı</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Onay Seviyesi</label>
                    <select name="approval_level" class="form-select">
                        <option value="">Tüm Seviyeler</option>
                        @foreach($levels ?? [] as $lvl)
                            <option value="{{ $lvl }}" {{ request('approval_level') === $lvl ? 'selected' : '' }}>
                                {{ $levelLabels[$lvl] ?? ucfirst($lvl) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-filter-3-line me-1"></i>Filtrele
                    </button>
                    <a href="{{ route('purchasing.approvals.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-refresh-line me-1"></i>Temizle
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Onay Listesi -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Onay Kayıtları</h5>
            <span class="badge bg-primary">{{ method_exists($approvals, 'total') ? $approvals->total() : count($approvals) }} Kayıt</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Talep No</th>
                            <th>Talep / Proje</th>
                            <th>Onay Seviyesi</th>
                            <th>Onaylayıcı</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvals as $approval)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-secondary font-monospace">
                                        #REQ-{{ str_pad($approval->requisition_id ?? $approval->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $approval->requisition->title ?? ($approval->requisition->notes ?? 'Satın Alma Talebi') }}</div>
                                    <small class="text-muted">{{ $approval->requisition->project->name ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                        {{ $levelLabels[$approval->approval_level] ?? ucfirst($approval->approval_level) }}
                                    </span>
                                </td>
                                <td>{{ $approval->approver->name ?? '-' }}</td>
                                <td>
                                    @if($approval->status === 'approved')
                                        <span class="badge bg-success">Onaylandı</span>
                                    @elseif($approval->status === 'rejected')
                                        <span class="badge bg-danger">Reddedildi</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Bekliyor</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $approval->created_at ? $approval->created_at->format('d.m.Y H:i') : '-' }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('purchasing.approvals.show', $approval->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line me-1"></i>Detay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ri-inbox-line fs-2 d-block mb-2"></i>
                                    Kayıtlı onay süreci bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($approvals, 'links'))
            <div class="card-footer">
                {{ $approvals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
