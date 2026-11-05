@extends('layouts.master')

@section('title', 'Notlar')
@section('topbar-title', 'Haberleşme')

@section('content')
<div class="row py-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3>Notlar</h3>
                <p>Notlar sayfası henüz hazır değil. İleride dokümanlar veya bilgi bankası ile entegre edilecektir.</p>
                <a href="{{ route('documentation.index') }}" class="btn btn-sm btn-outline-primary">Dokümantasyona Git</a>
            </div>
        </div>
    </div>
</div>
@endsection
