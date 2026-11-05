@extends('layouts.master')

@section('title', 'Yeni Bütçe Oluştur')
@section('topbar-title', '💰 Yeni Bütçe Oluştur')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="page-title">💰 Yeni Bütçe Oluştur</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('purchasing.budgets.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Geri
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchasing.budgets.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="project_id" class="form-label">Proje <span class="text-danger">*</span></label>
                        <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror">
                            <option value="">Proje Seçiniz</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">Kategori Seçiniz</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="allocated_amount" class="form-label">Tahsis Edilen Miktar (₺) <span class="text-danger">*</span></label>
                        <input type="number" name="allocated_amount" id="allocated_amount" 
                               class="form-control @error('allocated_amount') is-invalid @enderror"
                               step="0.01" min="0" value="{{ old('allocated_amount') }}" placeholder="0.00">
                        @error('allocated_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fiscal_year" class="form-label">Mali Yıl <span class="text-danger">*</span></label>
                        <input type="number" name="fiscal_year" id="fiscal_year" 
                               class="form-control @error('fiscal_year') is-invalid @enderror"
                               min="2020" max="2099" value="{{ old('fiscal_year', now()->year) }}">
                        @error('fiscal_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notlar</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" 
                              placeholder="Bütçe hakkında açıklamalar...">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('purchasing.budgets.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> İptal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
