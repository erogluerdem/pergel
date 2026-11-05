@extends('layouts.master')
@section('title','Teklifler — '.$p->name ?? 'Teklifler')
@section('content')
<div class="container mt-4">
    <h4>Teklifler — {{ $p->name }}</h4>
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('tenders.package.bids.compare', $p->id) }}" class="btn btn-outline-primary btn-sm">Teklif Karşılaştırma</a>
            </div>
            <table class="table table-sm">
                <thead><tr><th>E-Posta</th><th>Fiyat</th><th>Notlar</th><th>Tarih</th></tr></thead>
                <tbody>
                    @foreach($bids as $b)
                        <tr>
                            <td>{{ $b->bidder_email }}</td>
                            <td>{{ $b->price }}</td>
                            <td>
                                {{ $b->notes }}
                                @if(!empty($b->documents))
                                    <div class="mt-1">
                                        @foreach($b->documents as $doc)
                                            @php
                                                $dl = URL::temporarySignedRoute('tenders.package.bids.download', now()->addMinutes(30), ['id'=>$p->id,'file'=>$doc]);
                                            @endphp
                                            <a href="{{ $dl }}" class="small">Dosya indir</a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>{{ $b->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
