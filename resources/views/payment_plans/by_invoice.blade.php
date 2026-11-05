@extends('layouts.master')

@section('title', 'Faturaya Ait Ödeme Planları — ' . $invoice->no)

@section('topbar-title', 'Finans - Fatura Ödeme Planları')

@push('styles')
<style>
    .pp-by-hero {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }
</style>
@endpush

@section('content')

<div class="pp-by-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-indigo bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    <i class="ri-file-paper-line me-1"></i>Fatura: {{ $invoice->no }}
                </span>
            </div>
            <h2 class="fw-bold text-white mb-1">
                {{ $invoice->customer->name ?? 'Müşteri Fatura Ödeme Planları' }}
            </h2>
            <p class="text-white-50 mb-0">
                Fatura Genel Toplamı: <strong class="text-success fs-5">₺{{ number_format($invoice->amount, 2, ',', '.') }}</strong>
            </p>
        </div>
        <div>
            <a href="{{ route('payment-plans.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-add-line me-1"></i>Bu Faturaya Yeni Plan Ekle
            </a>
            <a href="{{ route('payment-plans.index') }}" class="btn btn-outline-light rounded-pill px-4 ms-2">
                <i class="ri-arrow-left-line me-1"></i>Tüm Planlar
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    @if($plans->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ri-folder-info-line fs-1 d-block mb-2 text-primary"></i>
            <h5>Bu faturaya tanımlı ödeme planı bulunmamaktadır.</h5>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Plan No</th>
                        <th>Vadelendirilen Tutar</th>
                        <th>Taksit Yapısı</th>
                        <th>Başlangıç Tarihi</th>
                        <th>Durum</th>
                        <th class="text-end pe-3">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 font-monospace fw-bold">
                                    {{ $plan->plan_no }}
                                </span>
                            </td>
                            <td><strong class="text-success fs-6">₺{{ number_format($plan->total_amount, 2, ',', '.') }}</strong></td>
                            <td>{{ $plan->installment_count }} Taksit ({{ $plan->payment_interval_days }} Gün Periyot)</td>
                            <td>{{ $plan->start_date ? $plan->start_date->format('d.m.Y') : '-' }}</td>
                            <td>
                                @if($plan->status == 'completed')
                                    <span class="badge bg-success rounded-pill px-3 py-1 fw-bold">🎉 TAMAMLANDI</span>
                                @elseif($plan->status == 'cancelled')
                                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">❌ İPTAL</span>
                                @else
                                    <span class="badge bg-info text-white rounded-pill px-3 py-1 fw-bold">✓ AKTİF</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('payment-plans.show', $plan->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $plans->links() }}</div>
    @endif
</div>

@endsection
