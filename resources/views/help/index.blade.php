@extends('layouts.master')

@section('title', 'Yardım & Destek Merkezi')

@section('topbar-title', 'Destek - Yardım Merkezi')

@push('styles')
<style>
    /* Hero Header Banner */
    .help-hero-banner {
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .help-hero-banner,
    :root:not([data-bs-theme="dark"]) .help-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #3b82f6 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .help-hero-banner {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .help-search-input {
        border-radius: 50px 0 0 50px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        border: none;
    }

    .help-search-btn {
        border-radius: 0 50px 50px 0;
        padding: 1rem 2rem;
        font-weight: 700;
    }

    /* Category & Article Cards */
    .help-card {
        border-radius: 18px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .help-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .help-card,
    :root:not([data-bs-theme="dark"]) .help-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .help-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .help-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    /* Glassmorphic Container */
    .help-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .help-glass-card,
    :root:not([data-bs-theme="dark"]) .help-glass-card {
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

<!-- Flash Success Notification -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-checkbox-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Hero Header Banner with Search & Remote Sync -->
<div class="help-hero-banner text-center">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
            <i class="ri-database-2-line me-1"></i>SQL Veritabanı & Canlı Merkez Senkronizasyonu
        </span>
        <form method="POST" action="{{ route('help.sync_remote') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-light text-indigo rounded-pill px-3 fw-bold shadow-sm" style="color:#4f46e5;">
                <i class="ri-refresh-line me-1"></i>Merkez Sunucudan Güncelle (Sync SQL)
            </button>
        </form>
    </div>

    <h1 class="fw-extrabold text-white mb-2 fs-2">Size Nasıl Yardımcı Olabiliriz?</h1>
    <p class="text-white-50 mb-4 fs-6 mx-auto" style="max-width: 600px;">Hakediş, saha raporları, faturalar veya kullanıcı izinleri ile ilgili sorularınızı arayın.</p>
    
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <form method="GET" action="{{ route('help.search') }}">
                <div class="input-group shadow-lg rounded-pill overflow-hidden">
                    <input type="text" class="form-control help-search-input" placeholder="Örn: Hakediş nasıl oluşturulur, e-fatura kesme..." name="q" value="{{ request('q') }}">
                    <button class="btn btn-primary help-search-btn" type="submit">
                        <i class="ri-search-2-line me-1"></i>Ara
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4 Quick Action Portal Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="help-card text-center">
            <div class="help-icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                <i class="ri-book-read-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Kullanım Kılavuzu</h5>
            <p class="text-muted small mb-3">Adım adım sistem kullanım talimatları</p>
            <a href="{{ route('help.guide') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold">
                Kılavuzu Oku <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="help-card text-center">
            <div class="help-icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                <i class="ri-video-chat-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Video Eğitimler</h5>
            <p class="text-muted small mb-3">Görsel video dersler ve modül turları</p>
            <a href="{{ route('help.videos') }}" class="btn btn-sm btn-outline-success rounded-pill px-4 fw-bold">
                Videoları İzle <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="help-card text-center">
            <div class="help-icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                <i class="ri-question-answer-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Sıkça Sorulanlar (SSS)</h5>
            <p class="text-muted small mb-3">En çok merak edilen soru & cevaplar</p>
            <a href="{{ route('help.faq') }}" class="btn btn-sm btn-outline-warning rounded-pill px-4 fw-bold">
                SSS İncele <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="help-card text-center">
            <div class="help-icon-box bg-danger bg-opacity-10 text-danger mx-auto mb-3">
                <i class="ri-customer-service-2-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Canlı Destek & Bilet</h5>
            <p class="text-muted small mb-3">Teknik ekibe doğrudan talep iletin</p>
            <a href="{{ route('help.support') }}" class="btn btn-sm btn-outline-danger rounded-pill px-4 fw-bold">
                Destek Talebi Aç <i class="ri-arrow-right-line ms-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Help Categories Grid -->
<div class="help-glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">
            <i class="ri-grid-fill text-indigo me-2" style="color:#4f46e5;"></i>Yardım Konu Kategorileri (SQL Veritabanı)
        </h4>
        <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1 fw-bold">
            Toplam {{ count($categories) }} Kategori
        </span>
    </div>

    <div class="row g-4">
        @foreach($categories as $cat)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('help.category', $cat->slug) }}" class="text-decoration-none">
                    <div class="p-3 border rounded-3 h-100 bg-body">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="help-icon-box bg-{{ $cat->color ?? 'primary' }} bg-opacity-10 text-{{ $cat->color ?? 'primary' }}" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                <i class="{{ $cat->icon ?? 'ri-folder-open-line' }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $cat->name }}</h6>
                                <small class="text-muted">{{ $cat->articles_count ?? 0 }} Makale</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">{{ $cat->description }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- Popular Articles & Recent Updates -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="help-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-star-line text-warning me-2"></i>Popüler Yardım Makaleleri (SQL Veritabanı)
            </h5>

            <div class="list-group list-group-flush">
                @foreach($popularArticles as $art)
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="help-icon-box bg-primary bg-opacity-10 text-primary" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                <i class="ri-article-line"></i>
                            </div>
                            <div>
                                <a href="{{ route('help.article', $art->id) }}" class="text-decoration-none">
                                    <h6 class="fw-bold text-dark mb-1">{{ $art->title }}</h6>
                                </a>
                                <p class="text-muted small mb-0">{{ $art->summary }}</p>
                            </div>
                        </div>
                        <div class="text-end ms-3">
                            <span class="badge bg-{{ $art->category_color ?? 'primary' }} bg-opacity-10 text-{{ $art->category_color ?? 'primary' }} border rounded-pill px-3 py-1 small">
                                {{ $art->category }}
                            </span>
                            <small class="text-muted d-block mt-1"><i class="ri-eye-line me-1"></i>{{ number_format($art->views) }} görüntülenme</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="help-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-notification-3-line text-info me-2"></i>Son Güncellemeler & Senkronizasyon
            </h5>

            <div class="list-group list-group-flush">
                @foreach($recentUpdates as $upd)
                    <div class="list-group-item bg-transparent py-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-dark mb-0 fs-6">{{ $upd->title }}</h6>
                            <small class="text-muted">{{ $upd->date }}</small>
                        </div>
                        <p class="text-muted small mb-0">{{ $upd->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection