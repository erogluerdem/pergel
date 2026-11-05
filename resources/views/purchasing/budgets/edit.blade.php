@extends('layouts.master')

@section('title', 'Bütçe Düzenle')
@section('topbar-title', '💰 Bütçe Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="page-title">💰 Bütçe Düzenle</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('purchasing.budgets.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchasing.budgets.update', $budget) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Proje</label>
                        <input type="text" class="form-control" value="{{ $budget->project->name }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control" value="{{ $budget->category }}" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Orijinal Tahsis (₺)</label>
                        <input type="text" class="form-control" value="₺{{ number_format($budget->allocated_amount, 2) }}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harcanan (₺)</label>
                        <input type="text" class="form-control" value="₺{{ number_format($budget->spent_amount, 2) }}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kalan (₺)</label>
                        <input type="text" class="form-control" value="₺{{ number_format($budget->remaining_amount, 2) }}" disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kullanım %</label>
                        <input type="text" class="form-control" value="{{ round($budget->spent_percentage) }}%" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="allocated_amount" class="form-label">Yeni Tahsis Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" name="allocated_amount" id="allocated_amount" 
                               class="form-control @error('allocated_amount') is-invalid @enderror"
                               step="0.01" min="0" value="{{ old('allocated_amount', $budget->allocated_amount) }}" required>
                        @error('allocated_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Fark otomatik olarak kalan tutara eklenecektir</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notlar</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" 
                              placeholder="Bütçe hakkında açıklamalar...">{{ old('notes', $budget->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('purchasing.budgets.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> İptal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
