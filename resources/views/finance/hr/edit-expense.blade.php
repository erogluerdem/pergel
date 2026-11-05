@extends('layouts.master')

@section('title', 'Harcama Beyanını Düzenle')

@section('topbar-title', 'Personel Harcamaları - Düzenle')

@push('styles')
<style>
    .hr-expense-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-expense-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-expense-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="hr-expense-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.expenses.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Beyan Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Personel Harcama Beyanını Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Harcama tutarı, başlık ve açıklama güncellemelerini kaydedin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="hr-expense-form-card">
            <form action="{{ route('finance.hr.expenses') }}" method="GET">

                <div class="mb-3">
                    <label for="title" class="form-label fw-bold small text-dark">Harcama Başlığı <span class="text-danger">*</span></label>
                    <input type="text" id="title" class="form-control fw-bold" value="{{ old('title', $expense->title ?? 'Ankara Hızlı Tren & Şantiye Ulaşım Harcırahı') }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Harcama Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="amount" class="form-control text-sky fw-bold" value="{{ old('amount', $expense->amount ?? 4850.00) }}" required style="color:#0284c7;">
                    </div>

                    <div class="col-md-6">
                        <label for="expense_date" class="form-label fw-bold small text-dark">Harcama Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Harcama Açıklaması & Detaylar</label>
                    <textarea id="description" class="form-control" rows="4">{{ old('description', $expense->description ?? 'Güneşli projesi saha denetimi ve bakanlık görüşmesi için tren ve taksi harcamaları.') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.hr.expenses.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
