@extends('layouts.master')

@section('title', 'Taksit Detayı — #' . $installment->installment_number)

@push('styles')
<style>
    .ins-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .ins-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: insShowGlow 4s linear infinite;
    }

    @keyframes insShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ins-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-insshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-insshow:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-show-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .custom-input-ins {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-ins:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.88rem !important;
        margin-bottom: 6px !important;
    }
</style>
@endpush

@section('content')

@php
    $isOverdue = $installment->status === 'pending' && $installment->due_date && $installment->due_date->isPast();
@endphp

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="ins-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($installment->status == 'paid')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">✓ ÖDENDİ & TAHSİL EDİLDİ</span>
                    @elseif($installment->status == 'partial')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-bold">⏳ KİSMEN ÖDENDİ</span>
                    @elseif($isOverdue)
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #ef4444 !important;">🚨 VADESİ GEÇTİ</span>
                    @else
                        <span class="badge bg-secondary text-white rounded-pill px-3 py-2 font-monospace fw-bold">⏳ BEKLEYEN TAKSİT</span>
                    @endif

                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill ins-badge-pill font-monospace">
                        <i class="ri-hashtag text-warning fs-5"></i> Taksit #{{ $installment->installment_number }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">👤 {{ $installment->invoice->customer->name ?? 'Müşteri Taksiti' }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-file-paper-line text-warning me-1"></i> Fatura No: <strong class="text-white fw-bold">{{ $installment->invoice->no ?? 'Fatura Yok' }}</strong> |
                    <i class="ri-calendar-line text-warning me-1"></i> Vade Tarihi: <strong class="text-white fw-bold">{{ $installment->due_date ? $installment->due_date->format('d.m.Y') : '-' }}</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('installments.edit', $installment->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('installments.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Taksit Portföyü
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SPEC KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-insshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAKSİT TUTARI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">₺{{ number_format($installment->amount, 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-money-dollar-circle-fill me-1"></i>Planlanan Taksit Bedeli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-insshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHSİL EDİLEN MİKTAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($installment->paid_amount, 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Ödenen Tahsilat Toplamı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-insshow h-100 border-start border-{{ $installment->remaining_amount > 0 ? 'danger' : 'success' }} border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">KALAN BAKİYE</small>
                        <div class="fw-extrabold text-{{ $installment->remaining_amount > 0 ? 'danger' : 'success' }} font-monospace" style="font-size: 1.7rem;">
                            ₺{{ number_format($installment->remaining_amount, 2, ',', '.') }}
                        </div>
                        <small class="text-{{ $installment->remaining_amount > 0 ? 'danger' : 'success' }} fw-bold">
                            <i class="ri-wallet-3-fill me-1"></i>{{ $installment->remaining_amount > 0 ? 'Tahsil Edilecek Kalan' : 'Bakiye Kapatıldı' }}
                        </small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #{{ $installment->remaining_amount > 0 ? 'fef2f2' : 'f0fdf4' }} !important; border: 1px solid #{{ $installment->remaining_amount > 0 ? 'fecaca' : 'bbf7d0' }} !important;">
                        <i class="ri-wallet-3-fill fs-2" style="color: #{{ $installment->remaining_amount > 0 ? 'dc2626' : '16a34a' }} !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="row">
        <!-- LEFT COLUMN: SPECS & PAYMENT FORM -->
        <div class="col-lg-8">
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-shield-check-line text-warning fs-5"></i>Taksit Detay Künyesi
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Müşteri Unvanı</small>
                            <strong class="fs-5 text-dark fw-extrabold">👤 {{ $installment->invoice->customer->name ?? 'Belirtilmedi' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Bağlı Fatura Kodu</small>
                            <span class="fw-extrabold text-primary font-monospace fs-6">🧾 {{ $installment->invoice->no ?? 'Fatura Yok' }}</span>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Taksit Sıra Numarası</small>
                            <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1 font-monospace fs-6 fw-bold">#{{ $installment->installment_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Son Ödeme Vade Tarihi</small>
                            <span class="fw-extrabold font-monospace fs-6 {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
                                📅 {{ $installment->due_date ? $installment->due_date->format('d.m.Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAYMENT FORM OR RECEIPT -->
            @if($installment->status !== 'paid')
                <div class="card custom-show-card border-warning">
                    <div class="card-header bg-warning text-dark p-3.5">
                        <h5 class="card-title text-dark mb-0 d-flex align-items-center gap-2 fw-extrabold">
                            <i class="ri-refund-line fs-5"></i>Tahsilat Kaydı İşle
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('installments.mark_as_paid', $installment->id) }}" method="POST">
                            @csrf
                            <div class="row g-4 mb-3">
                                <div class="col-md-6">
                                    <label for="paid_amount" class="form-label-custom">Ödenen Tutar (₺) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                                        <input type="number" class="form-control custom-input-ins font-monospace" id="paid_amount" name="paid_amount" 
                                               step="0.01" min="0.01" max="{{ $installment->remaining_amount > 0 ? $installment->remaining_amount : $installment->amount }}" 
                                               value="{{ $installment->remaining_amount > 0 ? $installment->remaining_amount : $installment->amount }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="paid_date" class="form-label-custom">Ödeme Tarihi <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control custom-input-ins" id="paid_date" name="paid_date" 
                                           value="{{ now()->toDateString() }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="payment_method" class="form-label-custom">Ödeme Yöntemi <span class="text-danger">*</span></label>
                                    <select class="form-select custom-input-ins" id="payment_method" name="payment_method" required>
                                        <option value="transfer">🏦 Banka EFT / Havale</option>
                                        <option value="credit_card">💳 Kredi Kartı</option>
                                        <option value="cash">💵 Nakit Tahsilat</option>
                                        <option value="check">📜 Çek / Senet</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="transaction_reference" class="form-label-custom">İşlem / Dekont Referans No</label>
                                    <input type="text" class="form-control custom-input-ins font-monospace" id="transaction_reference" name="transaction_reference" 
                                           placeholder="Örn: DEK-990123 / Banka Dekont No">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm mt-2">
                                <i class="ri-checkbox-circle-fill me-1"></i>Tahsilatı Kaydet & İşle
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card custom-show-card border-success" style="background: #f0fdf4 !important;">
                    <div class="card-header bg-success text-white p-3.5" style="background-color: #16a34a !important;">
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-checkbox-circle-fill fs-5"></i>Tahsilat Tamamlandı
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Ödeme Tarihi</small>
                                <strong class="text-dark font-monospace fs-6">📅 {{ $installment->paid_date ? $installment->paid_date->format('d.m.Y') : '-' }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Ödeme Yöntemi</small>
                                <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace text-uppercase fw-bold" style="background: #16a34a !important;">
                                    {{ $installment->payment_method ?? 'Bank Transfer' }}
                                </span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Dekont / İşlem Referansı</small>
                                <span class="font-monospace fw-extrabold text-dark fs-6">🧾 {{ $installment->transaction_reference ?? 'Dekont Yok' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: PROGRESS & ACTIONS -->
        <div class="col-lg-4">
            <!-- PROGRESS CARD -->
            <div class="card custom-show-card text-center">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-pie-chart-fill text-warning fs-5"></i>Tahsilat İlerlemesi
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $ratio = $installment->amount > 0 ? min(100, round(($installment->paid_amount / $installment->amount) * 100)) : 0;
                    @endphp
                    <div class="progress mb-3" style="height: 18px; border-radius: 8px; background-color: #e2e8f0;">
                        <div class="progress-bar bg-{{ $ratio == 100 ? 'success' : ($ratio > 0 ? 'warning' : 'secondary') }}" 
                             role="progressbar" style="width: {{ $ratio }}%"></div>
                    </div>
                    <span class="fs-4 fw-extrabold text-dark font-monospace">%{{ $ratio }} Tamamlandı</span>
                </div>
            </div>

            @if($installment->notes)
                <div class="card custom-show-card">
                    <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-file-text-line text-warning fs-5"></i>Notlar & Açıklama
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3 rounded-3 text-dark fw-semibold" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.7;">
                            {{ $installment->notes }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- DELETE ACTION CARD -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('installments.edit', $installment->id) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Taksiti Düzenle
                        </a>
                        <form action="{{ route('installments.destroy', $installment->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Taksit Kaydını Sil?', 'Bu taksit kaydı tamamen silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Taksiti Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
