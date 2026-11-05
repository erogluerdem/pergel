@extends('layouts.master')

@section('title','Fatura Ödemesi')
@section('topbar-title','Finans')

@section('content')
<div class="container">
    <h3>Fatura Ödemesi: {{ $invoice->no }}</h3>

    <form method="POST" action="{{ route('finance.invoices.payment.store', $invoice->id) }}">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Tutar (₺)</label>
            <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-control" value="{{ old('amount', $invoice->balance) }}" required>
            @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="method" class="form-label">Ödeme Yöntemi</label>
            <select id="method" name="method" class="form-select">
                <option value="cash">Nakit</option>
                <option value="bank_transfer">Banka Havalesi</option>
                <option value="credit_card">Kredi Kartı</option>
                <option value="check">Çek</option>
            </select>
            @error('method') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="reference" class="form-label">Referans / Açıklama</label>
            <input type="text" id="reference" name="reference" class="form-control" value="{{ old('reference') }}">
            @error('reference') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success">Ödemeyi Kaydet</button>
            <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Geri</a>
        </div>
    </form>
</div>
@endsection
