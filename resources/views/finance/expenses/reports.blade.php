@extends('layouts.master')
@section('title','Gider Raporları')

@section('content')
<div class="container">
    <h3>Gider Raporları</h3>
    <p>Bu sayfa detaylı raporlar için hazırlandığı zaman genişletilecek.</p>
    <a href="{{ route('finance.expenses.general') }}" class="btn btn-secondary">Geri</a>
</div>
@endsection
