@extends('layouts.master')

@section('title','Fatura E-posta Gönder')
@section('topbar-title','Finans')

@section('content')
<div class="container">
    <h3>Fatura E-posta Gönder: {{ $invoice->no }}</h3>
    <form method="POST" action="{{ route('finance.invoices.email.send', $invoice->id) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kime</label>
            <input type="email" name="to" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konu</label>
            <input type="text" name="subject" class="form-control" required value="Fatura {{ $invoice->no }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Mesaj</label>
            <textarea name="message" class="form-control" rows="5">Fatura bilgileri ektedir.</textarea>
        </div>
        <button class="btn btn-primary">Gönder</button>
        <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">İptal</a>
    </form>
</div>
@endsection
