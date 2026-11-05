@extends('subcontractor-portal.layouts.app', ['title' => 'Hizmet Kaydını Güncelle'])

@section('content')
<div class="section-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <span class="badge hero-badge text-uppercase mb-2">Kaydı güncelle</span>
        <h2 class="fw-semibold mb-1">Hizmet Kaydını Güncelle</h2>
        <p class="text-muted mb-0">Güncel saha bilgilerini düzenleyip yeniden gönderin.</p>
    </div>
    <a href="{{ route('subcontractor.services.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Listeye dön
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('subcontractor.services.update', $service) }}" enctype="multipart/form-data" class="row g-4">
            @csrf
            @method('PUT')
            @include('subcontractor-portal.services.form', ['service' => $service])
            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-gradient px-4">Kaydı Güncelle</button>
            </div>
        </form>
    </div>
</div>
@endsection
