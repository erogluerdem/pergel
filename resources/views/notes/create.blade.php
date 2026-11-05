@extends('layouts.master')

@section('title')
    Not Oluştur
@endsection

@section('topbar-title')
    Not Oluştur
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('notes.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea name="body" class="form-control" rows="8">{{ old('body') }}</textarea>
                    </div>
                    <button class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">İptal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
