@extends('layouts.master')

@section('title', 'Yeni Kapanış Süreci Başlat')

@section('topbar-title', 'Proje Yönetimi - Yeni Kapanış')

@push('styles')
<style>
    .closeout-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .closeout-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .closeout-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="closeout-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('closeout.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Kapanış Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Proje Kapanış Süreci Başlat</h1>
            <p class="text-white-50 mb-0 fs-6">Teslimat ve geçici kabul kontrol listesi oluşturulacak projeyi seçin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="closeout-form-card">
            <form action="{{ route('closeout.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="project_id" class="form-label fw-bold small text-dark">Kapanış Başlatılacak Proje <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-building-line text-indigo" style="color:#4338ca;"></i></span>
                        <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                            <option value="">Proje seçin...</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('project_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted mt-2 d-block">
                        <i class="ri-information-line me-1"></i>Proje seçildiğinde 6 temel teslimat ve kontrol listesi maddesi ("As-Built" Çizimler, Garanti Belgeleri, Kullanım Kılavuzları, Resmi İskan Belgeleri vb.) otomatik olarak oluşturulacaktır.
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('closeout.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-indigo text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#4338ca;">
                        <i class="ri-play-circle-line me-1"></i>Kapanış Sürecini Başlat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
