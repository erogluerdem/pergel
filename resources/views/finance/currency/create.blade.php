@extends('layouts.master')

@section('title', 'Yeni Kur')

@section('content')
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">Finansal Yönetim</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.currency.index') }}">Döviz Yönetimi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Yeni Kur</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Yeni Kur Ekle</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.currency.store') }}" method="POST" id="currencyForm">
                        @csrf

                        <!-- Para Birimi Kodları -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="from_currency" class="form-label">Kaynak Para Birimi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('from_currency') is-invalid @enderror" id="from_currency" name="from_currency" maxlength="3" placeholder="USD" value="{{ old('from_currency') }}" required>
                                @error('from_currency')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">3 haneli kod (örn: USD)</small>
                            </div>
                            <div class="col-md-6">
                                <label for="to_currency" class="form-label">Hedef Para Birimi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('to_currency') is-invalid @enderror" id="to_currency" name="to_currency" maxlength="3" placeholder="TRY" value="{{ old('to_currency') }}" required>
                                @error('to_currency')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">3 haneli kod (örn: TRY)</small>
                            </div>
                        </div>

                        <!-- Kur Tarihi ve Kaynağı -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="effective_date" class="form-label">Kur Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('effective_date') is-invalid @enderror" id="effective_date" name="effective_date" value="{{ old('effective_date', now()->format('Y-m-d')) }}" required>
                                @error('effective_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="source" class="form-label">Kaynak <span class="text-danger">*</span></label>
                                <select class="form-select @error('source') is-invalid @enderror" id="source" name="source" required>
                                    <option value="">-- Seçim Yapınız --</option>
                                    <option value="manual" {{ old('source') == 'manual' ? 'selected' : '' }}>Manuel</option>
                                    <option value="api" {{ old('source') == 'api' ? 'selected' : '' }}>API</option>
                                    <option value="bank" {{ old('source') == 'bank' ? 'selected' : '' }}>Banka</option>
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
                            <input type="number" class="form-control @error('rate') is-invalid @enderror" id="rate" name="rate" step="0.000001" value="{{ old('rate') }}" required>
                            @error('rate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alış ve Satış Kurları -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rate_bid" class="form-label">Alış Kuru (Bid)</label>
                                <input type="number" class="form-control @error('rate_bid') is-invalid @enderror" id="rate_bid" name="rate_bid" step="0.000001" value="{{ old('rate_bid') }}">
                                @error('rate_bid')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="rate_ask" class="form-label">Satış Kuru (Ask)</label>
                                <input type="number" class="form-control @error('rate_ask') is-invalid @enderror" id="rate_ask" name="rate_ask" step="0.000001" value="{{ old('rate_ask') }}">
                                @error('rate_ask')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Notlar -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notlar</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                            <a href="{{ route('finance.currency.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bilgi -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Yardım</h6>
                </div>
                <div class="card-body">
                    <h6>Kur Ekleme</h6>
                    <p class="small">Proje finansmanında kullanılacak döviz kurlarını tanımlayın.</p>
                    
                    <h6 class="mt-3">Gerekli Alanlar</h6>
                    <ul class="small">
                        <li>Kaynak para birimi (örn: USD)</li>
                        <li>Hedef para birimi (örn: TRY)</li>
                        <li>Kur değeri</li>
                        <li>Kur tarihi</li>
                    </ul>

                    <h6 class="mt-3">İpuçları</h6>
                    <ul class="small">
                        <li>Alış kuru ≤ Satış kuru olmalıdır</li>
                        <li>TCMB kuru referans alabilirsiniz</li>
                        <li>Kurlar 6 ondalık hanede kaydedilir</li>
                        <li>Spread (fark) otomatik hesaplanır</li>
                    </ul>

                    <h6 class="mt-3">Örnek</h6>
                    <div class="small bg-light p-2 rounded">
                        <p class="mb-1"><strong>USD/TRY</strong></p>
                        <p class="mb-1">Kur: 32.50</p>
                        <p class="mb-1">Alış: 32.40</p>
                        <p>Satış: 32.60</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
