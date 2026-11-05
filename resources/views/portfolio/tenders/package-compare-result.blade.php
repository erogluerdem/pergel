@extends('layouts.master')
@section('title','Karşılaştırma Sonuçları — '.$p->name ?? 'Karşılaştırma')
@section('content')
<div class="container mt-4">
    <h4>Karşılaştırma Sonuçları — {{ $p->name }}</h4>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-sm">
                <thead><tr><th>Sıra</th><th>E-Posta</th><th>Fiyat</th><th>Notlar</th></tr></thead>
                <tbody>
                @foreach($rows as $i => $r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r['email'] }}</td>
                        <td>{{ number_format($r['price'],2) }}</td>
                        <td>{{ $r['notes'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
