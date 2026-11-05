@extends('layouts.master')

@section('title')
    Not Düzenle
@endsection

@section('topbar-title')
    Not Düzenle
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('notes.update', $note->id ?? 0) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $note->title ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea name="body" class="form-control" rows="8">{{ old('body', $note->body ?? '') }}</textarea>
                    </div>
                    <button class="btn btn-primary">Güncelle</button>
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">İptal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
