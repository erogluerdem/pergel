@extends('layouts.master')

@section('title', 'Yeni Satış Hedefi')
@section('topbar-title', 'Yeni Satış Hedefi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-0 py-3">
                <h5 class="mb-0 fw-bold">Yeni Satış Hedefi Oluştur</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales-targets.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="target_amount" class="form-label">Hedef Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" name="target_amount" id="target_amount" class="form-control @error('target_amount') is-invalid @enderror" 
                               value="{{ old('target_amount') }}" step="0.01" min="0" required>
                        @error('target_amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Başlangıç Tarihi <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date') }}" required>
                            @error('start_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Bitiş Tarihi <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date') }}" required>
                            @error('end_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Atanan Kişi</label>
                        <select name="assigned_to" id="assigned_to" class="form-select">
                            <option value="">-- Seçiniz --</option>
                            @foreach($consultants as $consultant)
                                <option value="{{ $consultant->id }}" @selected(old('assigned_to') == $consultant->id)>
                                    {{ $consultant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notlar</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Kaydet
                        </button>
                        <a href="{{ route('sales-targets.index') }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
