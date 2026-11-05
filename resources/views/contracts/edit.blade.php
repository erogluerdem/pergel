@extends('layouts.master')

@section('title', $contract->name . ' — Sözleşmeyi Düzenle')
@section('topbar-title', 'Sözleşme Yönetimi - Düzenle')

@push('styles')
<style>
    /* ROYAL DARK SLATE & AMBER GOLD THEME */
    .contract-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .contract-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #10b981, #3b82f6, #d97706);
        background-size: 300% 100%;
        animation: contractGlow 4s linear infinite;
    }

    @keyframes contractGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .contract-form-card {
        border-radius: 20px !important;
        padding: 2.2rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 2rem;
    }

    .custom-input-contract {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .custom-input-contract:focus {
        background-color: #ffffff !important;
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
    }

    .form-section-title {
        color: #0f172a;
        font-weight: 800;
        font-size: 1.1rem;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="contract-hero-banner text-white mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">
                        <i class="ri-arrow-left-line me-1"></i>Sözleşmeye Dön
                    </a>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-1">Sözleşme Bilgilerini Düzenle</h2>
                <p class="text-white-50 mb-0 fs-6">{{ $contract->name }} ({{ $contract->number ?? 'SZ-KOD-YOK' }})</p>
            </div>
        </div>
    </div>

    <!-- MAIN FORM CONTAINER -->
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="contract-form-card">
                <form method="POST" action="{{ route('contracts.update', $contract->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-section-title">
                        <i class="ri-edit-fill text-warning fs-4"></i> Sözleşme Kimlik & Firma Bilgileri
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="name" class="form-label fw-extrabold text-dark">SÖZLEŞME ADI / KONUSU <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-file-text-line text-warning fs-5"></i>
                                </span>
                                <input type="text" name="name" id="name" class="form-control custom-input-contract border-start-0 py-2.5"
                                       value="{{ old('name', $contract->name) }}" required style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label for="number" class="form-label fw-extrabold text-dark">SÖZLEŞME NO / KODU</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-barcode-line text-muted fs-5"></i>
                                </span>
                                <input type="text" name="number" id="number" class="form-control custom-input-contract border-start-0 py-2.5 font-monospace"
                                       value="{{ old('number', $contract->number) }}" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label for="company" class="form-label fw-extrabold text-dark">YÜKLENİCİ / TAŞERON FİRMA</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-building-line text-primary fs-5"></i>
                                </span>
                                <input type="text" name="company" id="company" class="form-control custom-input-contract border-start-0 py-2.5"
                                       value="{{ old('company', $contract->company) }}" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="project_id" class="form-label fw-extrabold text-dark">İLGİLİ PROJE</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-building-2-line text-info fs-5"></i>
                                </span>
                                <select name="project_id" id="project_id" class="form-select custom-input-contract border-start-0 py-2.5" style="border-radius: 0 10px 10px 0 !important;">
                                    <option value="">Tüm Projeler (Genel)</option>
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ old('project_id', $contract->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="type" class="form-label fw-extrabold text-dark">SÖZLEŞME TÜRÜ</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                                    <i class="ri-price-tag-3-line text-success fs-5"></i>
                                </span>
                                <select name="type" id="type" class="form-select custom-input-contract border-start-0 py-2.5" style="border-radius: 0 10px 10px 0 !important;">
                                    <option value="Taşeron" {{ old('type', $contract->type) == 'Taşeron' ? 'selected' : '' }}>🔨 Taşeron Yüklenici</option>
                                    <option value="Tedarik" {{ old('type', $contract->type) == 'Tedarik' ? 'selected' : '' }}>📦 Malzeme & Tedarik</option>
                                    <option value="Hizmet" {{ old('type', $contract->type) == 'Hizmet' ? 'selected' : '' }}>📐 Hizmet & Müşavirlik</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title mt-4">
                        <i class="ri-money-dollar-circle-line text-success fs-4"></i> Finansal Tutar & Durum Güncellemesi
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="value" class="form-label fw-extrabold text-dark">SÖZLEŞME BEDELİ (TL)</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">₺</span>
                                <input type="number" step="0.01" name="value" id="value" class="form-control custom-input-contract border-start-0 py-2.5 font-monospace text-primary fw-bold fs-5"
                                       value="{{ old('value', $contract->value) }}" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="paid" class="form-label fw-extrabold text-dark">ÖDENEN HAKEDİŞ (TL)</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">₺</span>
                                <input type="number" step="0.01" name="paid" id="paid" class="form-control custom-input-contract border-start-0 py-2.5 font-monospace text-success fw-bold fs-5"
                                       value="{{ old('paid', $contract->paid) }}" style="border-radius: 0 10px 10px 0 !important;">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="start_date" class="form-label fw-extrabold text-dark">BAŞLANGIÇ TARİHİ</label>
                            <input type="date" name="start_date" id="start_date" class="form-control custom-input-contract py-2.5"
                                   value="{{ old('start_date', $contract->start_date ? $contract->start_date->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="end_date" class="form-label fw-extrabold text-dark">BİTİŞ TARİHİ</label>
                            <input type="date" name="end_date" id="end_date" class="form-control custom-input-contract py-2.5"
                                   value="{{ old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-extrabold text-dark">SÖZLEŞME DURUMU</label>
                        <select name="status" id="status" class="form-select custom-input-contract py-2.5">
                            <option value="active" {{ old('status', strtolower($contract->status)) == 'active' ? 'selected' : '' }}>🟢 Aktif Kullanımda</option>
                            <option value="pending" {{ old('status', strtolower($contract->status)) == 'pending' ? 'selected' : '' }}>🟡 Onay Bekliyor</option>
                            <option value="completed" {{ old('status', strtolower($contract->status)) == 'completed' ? 'selected' : '' }}>🔵 Tamamlandı (Feshedildi / Kapandı)</option>
                            <option value="cancelled" {{ old('status', strtolower($contract->status)) == 'cancelled' ? 'selected' : '' }}>🔴 İptal Edildi</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-extrabold text-dark">SÖZLEŞME NOTLARI & HÜKÜMLER</label>
                        <textarea name="notes" id="notes" class="form-control custom-input-contract" rows="6">{{ old('notes', $contract->notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">İptal</a>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2" style="background: #f59e0b; border: none;">
                            <i class="ri-save-3-fill fs-5"></i> Değişiklikleri Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
