@extends('layouts.master')

@section('title', 'Yeni BIM Simülasyonu Oluştur')
@section('topbar-title', 'BIM & Dijital İkiz - Yeni Simülasyon')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* HIGH-CONTRAST ROYAL DARK SLATE & EMERALD THEME */
    .sim-create-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sim-create-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: simCreateGlow 4s linear infinite;
    }

    @keyframes simCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sim-hero-title {
        color: #ffffff !important;
        font-weight: 800 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) !important;
    }

    .sim-hero-subtitle {
        color: #e2e8f0 !important;
        font-size: 1rem !important;
        font-weight: 500 !important;
        line-height: 1.6 !important;
    }

    .sim-create-card {
        border-radius: 20px !important;
        padding: 2.2rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 2rem;
    }

    .custom-input-sim {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-sim:focus {
        background-color: #ffffff !important;
        border-color: #047857 !important;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.15) !important;
    }

    .form-section-header {
        background: #f8fafc !important;
        border-left: 5px solid #047857 !important;
        border-top: 1px solid #e2e8f0 !important;
        border-right: 1px solid #e2e8f0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 14px 18px !important;
        border-radius: 10px !important;
        margin-bottom: 22px !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 1.1rem !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .model-select-item {
        border: 1.5px solid #64748b;
        border-radius: 12px;
        padding: 14px 16px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .model-select-item:hover {
        border-color: #047857;
        background: #f0fdf4;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="sim-create-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('bim.simulations.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Simülasyon Merkezine Dön
                    </a>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        4D ZAMAN & 5D MALİYET SENARYO OLUŞTURUCU
                    </span>
                </div>
                <h2 class="display-6 sim-hero-title mb-2">Yeni BIM Simülasyonu Başlat</h2>
                <p class="sim-hero-subtitle mb-0" style="max-width: 820px;">
                    3D bina modellerini şantiye zaman takvimi ve hakediş bütçe metrajları ile bağlayarak canlı simülasyon senaryoları oluşturun.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3 rounded-4 border text-start" style="background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255, 255, 255, 0.2) !important;">
                    <small class="text-warning fw-extrabold d-block mb-1 fs-6"><i class="ri-lightbulb-fill me-1"></i>Simülasyon Tavsiyesi:</small>
                    <span class="text-white small d-block" style="line-height: 1.5; font-weight: 500;">
                        Zaman simülasyonlarında 4D, maliyet ve metraj hesaplarında 5D türünü tercih ediniz.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN FORM CONTAINER -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="sim-create-card">
                <form method="POST" action="{{ route('bim.simulations.store') }}" id="simulationForm">
                    @csrf

                    <!-- BÖLÜM 1: SİMÜLASYON KİMLİK & PROJE -->
                    <div class="form-section-header">
                        <i class="ri-film-line text-emerald fs-4" style="color: #047857;"></i> Bölüm 1: Simülasyon Kimliği & İlişkili Proje
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-extrabold text-dark fs-6">SİMÜLASYON ADI / SENARYO KONUSU <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control custom-input-sim py-2.5 @error('name') is-invalid @enderror"
                                   value="{{ old('name', request('name')) }}" placeholder="Örn: A-Blok 4D İnşaat Zaman Simülasyonu" required>
                            @error('name')
                                <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="type" class="form-label fw-extrabold text-dark fs-6">SİMÜLASYON TÜRÜ <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select custom-input-sim py-2.5" required>
                                <option value="4D" {{ request('type') == '4D' ? 'selected' : '' }}>⏱️ 4D Zaman (İş Programı & Gantt)</option>
                                <option value="5D" {{ request('type') == '5D' ? 'selected' : '' }}>💰 5D Maliyet (Metraj & Bütçe)</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="project_id" class="form-label fw-extrabold text-dark fs-6">İLGİLİ PROJE <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select custom-input-sim py-2.5" required>
                                <option value="">Proje Seçiniz...</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- BÖLÜM 2: 3D MODEL BAĞLANTILARI -->
                    <div class="form-section-header mt-4">
                        <i class="ri-box-3-line text-warning fs-4" style="color: #d97706 !important;"></i> Bölüm 2: Dahil Edilecek 3D BIM Modelleri
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark fs-6 mb-2">ÇAKIŞMA VE SİMÜLASYON İÇİN MODEL SEÇİNİZ (ÇOKLU SEÇİM)</label>
                        <div class="p-3 border rounded-4 bg-light" style="border-color: #cbd5e1 !important; max-height: 260px; overflow-y: auto;">
                            <div class="row g-3">
                                @forelse($models as $m)
                                    <div class="col-md-4">
                                        <div class="model-select-item">
                                            <div class="form-check d-flex align-items-center justify-content-between">
                                                <div>
                                                    <input class="form-check-input me-2" type="checkbox" name="models[]" value="{{ $m->id }}" id="model-{{ $m->id }}" style="cursor: pointer;">
                                                    <label class="form-check-label fw-extrabold text-dark" for="model-{{ $m->id }}" style="cursor: pointer;">
                                                        {{ $m->name }}
                                                    </label>
                                                    <small class="text-muted d-block font-monospace" style="font-size: 11px;">{{ $m->discipline }} | v{{ $m->version ?? '1.0' }}</small>
                                                </div>
                                                <span class="badge bg-dark font-monospace text-warning px-2.5 py-1 rounded-pill small">
                                                    {{ strtoupper($m->file_type) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-3">
                                        Sistemde kullanılabilir 3D model bulunmuyor. Önce BIM Modelleri sayfasından model yükleyiniz.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- BÖLÜM 3: SİMÜLASYON PARAMETRELERİ -->
                    <div class="form-section-header mt-4">
                        <i class="ri-settings-4-line text-primary fs-4"></i> Bölüm 3: Simülasyon Konfigürasyonu & Zaman Adımı
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-extrabold text-dark fs-6">SİMÜLASYON BAŞLANGIÇ TARİHİ</label>
                            <input type="date" name="start_date" id="start_date" class="form-control custom-input-sim py-2.5" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="time_step" class="form-label fw-extrabold text-dark fs-6">ZAMAN ADIMI HIZI</label>
                            <select name="time_step" id="time_step" class="form-select custom-input-sim py-2.5">
                                <option value="daily" selected>📅 Günlük İlerleme (Day-by-Day)</option>
                                <option value="weekly">📆 Haftalık Görünüm (Weekly)</option>
                                <option value="monthly">🗓️ Aylık Fazlar (Monthly)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('bim.simulations.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">İptal</a>
                        <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2" style="background: #047857; border: none;">
                            <i class="ri-play-circle-fill fs-5"></i> Simülasyonu Başlat ve Derle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection