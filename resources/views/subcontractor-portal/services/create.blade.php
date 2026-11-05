@extends('subcontractor-portal.layouts.app', ['title' => 'Yeni Hizmet Kaydı'])

@section('content')
<div class="section-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <span class="badge hero-badge text-uppercase mb-2">Yeni hizmet kaydı</span>
        <h2 class="fw-semibold mb-1">Yeni Hizmet Kaydı</h2>
        <p class="text-muted mb-0">Şantiyede gerçekleştirdiğiniz hizmeti dakikalar içinde kayıt altına alın.</p>
    </div>
    <a href="{{ route('subcontractor.services.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Listeye dön
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('subcontractor.services.store') }}" enctype="multipart/form-data" class="row g-4">
            @csrf
            @include('subcontractor-portal.services.form')
            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-gradient px-4">Kaydı Oluştur</button>
            </div>
        </form>
    </div>
</div>
@endsection
