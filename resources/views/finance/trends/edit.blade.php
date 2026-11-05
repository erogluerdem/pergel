@extends('layouts.master')

@section('title', 'Trend Düzenle')

@section('content')
<div class="container-fluid mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">Finansal Yönetim</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.trends.dashboard') }}">Trend Analizi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('finance.trends.show', $trend) }}">{{ $trend->project->name ?? 'Trend' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Düzenle</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Trend Düzenle</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.trends.update', $trend) }}" method="POST" id="trendForm">
                        @csrf
                        @method('PUT')

                        <!-- Proje Seçimi (sadece oku) -->
                        <div class="mb-3">
                            <label for="project_id" class="form-label">Proje</label>
                            <input type="text" class="form-control" value="{{ $trend->project->name ?? 'N/A' }}" disabled>
                            <small class="text-muted">Proje değiştirilemez</small>
                        </div>

                        <!-- Trend Tipi -->
                        <div class="mb-3">
                            <label for="trend_type" class="form-label">Trend Tipi <span class="text-danger">*</span></label>
                            <select class="form-select @error('trend_type') is-invalid @enderror" id="trend_type" name="trend_type" required>
                                <option value="cash_flow" {{ $trend->trend_type == 'cash_flow' ? 'selected' : '' }}>Nakit Akım</option>
                                <option value="expenses" {{ $trend->trend_type == 'expenses' ? 'selected' : '' }}>Giderler</option>
                                <option value="revenue" {{ $trend->trend_type == 'revenue' ? 'selected' : '' }}>Gelirler</option>
                                <option value="profitability" {{ $trend->trend_type == 'profitability' ? 'selected' : '' }}>Kârlılık</option>
                            </select>
                            @error('trend_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dönem Başlangıcı -->
                        <div class="mb-3">
                            <label for="period_start" class="form-label">Dönem Başlangıcı <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('period_start') is-invalid @enderror" id="period_start" name="period_start" value="{{ $trend->period_start->format('Y-m-d') }}" required>
                            @error('period_start')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dönem Sonu -->
                        <div class="mb-3">
                            <label for="period_end" class="form-label">Dönem Sonu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('period_end') is-invalid @enderror" id="period_end" name="period_end" value="{{ $trend->period_end->format('Y-m-d') }}" required>
                            @error('period_end')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Başlangıç Değeri -->
                        <div class="mb-3">
                            <label for="starting_value" class="form-label">Başlangıç Değeri (₺) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('starting_value') is-invalid @enderror" id="starting_value" name="starting_value" step="0.01" value="{{ $trend->starting_value }}" required>
                            @error('starting_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bitiş Değeri -->
                        <div class="mb-3">
                            <label for="ending_value" class="form-label">Bitiş Değeri (₺) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('ending_value') is-invalid @enderror" id="ending_value" name="ending_value" step="0.01" value="{{ $trend->ending_value }}" required>
                            @error('ending_value')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Açıklama -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ $trend->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                            <a href="{{ route('finance.trends.show', $trend) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- İstatistikler -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Trend Analizi</h6>
                </div>
                <div class="card-body">
                    @php
                        $difference = $trend->ending_value - $trend->starting_value;
                        $percentChange = ($trend->starting_value > 0) ? (($difference / $trend->starting_value) * 100) : 0;
                    @endphp
                    
                    <div class="mb-3">
                        <small class="text-muted">Başlangıç Değeri</small>
                        <div class="h6">₺ {{ number_format($trend->starting_value, 2, ',', '.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Bitiş Değeri</small>
                        <div class="h6">₺ {{ number_format($trend->ending_value, 2, ',', '.') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Fark</small>
                        <div class="h6 {{ $difference >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $difference >= 0 ? '+' : '' }}₺ {{ number_format($difference, 2, ',', '.') }}
                        </div>
                    </div>
                    
                    <div>
                        <small class="text-muted">Değişim Yüzdesi</small>
                        <div class="h6 {{ $percentChange >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $percentChange >= 0 ? '+' : '' }}{{ number_format($percentChange, 2, ',', '.') }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Bilgi</h6>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Oluşturulma Tarihi:</strong> {{ $trend->created_at->format('d.m.Y H:i') }}</p>
                    <p class="small mb-2"><strong>Son Güncellenme:</strong> {{ $trend->updated_at->format('d.m.Y H:i') }}</p>
                    <p class="small"><strong>Dönem:</strong> {{ $trend->period_start->format('d.m.Y') }} - {{ $trend->period_end->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
