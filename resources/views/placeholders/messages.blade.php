@extends('layouts.master')

@section('title', 'Mesajlar')
@section('topbar-title', 'Haberleşme')

@section('content')
<div class="row py-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3>Mesajlar (Inbox)</h3>
                <p>Henüz gelen kutusu sayfası hazırlanmadı. Bu sayfa kısa süre içinde eklenecektir.</p>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">Bildirimlere Git</a>
            </div>
        </div>
    </div>
</div>
@endsection
