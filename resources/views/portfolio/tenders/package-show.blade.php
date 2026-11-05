@extends('layouts.master')
@section('title','İhale Paketi — '.$p->name ?? 'İhale Paketi')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ $p->name }}</h4>
        <div>
            <a href="{{ route('tenders.package.edit', $p->id) }}" class="btn btn-outline-secondary">Düzenle</a>
            <form method="post" action="{{ route('tenders.package.publish', $p->id) }}" style="display:inline">@csrf
                <button class="btn btn-primary">Yayınla ve Davet Gönder</button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-body">
            <p><strong>İlişkili Proje:</strong> {{ optional($p->project)->name }}</p>
            <p><strong>Durum:</strong> {{ $p->status }}</p>
            <p><strong>Teklif Dönemi:</strong> {{ $p->start_date }} — {{ $p->end_date }}</p>
            <p><strong>Sorumlu:</strong> {{ optional($p->responsible)->name ?? '—' }}</p>
            <h5>İşin Kapsamı</h5>
            <div class="border p-2">{!! nl2br(e($p->scope)) !!}</div>

            <h5 class="mt-3">Bağlı Dokümanlar</h5>
            <ul>
                @foreach($p->documents ?? [] as $d)
                    <li><a href="{{ $d }}" target="_blank">{{ basename($d) }}</a></li>
                @endforeach
            </ul>

            <h5 class="mt-3">BOQ (Pozlar)</h5>
            <table class="table table-sm">
                <thead><tr><th>No</th><th>Açıklama</th><th>Birim</th><th>Miktar</th></tr></thead>
                <tbody>
                    @foreach($p->boq ?? [] as $r)
                        <tr>
                            <td>{{ $r['no'] ?? '' }}</td>
                            <td>{{ $r['description'] ?? '' }}</td>
                            <td>{{ $r['unit'] ?? '' }}</td>
                            <td>{{ $r['quantity'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
