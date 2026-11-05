@extends('layouts.master')

@section('title', $article->title)

@section('topbar-title', 'Destek - Makale Detay')

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
        padding: 2rem;
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
                <span class="badge bg-white text-dark rounded-pill px-3 py-1 fw-bold">{{ $article->category }}</span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $article->title }}</h1>
            <p class="text-white-50 mb-0 fs-6">Yazar: {{ $article->author }} | {{ $article->views }} görüntülenme</p>
        </div>
    </div>
</div>

<div class="help-glass-card">
    <div class="article-content lead text-dark mb-4">
        {{ $article->content }}
    </div>

    <hr class="my-4">

    <h4 class="fw-bold text-dark mb-3">1. Taşeron Hakediş İşlem Adımları</h4>
    <p class="text-muted">
        Hakediş cetveline imalat miktarlarını girmek için sol navigasyon menüsünden <strong>"Hakediş Yönetimi"</strong> sekmesini seçin. Yeni bir hakediş dönemi başlatmak için <code>+ Yeni Hakediş Ekle</code> butonuna tıklayın.
    </p>

    <div class="alert alert-info border-0 rounded-4 p-3 mb-4">
        <i class="ri-information-line me-2"></i>
        <strong>Teknik İpucu:</strong> Hakediş kaydı oluşturulduğunda %20 KDV oranı ara toplam matraha otomatik olarak ilave edilir.
    </div>

    <h4 class="fw-bold text-dark mb-3">2. Onay & GİB E-Fatura Aşaması</h4>
    <p class="text-muted">
        Teknik kontrol tamamlandıktan sonra hakediş <code>Onaylandı</code> durumuna getirilir. Onaylı hakediş cetvelinden tek tıkla resmi GİB E-Fatura düzenleyebilir ve ETTKN kodlu XML belgesini indirebilirsiniz.
    </p>
</div>

@endsection