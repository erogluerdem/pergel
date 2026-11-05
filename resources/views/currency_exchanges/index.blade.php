@extends('layouts.master')

@section('title', 'Döviz Alım/Satım & Kur Çevrim Yönetimi')

@section('topbar-title', 'Finans - Döviz İşlemleri')

@push('styles')
<style>
    /* Hero Header Banner */
    .currency-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .currency-hero-banner,
    :root:not([data-bs-theme="dark"]) .currency-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0369a1 50%, #0284c7 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .currency-hero-banner {
        background: linear-gradient(135deg, rgba(3, 105, 161, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .currency-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .currency-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .currency-kpi-card,
    :root:not([data-bs-theme="dark"]) .currency-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .currency-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .currency-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .currency-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .currency-glass-card,
    :root:not([data-bs-theme="dark"]) .currency-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .currency-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $totalExchanges = $exchanges->total();
    $totalFromAmountUSD = $exchanges->where('from_currency', 2)->sum('from_amount');
    $totalFromAmountEUR = $exchanges->where('from_currency', 3)->sum('from_amount');
    $totalCommission = $exchanges->sum('commission_amount');
@endphp

<!-- Hero Header Banner -->
<div class="currency-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-exchange-dollar-line me-1"></i>FX & Treasury Management
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Döviz Alım/Satım & Kur Çevrim Yönetimi</h1>
            <p class="text-white-50 mb-0 fs-6">Banka ve kasa hesapları arası döviz alım/satımları, çapraz kur işlemleri ve komisyon takipleri.</p>
        </div>

        <a href="{{ route('currency_exchanges.create') }}" class="btn btn-sky text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#0284c7;">
            <i class="ri-add-circle-line me-1"></i>Yeni Döviz İşlemi Ekle
        </a>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="currency-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam İşlem Adedi</span>
                    <h2 class="fw-extrabold mb-0 text-sky" style="color:#0284c7;">{{ $totalExchanges }}</h2>
                </div>
                <div class="currency-stat-icon bg-sky bg-opacity-10 text-sky" style="color:#0284c7;">
                    <i class="ri-file-transfer-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="currency-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">İşlem Gören Dolar ($)</span>
                    <h2 class="fw-extrabold mb-0 text-primary">${{ number_format($totalFromAmountUSD, 2, ',', '.') }}</h2>
                </div>
                <div class="currency-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-currency-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="currency-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">İşlem Gören Euro (€)</span>
                    <h2 class="fw-extrabold mb-0 text-success">€{{ number_format($totalFromAmountEUR, 2, ',', '.') }}</h2>
                </div>
                <div class="currency-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-money-euro-box-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="currency-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Banka Komisyonu</span>
                    <h2 class="fw-extrabold mb-0 text-warning">₺{{ number_format($totalCommission, 2, ',', '.') }}</h2>
                </div>
                <div class="currency-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-percent-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="currency-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-exchange-line text-sky me-2" style="color:#0284c7;"></i>Gerçekleştirilen Döviz İşlemleri Listesi
        </h5>
        <span class="badge bg-sky bg-opacity-10 text-sky border border-sky border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#0284c7;">
            {{ $exchanges->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">İşlem Tarihi</th>
                    <th>İlişkili Fatura</th>
                    <th>Çevrim Yönü</th>
                    <th>Çıkış Tutarı</th>
                    <th>İşlem Kuru</th>
                    <th>Giriş Tutarı</th>
                    <th>Banka Komisyonu</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exchanges as $ex)
                    @php
                        $fromCode = \App\Models\Currency::find($ex->from_currency)?->code ?? $ex->from_currency;
                        $toCode = \App\Models\Currency::find($ex->to_currency)?->code ?? $ex->to_currency;
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                <i class="ri-calendar-line me-1"></i>{{ $ex->exchange_date ? $ex->exchange_date->format('d.m.Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark">{{ $ex->invoice?->invoice_number ?? 'Fatura Bağlanmamış' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-sky bg-opacity-10 text-sky border font-monospace px-3 py-1 fw-bold fs-6" style="color:#0284c7;">
                                {{ $fromCode }} <i class="ri-arrow-right-line mx-1"></i> {{ $toCode }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-danger">-{{ number_format($ex->from_amount, 2, ',', '.') }} {{ $fromCode }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace px-2 py-1 fs-6">
                                {{ number_format($ex->exchange_rate, 4, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">+{{ number_format($ex->to_amount, 2, ',', '.') }} {{ $toCode }}</strong>
                        </td>
                        <td>
                            @if($ex->commission_amount > 0)
                                <small class="text-muted">₺{{ number_format($ex->commission_amount, 2, ',', '.') }} (%{{ $ex->commission_percentage }})</small>
                            @else
                                <span class="text-muted small">Yok</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('currency_exchanges.show', $ex) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('currency_exchanges.edit', $ex) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('currency_exchanges.destroy', $ex) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu döviz işlemini silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-exchange-dollar-line fs-1 d-block mb-2 text-sky" style="color:#0284c7;"></i>
                            Henüz döviz işlemi kaydı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $exchanges->total() }} kayıt gösteriliyor</small>
        <div>{{ $exchanges->links() }}</div>
    </div>
</div>

@endsection
