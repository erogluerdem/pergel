@extends('layouts.master')

@section('title', 'Kategori: ' . $categoryName)

@section('topbar-title', 'Destek - Kategori')

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
            <h1 class="fw-extrabold text-white mb-1">Kategori: {{ $categoryName }}</h1>
            <p class="text-white-50 mb-0 fs-6">Bu kategoriye ait rehberler ve teknik dokümanlar.</p>
        </div>
    </div>
</div>

<div class="help-glass-card">
    <div class="list-group list-group-flush">
        @foreach($articles as $art)
            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">{{ $art->title }}</h5>
                    <p class="text-muted small mb-0">{{ $art->excerpt }}</p>
                </div>
                <div class="text-end">
                    <small class="text-muted d-block mb-1"><i class="ri-eye-line me-1"></i>{{ $art->views }}</small>
                    <a href="{{ route('help.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Makaleyi Oku</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection