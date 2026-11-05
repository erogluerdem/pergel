@extends('layouts.master')

@section('title', 'Hakediş Cetvelini Düzenle — ' . $progressPayment->payment_number)

@section('topbar-title', 'Hakediş Yönetimi - Düzenle')

@push('styles')
<style>
    .progress-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .progress-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .progress-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="progress-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('progress-payments.show', $progressPayment) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Hakediş Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Hakediş Cetvelini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $progressPayment->payment_number }} — Bekleyen Hakediş Güncellemesi</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="progress-form-card">
            <form action="{{ route('progress-payments.update', $progressPayment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="payment_date" class="form-label fw-bold small text-dark">Hakediş Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', $progressPayment->payment_date ? $progressPayment->payment_date->format('Y-m-d') : '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="due_date" class="form-label fw-bold small text-dark">Son Ödeme Vadesi <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $progressPayment->due_date ? $progressPayment->due_date->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Hakediş Tutarı (KDV Hariç - ₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount', $progressPayment->amount) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="percentage" class="form-label fw-bold small text-dark">Toplam Proje İlerleme Oranı (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="percentage" id="percentage" class="form-control" value="{{ old('percentage', $progressPayment->percentage) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold small text-dark">Hakediş Başlığı & Dönem Açıklaması <span class="text-danger">*</span></label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $progressPayment->description) }}" required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Ek Notlar & İnceleme Açıklamaları</label>
                    <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $progressPayment->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('progress-payments.show', $progressPayment) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
