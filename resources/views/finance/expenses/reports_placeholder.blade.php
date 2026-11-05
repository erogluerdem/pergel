@extends('layouts.master')
@section('title','Gider Raporu (Özet)')

@section('content')
<div class="container">
    <h3>Kategoriye Göre Özet</h3>
    <table class="table">
        <thead><tr><th>Kategori</th><th>Adet</th><th>Toplam</th></tr></thead>
        <tbody>
            @foreach($summary as $s)
            <tr>
                <td>{{ \App\Models\GeneralExpense::getCategories()[$s->category] ?? $s->category }}</td>
                <td>{{ $s->count }}</td>
                <td>₺{{ number_format($s->total,2,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('finance.expenses.general') }}" class="btn btn-secondary">Geri</a>
</div>
@endsection
