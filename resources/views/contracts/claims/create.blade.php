@extends('layouts.master')

@section('title','Yeni Hak Talebi Oluştur')

@section('content')
<div class="container-fluid">
            <div class="d-flex justify-content-between mb-3">
        <h3>Yeni Hak Talebi Oluştur</h3>
        <div>
            <a href="{{ route('contracts.claims') }}" class="btn btn-outline-secondary">← Geri</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('contracts.claims.store') }}">
                @csrf
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Proje</label>
                        <select name="project_id" class="form-select">
                            <option value="">-- Seçiniz --</option>
                            @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ old('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konu / Başlık</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Talep Eden (Taraf Firma)</label>
                        <input type="text" name="claimant" class="form-control" value="{{ old('claimant') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tip</label>
                        <select name="type" class="form-select">
                            <option value="Maliyet" {{ old('type')=='Maliyet' ? 'selected' : '' }}>Maliyet</option>
                            <option value="Süre" {{ old('type')=='Süre' ? 'selected' : '' }}>Süre</option>
                            <option value="Maliyet & Süre" {{ old('type')=='Maliyet & Süre' ? 'selected' : '' }}>Maliyet & Süre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Talep Edilen Tutar (TL)</label>
                        <input type="number" step="0.01" name="amount_requested" class="form-control" value="{{ old('amount_requested') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Talep Edilen Süre (Gün)</label>
                        <input type="number" name="days_requested" class="form-control" value="{{ old('days_requested') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Talebin Gerekçesi / Açıklama</label>
                        <textarea name="description" rows="6" class="form-control">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary">Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
