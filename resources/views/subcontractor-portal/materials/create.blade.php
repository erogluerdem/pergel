@extends('subcontractor-portal.layouts.app', ['title' => 'Yeni Malzeme Girişi'])

@section('content')
<div class="section-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <span class="badge hero-badge text-uppercase mb-2">Yeni malzeme</span>
        <h2 class="fw-semibold mb-1">Yeni Malzeme Girişi</h2>
        <p class="text-muted mb-0">Şantiye teslimlerinizi anında kayıt altına alın.</p>
    </div>
    <a href="{{ route('subcontractor.materials.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Listeye dön
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('subcontractor.materials.store') }}" enctype="multipart/form-data" class="row g-4">
            @csrf
            @include('subcontractor-portal.materials.form')
            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-gradient px-4">Kaydı Oluştur</button>
            </div>
        </form>
    </div>
</div>
@endsection
