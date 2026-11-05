@extends('layouts.master')

@section('title', 'Kalite Standardı Düzenle - ' . $standard->code)

@section('topbar-title', 'Kalite Standartları - Düzenle')

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
        padding: 2rem;
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
                <a href="{{ route('admin.standards.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Standart Listesi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Standart Düzenle: {{ $standard->code }}</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $standard->name }} parametrelerini güncelleyin.</p>
        </div>
    </div>
</div>

<div class="std-adm-glass-card">
    <form method="POST" action="{{ route('admin.standards.update', $standard->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Standart Kodu <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control font-monospace @error('code') is-invalid @enderror" value="{{ old('code', $standard->code) }}" required>
                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Standart Adı <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $standard->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-dark">Kategori <span class="text-danger">*</span></label>
                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                    @foreach(\App\Models\Standard::CATEGORIES as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $standard->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-dark">Standart Türü <span class="text-danger">*</span></label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    @foreach(\App\Models\Standard::TYPES as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $standard->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-dark">Zorunluluk Seviyesi <span class="text-danger">*</span></label>
                <select name="compliance_level" class="form-select @error('compliance_level') is-invalid @enderror" required>
                    @foreach(\App\Models\Standard::COMPLIANCE_LEVELS as $key => $label)
                        <option value="{{ $key }}" {{ old('compliance_level', $standard->compliance_level) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('compliance_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-dark">Durum <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(\App\Models\Standard::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $standard->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-dark">Versiyon / Sürüm</label>
                <input type="text" name="version" class="form-control" value="{{ old('version', $standard->version) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-dark">Öncelik Derecesi</label>
                <select name="priority" class="form-select">
                    @foreach(\App\Models\Standard::PRIORITIES as $key => $label)
                        <option value="{{ $key }}" {{ old('priority', $standard->priority) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Yürürlük Tarihi</label>
                <input type="date" name="effective_date" class="form-control" value="{{ old('effective_date', $standard->effective_date ? \Carbon\Carbon::parse($standard->effective_date)->format('Y-m-d') : '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Geçerlilik Bitiş Tarihi</label>
                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $standard->expiry_date ? \Carbon\Carbon::parse($standard->expiry_date)->format('Y-m-d') : '') }}">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-dark">Açıklama ve Şartname Özeti</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $standard->description) }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('admin.standards.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
            <button type="submit" class="btn btn-teal text-white rounded-pill px-5 fw-bold" style="background:#0d9488;">
                <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
            </button>
        </div>
    </form>
</div>

@endsection
