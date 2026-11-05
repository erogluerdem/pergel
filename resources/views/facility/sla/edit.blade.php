@extends('layouts.master')

@section('title', 'SLA Hedefini Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit"></i> SLA Hedefini Düzenle
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('facility.sla.update', $slaConfig->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="project_id" class="form-label">Proje <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror" required disabled>
                                @if(isset($projects))
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ $slaConfig->project_id == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" name="project_id" value="{{ $slaConfig->project_id }}">
                            <small class="form-text text-muted">Proje değiştiremezsiniz</small>
                        </div>

                        <div class="mb-3">
                            <label for="issue_category" class="form-label">Sorun Kategorisi <span class="text-danger">*</span></label>
                            <select name="issue_category" id="issue_category" class="form-select @error('issue_category') is-invalid @enderror" required>
                                <option value="acil" {{ $slaConfig->issue_category == 'acil' ? 'selected' : '' }}>🔴 Acil</option>
                                <option value="yuksek" {{ $slaConfig->issue_category == 'yuksek' ? 'selected' : '' }}>🟠 Yüksek</option>
                                <option value="normal" {{ $slaConfig->issue_category == 'normal' ? 'selected' : '' }}>🟡 Normal</option>
                                <option value="dusuk" {{ $slaConfig->issue_category == 'dusuk' ? 'selected' : '' }}>🟢 Düşük</option>
                            </select>
                            @error('issue_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="response_time" class="form-label">Yanıt Süresi (Saat) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="response_time" id="response_time" 
                                           class="form-control @error('response_time') is-invalid @enderror" 
                                           min="1" max="72" value="{{ old('response_time', $slaConfig->response_time) }}" required>
                                    <span class="input-group-text">saat</span>
                                </div>
                                @error('response_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="resolution_time" class="form-label">Çözüm Süresi (Saat) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="resolution_time" id="resolution_time" 
                                           class="form-control @error('resolution_time') is-invalid @enderror" 
                                           min="1" max="168" value="{{ old('resolution_time', $slaConfig->resolution_time) }}" required>
                                    <span class="input-group-text">saat</span>
                                </div>
                                @error('resolution_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="priority_weight" class="form-label">Ağırlık Puanı (1-100) <span class="text-danger">*</span></label>
                            <input type="number" name="priority_weight" id="priority_weight" 
                                   class="form-control @error('priority_weight') is-invalid @enderror" 
                                   min="1" max="100" value="{{ old('priority_weight', $slaConfig->priority_weight) }}" required>
                            @error('priority_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama (İsteğe Bağlı)</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description', $slaConfig->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       value="1" {{ old('is_active', $slaConfig->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('facility.sla.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
