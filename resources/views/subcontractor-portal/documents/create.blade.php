@extends('subcontractor-portal.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">İşçi Belgesi Yükle (İSG & Özlük)</h2>
    <a href="{{ route('subcontractor.documents.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i>Geri Dön
    </a>
</div>

<div class="card shadow-sm border-0" style="max-width:700px;">
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
            <div>
                Eksik veya geçersiz evrağı olan personellerin şantiye sahasına (turnikelerden) girişine kesinlikle izin verilmeyecektir.
            </div>
        </div>

        <form action="{{ route('subcontractor.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Personel (İşçi)</label>
                    <select class="form-select" name="worker_id" required>
                        <option value="">-- Personel Seçiniz --</option>
                        <option value="101">Ahmet Yılmaz (Kalıp Ustası)</option>
                        <option value="102">Mehmet Demir (Düz İşçi)</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Belge Türü</label>
                    <select class="form-select" name="document_type" required>
                        <option value="">-- Belge Seçiniz --</option>
                        <option value="sgk">SGK İşe Giriş Bildirgesi</option>
                        <option value="isg">İSG Temel Eğitim Sertifikası</option>
                        <option value="saglik">Ağır/Tehlikeli İşlerde Çalışabilir Raporu</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Belge Dosyası (PDF veya Resim)</label>
                <input class="form-control" type="file" name="file_path" id="documentFile" required>
                <!-- For demo we just pass string if file upload isn't fully set up in controller, but standard HTML form expects file -->
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-upload me-2"></i>Belgeyi Yükle ve Onaya Gönder
            </button>
        </form>
    </div>
</div>
@endsection
