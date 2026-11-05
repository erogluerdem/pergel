@extends('layouts.master')

@section('title','Fatura Düzenle')
@section('topbar-title','Finans')

@section('content')
<div class="container">
    <h3>Fatura Düzenle: {{ $invoice->no }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('finance.invoices.update', $invoice->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="no" class="form-label">Fatura No</label>
            <input type="text" id="no" name="no" class="form-control" value="{{ old('no', $invoice->no) }}" required>
            @error('no') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="date" class="form-label">Tarih</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ old('date', optional($invoice->date)->format('Y-m-d')) }}" required>
                @error('date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="due_date" class="form-label">Vade</label>
                <input type="date" id="due_date" name="due_date" class="form-control" value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d')) }}">
                @error('due_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notlar</label>
            <textarea id="notes" name="notes" class="form-control" rows="4">{{ old('notes', $invoice->notes) }}</textarea>
            @error('notes') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <h5>Kalemler (Önizleme)</h5>
            @if($invoice->invoiceItems->isEmpty())
                <p class="text-muted">Henüz kalem yok.</p>
            @else
                <table class="table table-sm">
                    <thead><tr><th>Açıklama</th><th>Miktar</th><th>Birim Fiyat</th><th>KDV</th><th>Toplam</th></tr></thead>
                    <tbody>
                    @foreach($invoice->invoiceItems as $it)
                        <tr>
                            <td>{{ $it->description ?? ($it->stockCard->name ?? '-') }}</td>
                            <td>{{ $it->quantity }} {{ $it->unit }}</td>
                            <td>{{ number_format($it->unit_price, 2, ',', '.') }}</td>
                            <td>{{ $it->tax_rate }}%</td>
                            <td>{{ number_format((($it->unit_price * $it->quantity) + ($it->tax_amount ?? 0)), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary">Kaydet</button>
            <a href="{{ route('finance.invoices.show', $invoice->id) }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>

</div>
@endsection
