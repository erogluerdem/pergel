@extends('layouts.master')

@section('title', 'Kur Detayı')

@section('content')
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">Finansal Yönetim</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.currency.index') }}">Döviz Yönetimi</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $currency->currency_pair }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Ana Detaylar -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> {{ $currency->currency_pair }} Kuru</h5>
                    <div>
                        <a href="{{ route('finance.currency.edit', $currency) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Düzenle
                        </a>
                        <form action="{{ route('finance.currency.destroy', $currency) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinizden emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Sil
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Genel Bilgiler -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Para Birimler</label>
                                <div class="h6">{{ $currency->from_currency }} / {{ $currency->to_currency }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Kur Kaynağı</label>
                                <div class="h6">{{ $currency->getSourceLabel() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Kur Bilgileri -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Satın Alma Kuru (Bid) (₺)</label>
                                <div class="h5">{{ number_format($currency->rate_bid ?? $currency->rate, 6, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Satış Kuru (Ask) (₺)</label>
                                <div class="h5">{{ number_format($currency->rate_ask ?? $currency->rate, 6, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Spread Hesaplaması -->
                    @php
                        $bidRate = $currency->rate_bid ?? $currency->rate;
                        $askRate = $currency->rate_ask ?? $currency->rate;
                        $spread = $askRate - $bidRate;
                        $spreadPercent = ($bidRate > 0) ? (($spread / $bidRate) * 100) : 0;
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Spread (Fark)</label>
                                <div class="h6">{{ number_format($spread, 6, ',', '.') }} ₺</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Spread Yüzdesi</label>
                                <div class="h6">{{ number_format($spreadPercent, 2, ',', '.') }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarihi Bilgiler -->
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Kur Tarihi</small>
                            <div class="small">{{ $currency->effective_date->format('d.m.Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Oluşturulma Tarihi</small>
                            <div class="small">{{ $currency->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notlar -->
            @if($currency->notes)
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Notlar</h6>
                    </div>
                    <div class="card-body">
                        {{ $currency->notes }}
                    </div>
                </div>
            @endif
        </div>

        <!-- İstatistikler -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Kur Analizi</h6>
                </div>
                <div class="card-body">
                    <!-- Ortalama Kur -->
                    @php
                        $averageRate = $currency->getAverageRateAttribute();
                    @endphp
                    <div class="mb-3">
                        <small class="text-muted d-block">Ortalama Kur</small>
                        <div class="h6">{{ number_format($averageRate, 6, ',', '.') }} ₺</div>
                    </div>

                    <!-- Güncel Durum -->
                    <div class="mb-3">
                        <small class="text-muted d-block">Durum</small>
                        <div>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>

                    <!-- Örnek Hesaplama -->
                    <div class="mt-4">
                        <small class="text-muted d-block mb-2"><strong>Örnek Hesaplama</strong></small>
                        <div class="small">
                            <div class="mb-2">
                                <strong>100 {{ $currency->from_currency }} satın almak:</strong><br>
                                100 × {{ number_format($bidRate, 6, ',', '.') }} = ₺{{ number_format(100 * $bidRate, 2, ',', '.') }}
                            </div>
                            <div>
                                <strong>100 {{ $currency->from_currency }} satmak:</strong><br>
                                100 × {{ number_format($askRate, 6, ',', '.') }} = ₺{{ number_format(100 * $askRate, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
