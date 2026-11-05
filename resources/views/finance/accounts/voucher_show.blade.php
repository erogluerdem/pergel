@extends('layouts.master')
@section('title', 'Fiş Detayı')
@section('topbar-title', 'Fiş Detayı')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4>Fiş: {{ $v->no ?? ('#'.$v->id) }}</h4>
            <p>Tarih: {{ $v->date ?? '' }} | Tür: {{ ucfirst($v->type ?? '') }} | Tutar: {{ number_format($v->amount ?? 0,2,',','.') }}</p>
            <p>Hesap: {{ $v->account ?? '' }} | Karşı Taraf: {{ $v->counterparty ?? '' }}</p>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Hesap</th><th>Borç</th><th>Alacak</th><th>Açıklama</th></tr></thead>
                    <tbody>
                        @if(!empty($v->lines) && is_array($v->lines))
                            @foreach($v->lines as $ln)
                                <tr>
                                    <td>{{ $ln['account'] ?? $ln->account ?? '' }}</td>
                                    <td>{{ number_format($ln['debit'] ?? $ln->debit ?? 0,2,',','.') }}</td>
                                    <td>{{ number_format($ln['credit'] ?? $ln->credit ?? 0,2,',','.') }}</td>
                                    <td>{{ $ln['description'] ?? $ln->description ?? '' }}</td>
                                </tr>
                            @endforeach
                        @else
                            @if(isset($v->lines) && $v->lines instanceof \Illuminate\Support\Collection)
                                @foreach($v->lines as $ln)
                                    <tr>
                                        <td>{{ $ln->account ?? '' }}</td>
                                        <td>{{ number_format($ln->debit ?? 0,2,',','.') }}</td>
                                        <td>{{ number_format($ln->credit ?? 0,2,',','.') }}</td>
                                        <td>{{ $ln->description ?? '' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                    </tbody>
                </table>
            </div>
            <p>Notlar: {{ $v->notes ?? '' }}</p>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Geri</a>
        </div>
    </div>
@endsection
