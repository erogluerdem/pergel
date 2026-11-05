@extends('layouts.master')

@section('title', 'Yeni Program')

@section('content')
<div class="container mt-4">
    <h4>Yeni Program Oluştur</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('portfolio.management.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Program Adı</label>
            <input name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Yönetici</label>
            <input name="manager" class="form-control" value="{{ old('manager') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Stratejik Hedef</label>
            <input name="strategic_objective" class="form-control" value="{{ old('strategic_objective') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Durum</label>
            <select name="status" class="form-select">
                <option value="planning">Planlanan</option>
                <option value="active">Aktif</option>
                <option value="completed">Tamamlandı</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Toplam Bütçe</label>
            <input name="total_budget" class="form-control" value="{{ old('total_budget') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Harcanan</label>
            <input name="spent_to_date" class="form-control" value="{{ old('spent_to_date') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div>
            <button class="btn btn-primary">Oluştur</button>
            <a href="{{ url('portfolio/program-management') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
