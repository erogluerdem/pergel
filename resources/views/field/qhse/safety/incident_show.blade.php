@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Olay #{{ $incident->id }}</h2>

    <div>
        <strong>Tip:</strong> {{ $incident->type }}
    </div>
    <div>
        <strong>Tarih:</strong> {{ $incident->occurred_at ?? $incident->created_at }}
    </div>
    <div>
        <strong>Açıklama:</strong>
        <p>{{ $incident->description }}</p>
    </div>
    <div>
        <strong>Fotoğraf:</strong>
        @if($incident->photo_path)
            <div><img src="{{ asset('storage/'.$incident->photo_path) }}" style="max-width:100%;height:auto"/></div>
        @else
            <div>Fotoğraf yok</div>
        @endif
    </div>
    <div>
        <strong>Kök Neden:</strong>
        <p>{{ $incident->root_cause }}</p>
    </div>
    <div>
        <strong>Düzeltici Faaliyet:</strong>
        <p>{{ $incident->corrective_action }}</p>
    </div>
</div>
@endsection
