@extends('layouts.master')

@section('title', isset($forecast) ? 'Tahmin Düzenle' : 'Yeni Tahmin Oluştur')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-crystal-ball"></i>
                {{ isset($forecast) ? 'Tahmin Düzenle' : 'Yeni Tahmin Oluştur' }}
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('finance.forecast.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> İptal
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" class="card border-0 shadow-sm" action="{{ isset($forecast) ? route('finance.forecast.update', $forecast) : route('finance.forecast.store') }}">
                @csrf
                @if(isset($forecast))
                    @method('PUT')
                @endif

                <div class="card-body">
                    <!-- Proje Seçimi -->
                    <div class="mb-3">
                        <label class="form-label">Proje <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                            <option value="">Proje Seçiniz</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ isset($forecast) && $forecast->project_id === $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahmin Tarihi -->
                    <div class="mb-3">
                        <label class="form-label">Tahmin Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="forecast_date" class="form-control @error('forecast_date') is-invalid @enderror" 
                               value="{{ isset($forecast) ? $forecast->forecast_date->format('Y-m-d') : '' }}" required>
                        @error('forecast_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Planlanan Net Akış -->
                    <div class="mb-3">
                        <label class="form-label">Planlanan Net Nakit Akışı (₺) <span class="text-danger">*</span></label>
                        <input type="number" name="planned_net_flow" class="form-control @error('planned_net_flow') is-invalid @enderror" 
                               placeholder="0.00" step="0.01" value="{{ isset($forecast) ? $forecast->planned_net_flow : '' }}" required>
                        @error('planned_net_flow')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fiili Net Akış -->
                    <div class="mb-3">
                        <label class="form-label">Fiili Net Nakit Akışı (₺)</label>
                        <input type="number" name="actual_net_flow" class="form-control @error('actual_net_flow') is-invalid @enderror" 
                               placeholder="0.00" step="0.01" value="{{ isset($forecast) ? $forecast->actual_net_flow : '' }}">
                        @error('actual_net_flow')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Doğruluk Puanı -->
                    <div class="mb-3">
                        <label class="form-label">Doğruluk Puanı (%)</label>
                        <input type="number" name="accuracy_score" class="form-control @error('accuracy_score') is-invalid @enderror" 
                               placeholder="0-100" min="0" max="100" step="0.01" value="{{ isset($forecast) ? $forecast->accuracy_score : '' }}">
                        @error('accuracy_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Güven Aralığı -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Güven Aralığı (%)</label>
                            <input type="number" name="confidence_interval" class="form-control @error('confidence_interval') is-invalid @enderror" 
                                   placeholder="0-100" min="0" max="100" step="0.01" value="{{ isset($forecast) ? $forecast->confidence_interval : '' }}">
                            @error('confidence_interval')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Veri Yeterliliği (%)</label>
                            <input type="number" name="data_sufficiency" class="form-control @error('data_sufficiency') is-invalid @enderror" 
                                   placeholder="0-100" min="0" max="100" step="0.01" value="{{ isset($forecast) ? $forecast->data_sufficiency : '' }}">
                            @error('data_sufficiency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Durum -->
                    <div class="mb-3">
                        <label class="form-label">Durum</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ isset($forecast) && $forecast->status === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ isset($forecast) && $forecast->status === 'inactive' ? 'selected' : '' }}>İnaktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Açıklamalar -->
                    <div class="mb-3">
                        <label class="form-label">Açıklamalar</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" 
                                  placeholder="Tahmin hakkında ek bilgiler...">{{ isset($forecast) ? $forecast->notes : '' }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($forecast) ? 'Güncelle' : 'Oluştur' }}
                    </button>
                    <a href="{{ route('finance.forecast.index') }}" class="btn btn-secondary">İptal</a>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="mb-3">📋 Form Yardımı</h6>
                    <ul class="small">
                        <li><strong>Proje:</strong> Tahmin yapılacak projeyi seçiniz</li>
                        <li><strong>Tahmin Tarihi:</strong> Tahminin geçerli olduğu tarih</li>
                        <li><strong>Planlanan Akış:</strong> Beklenen nakit akışı</li>
                        <li><strong>Fiili Akış:</strong> Gerçekleşen akış (sonradan güncellenebilir)</li>
                        <li><strong>Doğruluk:</strong> Tahmin doğruluğu yüzdesi</li>
                        <li><strong>Güven Aralığı:</strong> Tahmine olan güven derecesi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
