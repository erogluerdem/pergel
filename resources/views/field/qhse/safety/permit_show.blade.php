@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Çalışma İzni #{{ $permit->id }}</h2>

    <div><strong>Tip:</strong> {{ $permit->permit_type }}</div>
    <div><strong>İş Tanımı:</strong><p>{{ $permit->work_description }}</p></div>
    <div><strong>Lokasyon:</strong> <x-location-display :location="$permit->location" /></div>
    <div><strong>Durum:</strong> {{ $permit->status }}</div>
    <div><strong>Geçerlilik:</strong> {{ $permit->valid_from }} - {{ $permit->valid_to }}</div>

    <div class="mt-3">
        @if($permit->status !== 'active')
            <form method="POST" action="{{ route('field.qhse.safety.permits.approve', $permit) }}" style="display:inline">
                @csrf
                <button class="btn btn-success">Onayla ve Aktif Et</button>
            </form>
        @endif

        @if($permit->status !== 'closed')
            <form method="POST" action="{{ route('field.qhse.safety.permits.close', $permit) }}" style="display:inline">
                @csrf
                <button class="btn btn-danger">Kapat</button>
            </form>
        @endif
    </div>
</div>
@endsection
