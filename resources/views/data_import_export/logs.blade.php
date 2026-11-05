@extends('layouts.master')

@section('title', 'İçe Aktarım İşlem Logları')

@section('topbar-title', 'Veri Transferi - İşlem Logları')

@push('styles')
<style>
    .die-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .die-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .die-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="die-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('data-import-export.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Veri Transfer Merkezi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Veri Transfer Günlüğü & İşlem Logları</h1>
            <p class="text-white-50 mb-0 fs-6">Sistemde gerçekleştirilen tüm veri içe ve dışa aktarım günlükleri.</p>
        </div>
    </div>
</div>

<div class="die-glass-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Zaman</th>
                    <th>Dosya Adı</th>
                    <th>Modül</th>
                    <th>Toplam / Aktarılan</th>
                    <th>Hata Satırları</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 text-muted small">{{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y H:i') }}</td>
                        <td><span class="font-monospace fw-bold text-dark">{{ $log->file_name }}</span></td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill px-3 py-1 text-uppercase">{{ $log->module }}</span></td>
                        <td>{{ number_format($log->total_rows ?? 0) }} / <strong class="text-success">{{ number_format($log->imported_rows ?? 0) }}</strong></td>
                        <td><span class="text-danger fw-bold">{{ number_format($log->error_rows ?? 0) }} Hata</span></td>
                        <td>
                            <span class="badge bg-{{ $log->status === 'completed' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }} bg-opacity-10 text-{{ $log->status === 'completed' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }} border rounded-pill px-3 py-1">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Log kaydı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $logs->links() }}
    </div>
</div>

@endsection
