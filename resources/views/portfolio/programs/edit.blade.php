@extends('layouts.master')

@section('title', 'Program Düzenle')

@section('content')
<div class="container mt-4">
    <h4>Program Düzenle</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('portfolio.management.update', $program->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Program Adı</label>
            <input name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Yönetici</label>
            <input name="manager" class="form-control" value="{{ old('manager', $program->manager) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Stratejik Hedef</label>
            <input name="strategic_objective" class="form-control" value="{{ old('strategic_objective', $program->strategic_objective) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Durum</label>
            <select name="status" class="form-select">
                <option value="planning" {{ old('status', $program->status) == 'planning' ? 'selected' : '' }}>Planlanan</option>
                <option value="active" {{ old('status', $program->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ old('status', $program->status) == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Toplam Bütçe</label>
            <input name="total_budget" class="form-control" value="{{ old('total_budget', $program->total_budget) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Harcanan</label>
            <input name="spent_to_date" class="form-control" value="{{ old('spent_to_date', $program->spent_to_date) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" rows="4" class="form-control">{{ old('description', $program->description) }}</textarea>
        </div>

        <div>
            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('portfolio.management.show', $program->id) }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>

    <form method="post" action="{{ route('portfolio.management.destroy', $program->id) }}" class="mt-3" onsubmit="return confirm('Programı silmek istediğinize emin misiniz? Bu işlem projelerin program bağlantısını kaldıracaktır.');">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Programı Sil</button>
    </form>
</div>
@endsection
