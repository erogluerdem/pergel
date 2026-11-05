@extends('layouts.master')
@section('title','Hesap Ekstresi')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4>{{ $account_name ?? 'Hesap' }} - Ekstre ({{ $start ?? '' }} - {{ $end ?? '' }})</h4>
            <table class="table table-sm">
                <thead><tr><th>Tarih</th><th>Fiş No</th><th>Tür</th><th>Açıklama</th><th>Giren</th><th>Çıkan</th><th>Bakiye</th></tr></thead>
                <tbody>
                    @foreach($rows as $r)
                        <tr>
                            <td>{{ $r->date ?? '' }}</td>
                            <td>{{ $r->voucher_no ?? '' }}</td>
                            <td>{{ $r->type ?? '' }}</td>
                            <td>{{ $r->description ?? '' }}</td>
                            <td>{{ $r->debit ?? '' }}</td>
                            <td>{{ $r->credit ?? '' }}</td>
                            <td>{{ $r->balance ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
