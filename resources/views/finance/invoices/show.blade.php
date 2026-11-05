@extends('layouts.master')

@section('title','Fatura Detay')
@section('topbar-title','Finans')

@section('content')
<div class="container">
    <h3>Fatura: {{ $invoice->no }}</h3>
    <p>Müşteri: {{ $invoice->customer_name }}</p>
    <p>Tutar: ₺{{ number_format($invoice->amount,2,',','.') }}</p>
    <p>KDV: ₺{{ number_format($invoice->tax_amount,2,',','.') }}</p>
    <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Geri</a>
</div>
@endsection
