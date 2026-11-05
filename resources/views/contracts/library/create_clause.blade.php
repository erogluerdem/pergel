@extends('layouts.master')

@section('title','Yeni Madde')

@section('content')
<div class="container">
    <h3>Yeni Madde Oluştur</h3>
    <form action="{{ route('contracts.library.clauses.store') }}" method="post">
        @csrf
        <div class="mb-2">
            <label class="form-label">Başlık</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control">
        </div>
        <div class="mb-2">
            <label class="form-label">İçerik</label>
            <textarea name="content" rows="8" class="form-control" required></textarea>
        </div>
        <button class="btn btn-primary">Kaydet</button>
    </form>
</div>
@endsection
