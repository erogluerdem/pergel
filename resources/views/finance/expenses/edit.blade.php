@extends('layouts.master')
@section('title','Gider Düzenle')

@section('content')
<div class="container">
    <h3>Gider Düzenle</h3>
    <form action="{{ route('finance.expenses.update', [$expense->id]) }}" method="POST">
        @csrf
        @method('PUT')
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="mb-3">
            <label class="form-label">Başlık</label>
            <input name="title" class="form-control" value="{{ old('title', $expense->title) }}" />
            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" class="form-control">{{ old('description', $expense->description) }}</textarea>
            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Tutar</label>
            <input name="total_amount" class="form-control" value="{{ old('total_amount', $expense->total_amount) }}" />
            @error('total_amount') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select">
                <option value="">Seçiniz</option>
                @foreach(\App\Models\GeneralExpense::getCategories() as $k=>$v)
                <option value="{{ $k }}" {{ old('category', $expense->category) == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            @error('category') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Tarih</label>
            <input name="expense_date" class="form-control" value="{{ old('expense_date', optional($expense->expense_date)->format('Y-m-d')) }}" />
            @error('expense_date') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary">Güncelle</button>
        <a href="{{ route('finance.expenses.general') }}" class="btn btn-secondary">Geri</a>
    </form>
</div>
@endsection
