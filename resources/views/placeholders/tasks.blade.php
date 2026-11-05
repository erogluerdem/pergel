@extends('layouts.master')

@section('title', 'Görevler')
@section('topbar-title', 'Projeler')

@section('content')
<div class="row py-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3>Görevler</h3>
                <p>Görev listesi henüz uygulamada değil. Mevcut görevler için Projeler sayfasına bakabilirsiniz.</p>
                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary">Projeler</a>
            </div>
        </div>
    </div>
</div>
@endsection
