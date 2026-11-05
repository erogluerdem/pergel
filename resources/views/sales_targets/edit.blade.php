@extends('layouts.master')

@section('title', 'Hedef Düzenle')
@section('topbar-title', 'Hedef Düzenle')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 fw-bold">{{ $salesTarget->title }} - Düzenle</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales-targets.update', $salesTarget) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $salesTarget->title) }}" required>
                        @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="target_amount" class="form-label">Hedef Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" name="target_amount" id="target_amount" class="form-control @error('target_amount') is-invalid @enderror" 
                               value="{{ old('target_amount', $salesTarget->target_amount) }}" step="0.01" min="0" required>
                        @error('target_amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Başlangıç Tarihi <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date', $salesTarget->start_date?->format('Y-m-d')) }}" required>
                            @error('start_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Bitiş Tarihi <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date', $salesTarget->end_date?->format('Y-m-d')) }}" required>
                            @error('end_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Atanan Kişi</label>
                        <select name="assigned_to" id="assigned_to" class="form-select">
                            <option value="">-- Seçiniz --</option>
                            @foreach($consultants as $consultant)
                                <option value="{{ $consultant->id }}" @selected(old('assigned_to', $salesTarget->assigned_to) == $consultant->id)>
                                    {{ $consultant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notlar</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $salesTarget->notes) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync me-2"></i>Güncelle
                        </button>
                        <a href="{{ route('sales_targets.show', $salesTarget) }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
