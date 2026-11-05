@extends('layouts.master')

@section('title', 'Yeni Taşeron')
@section('topbar-title', 'Taşeron Yönetimi')

@push('styles')
<style>
    /* Page-scoped adjustments to match master theme */
    .subcontractor-create .card { border-radius: .5rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.04); }
    .subcontractor-create .card-title { margin-bottom: 1rem; font-weight: 600; }
    .subcontractor-create .form-label { font-weight: 500; }
    .subcontractor-create .form-control, .subcontractor-create .form-select { border-radius: .375rem; }
    .subcontractor-create .btn { min-width: 110px; }
    @media (max-width: 576px) {
        .subcontractor-create .card { padding: .75rem; }
    }

    /* Prevent form elements overflowing right (account for fixed right toolbar). Increased padding to ensure safety */
    .subcontractor-create { padding-right: 200px; }
    /* Allow dropdowns to overflow the card so option lists are visible above the toolbar */
    .subcontractor-create .card { overflow: visible; }
    .subcontractor-create .card-body { overflow: visible; }
    .subcontractor-create .form-control, .subcontractor-create .form-select { box-sizing: border-box; max-width: 100%; }

    /* Constrain the status select so it cannot grow indefinitely */
    .subcontractor-create .status-col .form-select { max-width: 220px; width: 100%; }

    /* If a JS dropdown plugin (select2 etc.) is used, ensure its dropdown overlays above everything */
    .subcontractor-create .select2-container .select2-dropdown,
    .select2-container .select2-dropdown,
    .subcontractor-create .select2-container--open .select2-dropdown {
        z-index: 3000 !important;
    }

    /* Native select fallback: ensure it visually sits above fixed toolbar where possible */
    .subcontractor-create .form-select { position: relative; z-index: 2000; }

    /* Make secondary button visible and accessible (contrast) */
    .subcontractor-create .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #ffffff;
        box-shadow: none;
    }
    .subcontractor-create .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        color: #fff;
    }
    /* Make status column wrap under the row when there's not enough horizontal space */
    @media (max-width: 991px) {
        .subcontractor-create .status-col { width: 100% !important; flex: 0 0 100% !important; }
    }

    /* For very large screens, ensure the form doesn't exceed the viewport (defensive) */
    @media (min-width: 1400px) {
        .subcontractor-create .container-fluid { max-width: calc(100% - 220px); }
    }
</style>
@endpush

@section('content')
    <div class="container-fluid subcontractor-create">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Yeni Taşeron Ekle</h4>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('subcontractor.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">Firma Adı</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_person" class="form-label">İlgili Kişi</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">E-posta</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="col-md-4 mb-3 status-col">
                                    <label for="status" class="form-label">Durum</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="pending">Beklemede</option>
                                        <option value="approved">Onaylandı</option>
                                        <option value="conditional">Koşullu</option>
                                        <option value="blacklisted">Blacklisted</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="phone" class="form-label">Telefon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="expertise_area" class="form-label">Uzmanlık Alanı</label>
                                    <input type="text" class="form-control" id="expertise_area" name="expertise_area" value="{{ old('expertise_area') }}" required>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary">Kaydet</button>
                                <a href="{{ route('subcontractor.companies') }}" class="btn btn-secondary">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
