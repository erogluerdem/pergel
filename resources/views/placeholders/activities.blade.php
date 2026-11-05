@extends('layouts.master')

@section('title', 'Aktiviteler')
@section('topbar-title', 'İş Takibi')

@section('content')
<div class="row py-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3>Aktiviteler</h3>
                <p>Aktiviteler paneli hazır değil. Bu sayfa yakında eklenecektir.</p>
                <a href="{{ route('portfolio.management.index') }}" class="btn btn-sm btn-outline-primary">Kontrol Paneline Dön</a>
            </div>
        </div>
    </div>
</div>
@endsection
