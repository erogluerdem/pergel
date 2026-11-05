@extends('layouts.master')

@section('title', $vendor->name)
@section('topbar-title', 'Tedarik & Satın Alma')

@section('content')
<div>
    <div class="d-flex justify-content-between mb-3">
    <h2>{{ $vendor->name }}</h2>
    <a href="{{ route('index', ['any' => 'finance/accounts/suppliers']) }}" class="btn btn-outline-secondary">Geri</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card p-3 mb-3">
                <h5>Künye Bilgileri</h5>
                <p><strong>Vergi No:</strong> {{ $vendor->tax_number }}</p>
                <p><strong>Uzmanlık:</strong> {{ $vendor->specialty }}</p>
                <p><strong>İletişim:</strong> {{ $vendor->contact_person }} - {{ $vendor->phone }} - {{ $vendor->email }}</p>
                <p><strong>Adres:</strong> {{ $vendor->address }} {{ $vendor->city }} {{ $vendor->country }}</p>
                <p><strong>IBAN:</strong> {{ $vendor->iban }}</p>
            </div>

            <div class="card p-3 mb-3">
                <h5>Projeler ve Sözleşmeler</h5>
                @if($vendor->projects && $vendor->projects->isNotEmpty())
                    <ul>
                        @foreach($vendor->projects as $p)
                            <li><a href="{{ route('portfolio.project.show', $p->id) }}">{{ $p->name ?? 'Proje '.$p->id }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p>Henüz projeler ilişkilendirilmemiş.</p>
                @endif

                @if($vendor->contracts && $vendor->contracts->isNotEmpty())
                    <h6 class="mt-2">Sözleşmeler</h6>
                    <ul>
                        @foreach($vendor->contracts as $c)
                            <li><a href="{{ route('contracts.management', ['id' => $c->id] ) }}">{{ $c->title ?? 'Sözleşme '.$c->id }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p>Henüz sözleşme ilişkilendirilmemiş.</p>
                @endif
            </div>

            <div class="card p-3 mb-3">
                <h5>Performans Değerlendirmesi</h5>
                <p>Genel Puan: {{ number_format($vendor->performance_score,1) }} / 5</p>
                <hr>
                <h6>Değerlendirme Ekle</h6>
                <form method="post" action="{{ route('finance.accounts.suppliers.reviews.store', $vendor->id) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-3"><label>İş Kalitesi</label><input name="quality" type="number" min="1" max="5" class="form-control" required></div>
                        <div class="col-md-3"><label>Zamanına Uyum</label><input name="schedule" type="number" min="1" max="5" class="form-control" required></div>
                        <div class="col-md-3"><label>İSG</label><input name="safety" type="number" min="1" max="5" class="form-control" required></div>
                        <div class="col-md-3"><label>İletişim</label><input name="communication" type="number" min="1" max="5" class="form-control" required></div>
                    </div>
                    <div class="mt-2"><textarea name="comments" class="form-control" placeholder="Yorumlar (isteğe bağlı)"></textarea></div>
                    <div class="mt-2"><button class="btn btn-primary">Değerlendir</button></div>
                </form>

                <hr>
                <h6>Değerlendirme Geçmişi</h6>
                @if($reviews->isEmpty())
                    <p>Henüz değerlendirme yok.</p>
                @else
                    @foreach($reviews as $r)
                        <div class="border p-2 mb-2">
                            <div><strong>{{ $r->created_at->format('Y-m-d') }}</strong> - {{ $r->comments }}</div>
                            <div>Kalite: {{ $r->quality }} • Zaman: {{ $r->schedule }} • İSG: {{ $r->safety }} • İletişim: {{ $r->communication }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h6>Durum</h6>
                <p>{{ $vendor->status }}</p>
            </div>

            <div class="card p-3 mb-3">
                <h6>Dokümanlar & Uygunluk</h6>
                <ul class="list-unstyled">
                    @foreach($documents as $doc)
                        <li>
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">{{ $doc->type ?? 'Dosya' }}</a>
                            @if($doc->expiry_date) - <small>Son: {{ $doc->expiry_date }}</small>@endif
                        </li>
                    @endforeach
                </ul>

                <hr>
                <h6>Doküman Yükle</h6>
                <form method="post" action="{{ route('finance.accounts.suppliers.documents.upload', $vendor->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2"><input type="text" name="type" placeholder="Doküman tipi (örn: Vergi Levhası)" class="form-control"></div>
                    <div class="mb-2"><input type="date" name="expiry_date" class="form-control"></div>
                    <div class="mb-2"><input type="file" name="file" class="form-control" required></div>
                    <div><button class="btn btn-outline-primary">Yükle</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
