@extends('layouts.master')

@section('title', $rate->exists ? 'KDV Oranı Düzenle' : 'Yeni KDV Oranı Tanımla')
@section('topbar-title', 'Yönetim - KDV Formu')

@push('styles')
<style>
    /* ROYAL DARK SLATE & EMERALD GOLD THEME */
    .kdv-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kdv-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: kdvGlow 4s linear infinite;
    }

    @keyframes kdvGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .kdv-form-card {
        border-radius: 20px !important;
        padding: 2.2rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
    }

    .custom-input-kdv {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .custom-input-kdv:focus {
        background-color: #ffffff !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">
    <!-- Hero Header Banner -->
    <div class="kdv-hero-banner text-white mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="{{ route('admin.kdv.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">
                        <i class="ri-arrow-left-line me-1"></i>KDV Panosuna Dön
                    </a>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-1">{{ $rate->exists ? 'KDV Oranı Düzenle' : 'Yeni KDV Oranı Tanımla' }}</h2>
                <p class="text-white-50 mb-0 fs-6">Sistem genelindeki teklif, hakediş ve fatura modüllerinde kullanılacak KDV parametrelerini yapılandırın.</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="kdv-form-card">
                <form method="POST" action="{{ $rate->exists ? route('admin.kdv.update', $rate) : route('admin.kdv.store') }}">
                    @csrf
                    @if($rate->exists)
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="rate" class="form-label fw-extrabold text-dark fs-6">KDV ORANI (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text custom-input-kdv border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                <i class="ri-percent-fill text-warning fs-5"></i>
                            </span>
                            <input type="number" name="rate" id="rate" class="form-control custom-input-kdv border-start-0 py-2.5 font-monospace text-primary fw-bold fs-5"
                                   value="{{ old('rate', $rate->rate) }}" min="0" max="100" placeholder="Örn: 20" style="border-radius: 0 10px 10px 0 !important;" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="label" class="form-label fw-extrabold text-dark fs-6">ETİKET / KULLANIM ALANI AÇIKLAMASI</label>
                        <div class="input-group">
                            <span class="input-group-text custom-input-kdv border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                <i class="ri-price-tag-3-line text-emerald fs-5" style="color: #047857;"></i>
                            </span>
                            <input type="text" name="label" id="label" class="form-control custom-input-kdv border-start-0 py-2.5"
                                   value="{{ old('label', $rate->label) }}" placeholder="Örn: Kentsel Dönüşüm Konut Teslimi (%1)" style="border-radius: 0 10px 10px 0 !important;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="sort" class="form-label fw-extrabold text-dark fs-6">SIRA NO (ÖNCELİK)</label>
                        <div class="input-group">
                            <span class="input-group-text custom-input-kdv border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                <i class="ri-sort-asc text-info fs-5"></i>
                            </span>
                            <input type="number" name="sort" id="sort" class="form-control custom-input-kdv border-start-0 py-2.5 font-monospace"
                                   value="{{ old('sort', $rate->sort ?? 1) }}" min="0" style="border-radius: 0 10px 10px 0 !important;">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4 p-3 bg-light rounded-3 border" style="border-color: #cbd5e1 !important;">
                        <input class="form-check-input ms-0 me-3" type="checkbox" name="active" id="active" value="1"
                               {{ old('active', $rate->active ?? true) ? 'checked' : '' }} style="cursor: pointer; width: 44px; height: 24px;">
                        <label for="active" class="form-check-label fw-extrabold text-dark fs-6" style="cursor: pointer;">
                            Sistem Genelinde Aktif Olsun
                        </label>
                        <small class="text-muted d-block mt-1">İşaretlendiğinde tüm form ve hesaplama pencerelerinde varsayılan seçenek olarak listelenir.</small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('admin.kdv.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">İptal</a>
                        <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2" style="background: #047857; border: none;">
                            <i class="ri-save-3-fill fs-5"></i> {{ $rate->exists ? 'Değişiklikleri Kaydet' : 'KDV Oranını Kaydet' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
