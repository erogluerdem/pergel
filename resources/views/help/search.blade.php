@extends('layouts.master')

@section('title', 'Yardım Arama Sonuçları')

@section('topbar-title', 'Destek - Arama')

@push('styles')
<style>
    .help-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #3b82f6 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .help-glass-card {
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .help-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="help-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('help.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Yardım Merkezi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Arama Sonuçları: "{{ $query }}"</h1>
            <p class="text-white-50 mb-0 fs-6">Toplam {{ $totalResults }} makale eşleşti.</p>
        </div>
    </div>
</div>

<div class="help-glass-card">
    <div class="list-group list-group-flush">
        @forelse($searchResults as $art)
            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3 border-bottom">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1 mb-2">{{ $art->category }}</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $art->title }}</h5>
                    <p class="text-muted small mb-0">{{ $art->excerpt }}</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('help.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Makaleyi Oku</a>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="ri-search-eye-line fs-1 d-block mb-2 text-secondary"></i>
                Aramanızla eşleşen sonuç bulunamadı.
            </div>
        @endforelse
    </div>
</div>

@endsection