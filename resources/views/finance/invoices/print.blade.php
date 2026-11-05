@extends('layouts.master')

@section('title','Fatura Yazdır')
@section('topbar-title','Finans')

@section('content')
<div class="container">
    <h3>Fatura Yazdır: {{ $invoice->no }}</h3>
    <p>Bu sayfa PDF/print çıktısı için kullanılabilir.</p>
</div>
@endsection
