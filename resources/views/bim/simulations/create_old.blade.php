@extends('layouts.master')
@section('content')
<div class="container-fluid">
    <h3>Yeni Simülasyon Oluştur</h3>
    <form method="POST" action="{{ route('bim.simulations.store') }}">
        @csrf
        <div class="form-row">
            <div class="col-md-4">
                <label>Simülasyon Adı</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Tür</label>
                <select name="type" class="form-control">
                    <option value="4D">4D Zaman (İnşaat Sırası)</option>
                    <option value="5D">5D Maliyet (Nakit Akışı)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>İlişkili Proje</label>
                <select name="project_id" class="form-control">
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <label>3D Modeller (sürükle-bırak bağlantısı için seçim)</label>
            <div class="d-flex flex-wrap">
                @foreach($models as $m)
                    <div class="p-2 border mr-2 mb-2" style="min-width:160px">
                        <strong>{{ $m->name }}</strong><br>
                        <small>{{ $m->discipline }} / {{ $m->version }}</small>
                        <input type="checkbox" name="models[]" value="{{ $m->id }}"> Seç
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-3"><button class="btn btn-primary">Başlat</button></div>
    </form>
</div>
@endsection
