@extends('layouts.master')
@section('title','Teklif Karşılaştırma — '.$p->name ?? 'Karşılaştırma')
@section('content')
<div class="container mt-4">
    <h4>Teklif Karşılaştırma — {{ $p->name }}</h4>
    <div class="card mt-3">
        <div class="card-body">
            <form method="post" action="{{ route('tenders.package.bids.compare', $p->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label>CSV Yükle (opsiyonel) — Format: email,price,notes</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" />
                </div>
                <div class="text-end">
                    <button class="btn btn-primary">Karşılaştır</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
