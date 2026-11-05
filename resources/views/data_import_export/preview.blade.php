@extends('layouts.master')

@section('title', 'Veri İçe Aktarım Önizleme')

@section('topbar-title', 'Veri Transferi - Önizleme')

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
                <a href="{{ route('data-import-export.import_form') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Forma Dön
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Veri Önizleme & Onay</h1>
            <p class="text-white-50 mb-0 fs-6">Dosyanızdan okunan ilk 5 satır kontrol ediliyor. Toplam: {{ $total }} satır.</p>
        </div>
    </div>
</div>

<div class="die-glass-card">
    <h5 class="fw-bold text-dark mb-3">
        <i class="ri-table-line text-primary me-2"></i>Okunan Dosya İçerik Önizlemesi
    </h5>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    @if(!empty($preview) && count($preview) > 0)
                        @foreach(array_keys((array)$preview->first()) as $colHeader)
                            <th>{{ $colHeader }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($preview as $row)
                    <tr>
                        @foreach((array)$row as $val)
                            <td>{{ is_array($val) ? json_encode($val) : $val }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td class="text-center py-4 text-muted">Önizlenecek veri bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
