@extends('layouts.master')

@section('title', 'Satış Hedefi Düzenle')

@section('topbar-title', 'Satış - Hedef Düzenle')

@push('styles')
<style>
    .sales-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #6366f1 50%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .sales-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .sales-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="sales-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('sales_performance.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Performans Dashboard
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satış Hedefini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Danışman hedef tutarı, dönem güncellemeleri ve açıklama.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="sales-form-card">
            <form action="{{ route('sales_performance.update', $target->id) }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label fw-bold small text-dark">Satış Temsilcisi / Danışman <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ $target->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="target_type" class="form-label fw-bold small text-dark">Hedef Tipi <span class="text-danger">*</span></label>
                        <select name="target_type" id="target_type" class="form-select" required>
                            <option value="revenue" {{ $target->target_type === 'revenue' ? 'selected' : '' }}>Ciro Bazlı (₺)</option>
                            <option value="unit" {{ $target->target_type === 'unit' ? 'selected' : '' }}>Satış Adedi Bazlı</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="target_amount" class="form-label fw-bold small text-dark">Hedef Tutar (₺ / Adet) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="target_amount" id="target_amount" class="form-control fw-bold" value="{{ old('target_amount', $target->target_amount) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Durum <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $target->status === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ $target->status === 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="abandoned" {{ $target->status === 'abandoned' ? 'selected' : '' }}>İptal Edildi</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="period_start" class="form-label fw-bold small text-dark">Dönem Başlangıcı <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" id="period_start" class="form-control" value="{{ \Carbon\Carbon::parse($target->period_start)->format('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="period_end" class="form-label fw-bold small text-dark">Dönem Bitişi <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" id="period_end" class="form-control" value="{{ \Carbon\Carbon::parse($target->period_end)->format('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Hedef Açıklaması</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $target->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('sales_performance.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-indigo text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#6366f1;">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
