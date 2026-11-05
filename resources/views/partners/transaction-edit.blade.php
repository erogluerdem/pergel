@extends('layouts.master')

@section('title','İşlem Düzenle')

@section('content')
<div class="container">
    <h2>İşlem Düzenle (#{{ $tx->id }})</h2>

    <form method="POST" action="{{ route('partners.transactions.update', $tx->id) }}">
        @csrf
        <div class="mb-3">
            <label>Tarih</label>
            <input type="date" name="transaction_date" class="form-control" value="{{ optional($tx->transaction_date)->format('Y-m-d') ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label>Tutar</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $tx->amount }}" required>
        </div>
        <div class="mb-3">
            <label>Kaynak</label>
            <select name="source" class="form-select">
                <option value="cash" {{ $tx->source == 'cash' ? 'selected' : '' }}>Kasa</option>
                <option value="bank" {{ $tx->source == 'bank' ? 'selected' : '' }}>Banka</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Kur</label>
            <select name="currency" class="form-select">
                <option value="TRY" {{ optional($tx->cashBankTransaction)->currency == 'TRY' ? 'selected' : '' }}>TL</option>
                <option value="USD" {{ optional($tx->cashBankTransaction)->currency == 'USD' ? 'selected' : '' }}>USD</option>
                <option value="EUR" {{ optional($tx->cashBankTransaction)->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Kuru (örn. 27.35)</label>
            <input type="number" step="0.000001" name="exchange_rate" class="form-control" value="{{ optional($tx->cashBankTransaction)->exchange_rate ?? '' }}">
        </div>
        <div class="mb-3">
            <label>Hangi Banka (isteğe bağlı)</label>
            <select name="bank_account_id" class="form-select">
                <option value="">Seçiniz</option>
                @foreach(
                    \App\Models\Account::where('type','bank')->orWhereNotNull('bank_name')->orderBy('name')->get() as $acc
                )
                    <option value="{{ $acc->id }}" {{ optional($tx->cashBankTransaction)->account_id == $acc->id ? 'selected' : '' }}>{{ $acc->bank_name ? $acc->bank_name . ' - ' . $acc->name : $acc->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Açıklama</label>
            <input type="text" name="description" class="form-control" value="{{ $tx->description }}">
        </div>
        <button class="btn btn-sm btn-primary" aria-label="Güncelle">Güncelle</button>
        <a href="{{ route('partners.transactions') }}" class="btn btn-sm btn-secondary" aria-label="İptal">İptal</a>
    </form>
</div>
@endsection
