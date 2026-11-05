@extends('layouts.master')

@section('title', 'Kur Düzenle')

@section('content')
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">Finansal Yönetim</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.currency.index') }}">Döviz Yönetimi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.currency.show', $currency) }}">{{ $currency->currency_pair }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Düzenle</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Kur Düzenle</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.currency.update', $currency) }}" method="POST" id="currencyForm">
                        @csrf
                        @method('PUT')

                        <!-- Para Birimler (sadece oku) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kaynak Para Birimi</label>
                                <input type="text" class="form-control" value="{{ $currency->from_currency }}" disabled>
                                <small class="text-muted">Para birimler değiştirilemez</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hedef Para Birimi</label>
                                <input type="text" class="form-control" value="{{ $currency->to_currency }}" disabled>
                            </div>
                        </div>

                        <hr>

                        <!-- Kur Tarihi ve Kaynağı -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="effective_date" class="form-label">Kur Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('effective_date') is-invalid @enderror" id="effective_date" name="effective_date" value="{{ $currency->effective_date->format('Y-m-d') }}" required>
                                @error('effective_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label">Kaynak <span class="text-danger">*</span></label>
                                <select class="form-select @error('source') is-invalid @enderror" id="source" name="source" required>
                                    <option value="manual" {{ $currency->source == 'manual' ? 'selected' : '' }}>Manuel</option>
                                    <option value="api" {{ $currency->source == 'api' ? 'selected' : '' }}>API</option>
                                    <option value="bank" {{ $currency->source == 'bank' ? 'selected' : '' }}>Banka</option>
                                </select>
                                @error('source')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Ana Kur -->
                        <div class="mb-3">
                            <label for="rate" class="form-label">Kur <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate" step="0.000001" value="{{ $currency->rate }}" required>
                            @error('rate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alış ve Satış Kurları -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rate_bid" class="form-label">Alış Kuru (Bid)</label>
                                <input type="number" class="form-control @error('rate_bid') is-invalid @enderror" id="rate_bid" name="rate_bid" step="0.000001" value="{{ $currency->rate_bid }}">
                                @error('rate_bid')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="rate_ask" class="form-label">Satış Kuru (Ask)</label>
                                <input type="number" class="form-control @error('rate_ask') is-invalid @enderror" id="rate_ask" name="rate_ask" step="0.000001" value="{{ $currency->rate_ask }}">
                                @error('rate_ask')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Notlar -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ $currency->notes }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                            <a href="{{ route('finance.currency.show', $currency) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kur Analizi -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Kur Analizi</h6>
                </div>
                <div class="card-body">
                    @php
                        $bidRate = $currency->rate_bid ?? $currency->rate;
                        $askRate = $currency->rate_ask ?? $currency->rate;
                        $spread = $askRate - $bidRate;
                        $spreadPercent = ($bidRate > 0) ? (($spread / $bidRate) * 100) : 0;
                        $averageRate = $currency->getAverageRateAttribute();
                    @endphp
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Ortalama Kur</small>
                        <div class="h6">{{ number_format($averageRate, 6, ',', '.') }} ₺</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Spread</small>
                        <div class="h6 text-info">{{ number_format($spread, 6, ',', '.') }} ₺ (%{{ number_format($spreadPercent, 2, ',', '.') }})</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-history"></i> Geçmiş</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-1"><strong>Oluşturulma:</strong> {{ $currency->created_at->format('d.m.Y H:i') }}</p>
                    <p class="small"><strong>Güncellenme:</strong> {{ $currency->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
