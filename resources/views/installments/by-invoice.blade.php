@extends('layouts.master')

@section('title', 'Fatura Taksitleri — ' . $invoice->no)

@section('topbar-title', 'Finans - Fatura Taksitleri')

@push('styles')
<style>
    .ins-by-hero {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #065f46 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .ins-by-card {
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .ins-by-card,
    :root:not([data-bs-theme="dark"]) .ins-by-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .ins-by-card {
        background: rgba(30, 41, 59, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

@section('content')

<div class="ins-by-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-emerald bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    <i class="ri-file-paper-line me-1"></i>Fatura: {{ $invoice->no }}
                </span>
            </div>
            <h2 class="fw-bold text-white mb-1">
                {{ $invoice->customer->name ?? 'Müşteri Fatura Taksit Takvimi' }}
            </h2>
            <p class="text-white-50 mb-0">
                Fatura Genel Toplamı: <strong class="text-success fs-5">₺{{ number_format($invoice->amount, 2, ',', '.') }}</strong>
            </p>
        </div>
        <div>
            <a href="{{ route('installments.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>Tüm Taksitler
            </a>
        </div>
    </div>
</div>

<!-- Özet İstatistikler -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ins-by-card text-center">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Fatura Genel Tutarı</small>
            <h3 class="fw-extrabold text-primary mb-0">₺{{ number_format($invoice->amount, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ins-by-card text-center">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Toplam Tahsil Edilen</small>
            <h3 class="fw-extrabold text-success mb-0">₺{{ number_format($installments->sum('paid_amount'), 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ins-by-card text-center">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Kalan Bekleyen Bakiye</small>
            <h3 class="fw-extrabold text-warning mb-0">₺{{ number_format(max(0, $invoice->amount - $installments->sum('paid_amount')), 2, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Taksitler Tablosu -->
<div class="ins-by-card">
    @if($installments->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ri-folder-info-line fs-1 d-block mb-2 text-primary"></i>
            <h5>Bu faturaya tanımlı taksit kaydı bulunmamaktadır.</h5>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Taksit No</th>
                        <th>Taksit Tutarı</th>
                        <th>Tahsil Edilen</th>
                        <th>Kalan Bakiye</th>
                        <th>Son Vade Tarihi</th>
                        <th>Durum</th>
                        <th class="text-end pe-3">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installments as $installment)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 font-monospace fw-bold">
                                    #{{ $installment->installment_number }}
                                </span>
                            </td>
                            <td><strong class="fs-6 text-dark">₺{{ number_format($installment->amount, 2, ',', '.') }}</strong></td>
                            <td class="text-success fw-bold">₺{{ number_format($installment->paid_amount, 2, ',', '.') }}</td>
                            <td class="text-danger fw-bold">₺{{ number_format($installment->remaining_amount, 2, ',', '.') }}</td>
                            <td>
                                {{ $installment->due_date ? $installment->due_date->format('d.m.Y') : '-' }}
                            </td>
                            <td>
                                @if($installment->status == 'paid')
                                    <span class="badge bg-success rounded-pill px-3 py-1 fw-bold">✓ ÖDENDİ</span>
                                @elseif($installment->status == 'partial')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">⏳ KISMEN ÖDENDİ</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-20 text-muted rounded-pill px-3 py-1 fw-bold">⏳ BEKLEYEN</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

