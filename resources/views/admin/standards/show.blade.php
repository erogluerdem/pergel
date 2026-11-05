@extends('layouts.master')

@section('title', 'Kalite Standardı Detayı - ' . $standard->code)

@section('topbar-title', 'Kalite Standartları - Standart Detay')

@push('styles')
<style>
    .std-adm-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .std-adm-glass-card {
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .std-adm-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="std-adm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $standard->code }}
                </span>
                <span class="badge bg-white text-teal rounded-pill px-3 py-1 fw-bold" style="color:#0d9488;">
                    {{ $standard->version ?? 'v1.0' }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $standard->name }}</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $standard->description }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.standards.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>Listeye Dön
            </a>
            <a href="{{ route('admin.standards.edit', $standard->id) }}" class="btn btn-light rounded-pill px-4 fw-bold text-teal shadow-sm" style="color:#0d9488;">
                <i class="ri-edit-line me-1"></i>Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="std-adm-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-article-line text-teal me-2" style="color:#0d9488;"></i>Standart Özellikleri
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Kategori</span>
                    <strong class="text-dark">{{ \App\Models\Standard::CATEGORIES[$standard->category] ?? $standard->category }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Standart Türü</span>
                    <strong class="text-dark">{{ \App\Models\Standard::TYPES[$standard->type] ?? $standard->type }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Zorunluluk Seviyesi</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border rounded-pill px-3 py-1 fw-bold">
                        {{ \App\Models\Standard::COMPLIANCE_LEVELS[$standard->compliance_level] ?? $standard->compliance_level }}
                    </span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Durum</span>
                    <span class="badge bg-{{ $standard->status_color }} bg-opacity-10 text-{{ $standard->status_color }} border rounded-pill px-3 py-1 fw-bold">
                        {{ \App\Models\Standard::STATUSES[$standard->status] ?? $standard->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="std-adm-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-calendar-line text-primary me-2"></i>Tarih & Doküman Bilgileri
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Yürürlük Tarihi</span>
                    <strong class="text-dark">{{ $standard->effective_date ? \Carbon\Carbon::parse($standard->effective_date)->format('d.m.Y') : '-' }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Geçerlilik Bitiş Tarihi</span>
                    <strong class="text-dark">{{ $standard->expiry_date ? \Carbon\Carbon::parse($standard->expiry_date)->format('d.m.Y') : 'Süresiz' }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Doküman Bağlantısı</span>
                    @if($standard->document_url)
                        <a href="{{ $standard->document_url }}" target="_blank" class="btn btn-sm btn-outline-teal rounded-pill px-3">
                            <i class="ri-external-link-line me-1"></i>Dokümanı Görüntüle
                        </a>
                    @else
                        <span class="text-muted">Doküman Yüklenmedi</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
