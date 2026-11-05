@extends('layouts.master')
@section('title','İhale Paketi Düzenle')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>İhale Paketi Düzenle — {{ $p->name }}</h4>
        <div>
            <a href="{{ route('tenders.package.show', $p->id) }}" class="btn btn-secondary">Geri</a>
        </div>
    </div>

    <form method="post" action="{{ route('tenders.package.update', $p->id) }}">
        @csrf @method('PUT')
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>İlişkili Proje</label>
                        <select name="project_id" class="form-control">
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ $proj->id==$p->project_id? 'selected':''}}>{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Paket Adı</label>
                        <input type="text" name="name" class="form-control" value="{{ $p->name }}" required />
                    </div>
                    <div class="col-12 mb-2">
                        <label>İşin Kapsamı</label>
                        <textarea name="scope" class="form-control" rows="4">{{ $p->scope }}</textarea>
                    </div>
                    <div class="col-12 mb-2">
                        <label>Davetli E-Posta Adresleri</label>
                        <textarea name="bidders_text" class="form-control" rows="3">{{ is_array($p->bidders) ? implode("\n", $p->bidders) : '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary">Güncelle</button>
        </div>
    </form>
</div>
@endsection
