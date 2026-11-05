@extends('layouts.master')

@section('title', 'Harcama Beyan Detayı')

@section('topbar-title', 'Personel Harcamaları - Detay')

@push('styles')
<style>
    .hr-expense-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-expense-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-expense-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $refNo = $expense->reference_no ?: ('EXP-2026-' . sprintf('%04d', $expense->id));
    $empName = $expense->employee->name ?? 'Ahmet Yılmaz';
    $amount = $expense->amount ?? 4850.00;
@endphp

<!-- Hero Banner -->
<div class="hr-expense-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.expenses') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Harcamalar Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $refNo }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Harcama Beyan Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">Personel: {{ $empName }} — Departman: {{ $expense->department ?? 'Saha Ekibi' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.hr.expenses.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Beyanı Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="hr-expense-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $expense->title }}</h3>
                    <small class="text-muted">Proje Kodu: {{ $expense->project_code ?: 'PRJ-001' }}</small>
                </div>
                <div>
                    @if($expense->status === 'paid')
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Ödendi</span>
                    @elseif($expense->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onaylandı</span>
                    @elseif($expense->status === 'rejected')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Reddedildi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onay Bekliyor</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Beyan Edilen Tutar</span>
                    <h3 class="fw-extrabold mb-0 text-sky" style="color:#0284c7;">₺{{ number_format($amount, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Harcama Türü</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ \App\Models\HrExpense::getExpenseTypes()[$expense->expense_type] ?? $expense->expense_type }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Ödeme Yöntemi</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $expense->payment_method ?: 'Kişisel Kredi Kartı' }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Harcama Tarihi</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $expense->expense_date ? $expense->expense_date->format('d.m.Y') : '-' }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Harcama Açıklaması & Detaylar</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line;">
                {{ $expense->description ?: 'Açıklama girilmemiş.' }}
            </div>

            @if($expense->approval_notes)
                <h6 class="fw-bold text-dark mb-2">Onay Notları</h6>
                <div class="p-3 border rounded-3 bg-success bg-opacity-10 text-success fw-bold mb-4">
                    {{ $expense->approval_notes }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
