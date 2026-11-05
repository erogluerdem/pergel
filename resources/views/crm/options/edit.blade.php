@extends('layouts.master')

@section('title', 'Opsiyon Düzenle — ' . ($option->asset?->name ?? 'Opsiyon'))

@section('topbar-title', 'CRM - Opsiyon Düzenle')

@push('styles')
<style>
    .opt-form-card {
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .opt-form-card,
    :root:not([data-bs-theme="dark"]) .opt-form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .opt-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .opt-form-label {
        font-weight: 700;
        font-size: 0.88rem;
        margin-bottom: 0.4rem;
    }

    html[data-bs-theme="light"] .opt-form-label,
    :root:not([data-bs-theme="dark"]) .opt-form-label { color: #1e293b; }
    html[data-bs-theme="dark"] .opt-form-label { color: #f8fafc; }

    .opt-form-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .opt-icon-ring-warning {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
        color: #f59e0b;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-lg-9 col-xl-8 mx-auto">
        <div class="opt-form-card">
            <!-- Header -->
            <div class="opt-form-header">
                <div class="opt-icon-ring-warning">
                    <i class="ri-edit-box-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Opsiyon Düzenle — {{ $option->asset?->name ?? 'Gayrimenkul' }}</h3>
                    <p class="text-muted mb-0 small">Opsiyon sahibi müşteriyi, ilişkili fırsatı ve şart notlarını güncelleyin.</p>
                </div>
            </div>

            <form action="{{ route('options.update', $option) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_id" class="opt-form-label">Opsiyon Sahibi Müşteri <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
                            <select name="customer_id" id="customer_id" class="form-select form-select-lg @error('customer_id') is-invalid @enderror" required>
                                <option value="">-- Müşteri Seçiniz --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $option->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('customer_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="opportunity_id" class="opt-form-label">İlgili Satış Fırsatı</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-lightbulb-line"></i></span>
                            <select name="opportunity_id" id="opportunity_id" class="form-select form-select-lg">
                                <option value="">-- Fırsat Eşleştirme Yapılmasın --</option>
                                @foreach($opportunities as $opp)
                                    <option value="{{ $opp->id }}" {{ old('opportunity_id', $option->opportunity_id) == $opp->id ? 'selected' : '' }}>
                                        {{ $opp->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="opt-form-label">Notlar ve Özel Şartlar</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                            rows="3">{{ old('notes', $option->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Info summary box -->
                <div class="p-3 bg-light bg-opacity-50 border border-secondary border-opacity-20 rounded-4 mt-4">
                    <div class="fw-bold mb-2 text-warning">
                        <i class="ri-information-fill me-1"></i>Opsiyon Metadataları (Salt Okunur):
                    </div>
                    <div class="row g-2 text-muted small">
                        <div class="col-md-4">
                            <strong>Gayrimenkul:</strong> {{ $option->asset?->name ?? 'Silinmiş' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Başlama Tarihi:</strong> {{ $option->option_date ? $option->option_date->format('d.m.Y') : '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Son Geçerlilik:</strong> {{ $option->expiry_date ? $option->expiry_date->format('d.m.Y') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-opacity-10">
                    <a href="{{ route('options.show', $option) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="ri-close-line me-1"></i>İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

