@extends('layouts.master')
@section('title','İhale Paketi — '.$p->name ?? 'İhale Paketi')
@section('content')
<div class="container mt-4">
    <h4>{{ $p->name }} (Public Access)</h4>
    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Proje:</strong> {{ optional($p->project)->name }}</p>
            <p><strong>Teklif Bitiş:</strong> {{ $p->end_date }}</p>
            <h5>İşin Kapsamı</h5>
            <div class="border p-2">{!! nl2br(e($p->scope)) !!}</div>
            <h5 class="mt-3">BOQ</h5>
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
            <hr />
            <h5 class="mt-3">Teklif Gönder</h5>
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <form method="post" action="{{ route('tenders.package.submit_bid', $p->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label>E-Posta</label>
                    <input type="email" name="bidder_email" class="form-control" required value="{{ request()->get('bidder') ? base64_decode(request()->get('bidder')) : '' }}" />
                </div>
                <div class="mb-2">
                    <label>Teklif Tutarı (TL)</label>
                    <input type="number" step="0.01" name="price" class="form-control" />
                </div>
                <div class="mb-2">
                    <label>Notlar</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-2">
                    <label>Ek Dokümanlar (isteğe bağlı)</label>
                    <input type="file" name="documents[]" class="form-control" multiple />
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">Teklif Gönder</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
