@extends('layouts.master')

@section('title', 'Yeni Trend Analizi')

@section('content')
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">Finansal Yönetim</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.trends.dashboard') }}">Trend Analizi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Yeni Trend</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Yeni Trend Oluştur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.trends.store') }}" method="POST" id="trendForm">
                        @csrf

                        <!-- Proje Seçimi -->
                        <div class="mb-3">
                            <label for="project_id" class="form-label">Proje <span class="text-danger">*</span></label>
                            <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id" required>
                                <option value="">-- Proje Seçin --</option>
                                @forelse($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Kullanılabilir proje yok</option>
                                @endforelse
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Trend Tipi -->
                        <div class="mb-3">
                            <label for="trend_type" class="form-label">Trend Tipi <span class="text-danger">*</span></label>
                            <select class="form-select @error('trend_type') is-invalid @enderror" id="trend_type" name="trend_type" required>
                                <option value="">-- Trend Tipi Seçin --</option>
                                <option value="cash_flow" {{ old('trend_type') == 'cash_flow' ? 'selected' : '' }}>Nakit Akım</option>
                                <option value="expenses" {{ old('trend_type') == 'expenses' ? 'selected' : '' }}>Giderler</option>
                                <option value="revenue" {{ old('trend_type') == 'revenue' ? 'selected' : '' }}>Gelirler</option>
                                <option value="profitability" {{ old('trend_type') == 'profitability' ? 'selected' : '' }}>Kârlılık</option>
                            </select>
                            @error('trend_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dönem Başlangıcı -->
                        <div class="mb-3">
                            <label for="period_start" class="form-label">Dönem Başlangıcı <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('period_start') is-invalid @enderror" id="period_start" name="period_start" value="{{ old('period_start') }}" required>
                            @error('period_start')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dönem Sonu -->
                        <div class="mb-3">
                            <label for="period_end" class="form-label">Dönem Sonu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('period_end') is-invalid @enderror" id="period_end" name="period_end" value="{{ old('period_end') }}" required>
                            @error('period_end')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Başlangıç Değeri -->
                        <div class="mb-3">
                            <label for="starting_value" class="form-label">Başlangıç Değeri (₺) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('starting_value') is-invalid @enderror" id="starting_value" name="starting_value" step="0.01" value="{{ old('starting_value') }}" required>
                            @error('starting_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bitiş Değeri -->
                        <div class="mb-3">
                            <label for="ending_value" class="form-label">Bitiş Değeri (₺) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('ending_value') is-invalid @enderror" id="ending_value" name="ending_value" step="0.01" value="{{ old('ending_value') }}" required>
                            @error('ending_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Açıklama -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Kaydet
                            </button>
                            <a href="{{ route('finance.trends.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Yardım Sayfası -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Bilgi</h6>
                </div>
                <div class="card-body">
                    <h6>Trend Oluşturma</h6>
                    <p class="small">Finansal trend analiziyle proje finansmanının zaman içindeki değişimini takip edin.</p>
                    
                    <h6 class="mt-3">Trend Tipleri</h6>
                    <ul class="small">
                        <li><strong>Nakit Akım</strong>: Nakit girişi-çıkışı</li>
                        <li><strong>Giderler</strong>: Harcama trendleri</li>
                        <li><strong>Gelirler</strong>: Gelir trendleri</li>
                        <li><strong>Kârlılık</strong>: Kar/zarar trendleri</li>
                    </ul>

                    <h6 class="mt-3">İpuçları</h6>
                    <ul class="small">
                        <li>Dönem sonu, dönem başlangıcından sonra olmalıdır</li>
                        <li>Başlangıç ve bitiş değerlerini doğru girin</li>
                        <li>Açıklama alanına trend analizi notlarını yazabilirsiniz</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
