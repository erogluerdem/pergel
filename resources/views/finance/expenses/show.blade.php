@extends('layouts.master')
@section('title','Gider Detayı')

@section('content')
<div class="container">
    <h3>Gider #{{ $expense->id ?? '' }}</h3>
    <dl class="row">
        <dt class="col-sm-3">Başlık</dt><dd class="col-sm-9">{{ $expense->title ?? '' }}</dd>
        <dt class="col-sm-3">Açıklama</dt><dd class="col-sm-9">{{ $expense->description ?? '' }}</dd>
        <dt class="col-sm-3">Tutar</dt><dd class="col-sm-9">₺{{ number_format($expense->total_amount ?? 0,2,',','.') }}</dd>
        <dt class="col-sm-3">Kategori</dt><dd class="col-sm-9">{{ \App\Models\GeneralExpense::getCategories()[$expense->category] ?? $expense->category }}</dd>
        <dt class="col-sm-3">Durum</dt><dd class="col-sm-9">{{ $expense->status }}</dd>
        <dt class="col-sm-3">Tarih</dt><dd class="col-sm-9">{{ optional($expense->expense_date)->format('d.m.Y') }}</dd>
    </dl>
    <a href="{{ route('finance.expenses.general') }}" class="btn btn-secondary">Geri</a>
    <a href="{{ route('finance.expenses.edit', [$expense->id]) }}" class="btn btn-primary">Düzenle</a>
</div>
@endsection
