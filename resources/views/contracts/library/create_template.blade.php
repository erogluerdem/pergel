@extends('layouts.master')

@section('title','Yeni Şablon')

@section('content')
<div class="container">
    <h3>Yeni Şablon Oluştur</h3>
    <form action="{{ route('contracts.library.templates.store') }}" method="post">
        @csrf
        <div class="mb-2">
            <label class="form-label">Başlık</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">İçerik</label>
            <textarea name="body" rows="10" class="form-control" required></textarea>
        </div>
        <button class="btn btn-primary">Kaydet</button>
    </form>
</div>
@endsection
