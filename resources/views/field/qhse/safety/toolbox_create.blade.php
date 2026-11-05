@extends('layouts.master')

@section('title', 'Yeni Toolbox Talk')
@section('topbar-title', 'İSG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Yeni Toolbox Talk Kaydı</h4>
                <div class="page-title-right">
                    <a href="{{ route('field.qhse.safety.toolbox.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Eğitim Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('field.qhse.safety.toolbox.store') }}" enctype="multipart/form-data" id="toolbox-form">
                        @csrf

                        <!-- Tarih ve Konu -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Eğitim Tarihi <span class="text-danger">*</span></label>
                                <input type="date" name="date" id="date" class="form-control"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="topic" class="form-label">Güvenlik Konusu <span class="text-danger">*</span></label>
                                <input type="text" name="topic" id="topic" class="form-control"
                                       placeholder="Örn: Kişisel Koruyucu Donanım Kullanımı" required>
                            </div>
                        </div>

                        <!-- Lokasyon -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Eğitim Lokasyonu <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control"
                                   placeholder="Eğitimin yapıldığı yer" required>
                        </div>

                        <!-- GPS Koordinatları -->
                        <div class="mb-3">
                            <label class="form-label">GPS Konumu</label>
                            <div class="input-group">
                                <input type="text" id="location-display" class="form-control" placeholder="Konum alınıyor..." readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="getLocation()">
                                    <i class="mdi mdi-crosshairs-gps"></i> Konum Al
                                </button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude" />
                            <input type="hidden" name="longitude" id="longitude" />
                        </div>

                        <!-- Veren Kişi -->
                        <div class="mb-3">
                            <label for="facilitator" class="form-label">Eğitimi Veren Kişi <span class="text-danger">*</span></label>
                            <input type="text" name="facilitator" id="facilitator" class="form-control"
                                   placeholder="Eğitim koordinatörünün adı" required>
                        </div>

                        <!-- Katılımcılar -->
                        <div class="mb-3">
                            <label class="form-label">Katılımcılar <span class="text-danger">*</span></label>
                            <div class="border rounded p-3 bg-light">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label mb-0">Katılımcı Listesi</label>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addParticipant()">
                                            <i class="mdi mdi-plus"></i> Katılımcı Ekle
                                        </button>
                                    </div>
                                    <div id="participants-list">
                                        <!-- Katılımcılar buraya eklenecek -->
                                    </div>
                                </div>

                                <!-- Toplu Ekleme -->
                                <div class="border-top pt-3">
                                    <label class="form-label">Veya Toplu Ekleme (Virgülle ayırın)</label>
                                    <textarea class="form-control" id="bulk-participants" rows="2"
                                              placeholder="Ahmet Yılmaz, Mehmet Demir, Ayşe Kaya..."></textarea>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addBulkParticipants()">
                                        Toplu Ekle
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Eğitim Notları -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Eğitim Notları</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4"
                                      placeholder="Konuşulan önemli noktalar, örnekler, sorular ve yanıtlar..."></textarea>
                        </div>

                        <!-- Eğitim Fotoğrafı -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Eğitim Fotoğrafı</label>
                            <input type="file" name="photo" id="photo" accept="image/*" capture="environment" class="form-control">
                            <small class="text-muted">Eğitim anından bir fotoğraf ekleyin (opsiyonel)</small>
                            <div id="photo-preview" class="mt-2"></div>
                        </div>

                        <!-- Katılım İmzaları -->
                        <div class="mb-3">
                            <label class="form-label">Katılım İmzaları</label>
                            <div class="border rounded p-3">
                                <p class="mb-2">Katılımcılar için dijital imza alanı:</p>
                                <canvas id="signature-canvas" width="400" height="200"
                                        style="border: 1px solid #ccc; cursor: crosshair;"
                                        class="w-100"></canvas>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
                                        <i class="mdi mdi-refresh"></i> Temizle
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="saveSignature()">
                                        <i class="mdi mdi-content-save"></i> İmzayı Kaydet
                                    </button>
                                </div>
                                <input type="hidden" name="signature_data" id="signature-data">
                            </div>
                        </div>

                        <!-- Gönder Butonları -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('field.qhse.safety.toolbox.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Eğitimi Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Bilgilendirme Kartı -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Toolbox Talk Rehberi</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Toolbox Talk:</strong> Her gün iş başlangıcında yapılan kısa güvenlik konuşmaları
                    </div>

                    <h6>Örnek Konular:</h6>
                    <ul class="list-unstyled">
                        <li>• Kişisel Koruyucu Donanım</li>
                        <li>• Yüksekte Çalışma Güvenliği</li>
                        <li>• Elektrik Güvenliği</li>
                        <li>• Yangın Önleme</li>
                        <li>• Kimyasal Güvenlik</li>
                    </ul>

                    <hr>
                    <p class="mb-1"><strong>Kayıt Önemli:</strong></p>
                    <small class="text-muted">
                        Düzenli yapılan toolbox talk'lar, güvenlik kültürünün temelini oluşturur.
                    </small>
                </div>
            </div>

            <!-- İstatistikler -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Bu Ay Eğitimler</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-primary">{{ \App\Models\ToolboxTalk::whereMonth('date', now()->month)->whereYear('date', now()->year)->count() }}</h3>
                        <p class="text-muted mb-0">adet eğitim yapıldı</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let participants = [];
let signaturePad;

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    // İmza canvas'ını başlat
    const canvas = document.getElementById('signature-canvas');
    signaturePad = new SignaturePad(canvas);

    // İlk katılımcıyı ekle
    addParticipant();
});

// Katılımcı ekleme
function addParticipant(name = '') {
    const participantId = Date.now();
    const participantHtml = `
        <div class="input-group mb-2 participant-item" data-id="${participantId}">
            <input type="text" class="form-control participant-name"
                   placeholder="Katılımcı adı" value="${name}" required>
            <button type="button" class="btn btn-outline-danger" onclick="removeParticipant(${participantId})">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    `;
    document.getElementById('participants-list').insertAdjacentHTML('beforeend', participantHtml);
}

// Katılımcı çıkarma
function removeParticipant(id) {
    const element = document.querySelector(`[data-id="${id}"]`);
    if (element) {
        element.remove();
    }
}

// Toplu katılımcı ekleme
function addBulkParticipants() {
    const bulkText = document.getElementById('bulk-participants').value;
    if (bulkText.trim()) {
        const names = bulkText.split(',').map(name => name.trim()).filter(name => name);
        names.forEach(name => addParticipant(name));
        document.getElementById('bulk-participants').value = '';
    }
}

// İmza temizleme
function clearSignature() {
    signaturePad.clear();
    document.getElementById('signature-data').value = '';
}

// İmza kaydetme
function saveSignature() {
    if (signaturePad.isEmpty()) {
        alert('Lütfen imza atın.');
        return;
    }
    const dataURL = signaturePad.toDataURL();
    document.getElementById('signature-data').value = dataURL;
    alert('İmza kaydedildi.');
}

// Form gönderilmeden önce katılımcıları topla
document.getElementById('toolbox-form').addEventListener('submit', function(e) {
    const participantInputs = document.querySelectorAll('.participant-name');
    const participantNames = Array.from(participantInputs)
        .map(input => input.value.trim())
        .filter(name => name);

    if (participantNames.length === 0) {
        e.preventDefault();
        alert('En az bir katılımcı ekleyin.');
        return;
    }

    // Katılımcıları hidden input olarak ekle
    let participantsInput = document.querySelector('input[name="participants"]');
    if (!participantsInput) {
        participantsInput = document.createElement('input');
        participantsInput.type = 'hidden';
        participantsInput.name = 'participants';
        this.appendChild(participantsInput);
    }
    participantsInput.value = JSON.stringify(participantNames);
});

// Fotoğraf önizleme
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photo-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// GPS Konum Alma Fonksiyonu
function getLocation() {
    const locationDisplay = document.getElementById('location-display');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    if (navigator.geolocation) {
        locationDisplay.value = 'Konum alınıyor...';
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            latitudeInput.value = lat;
            longitudeInput.value = lng;
            locationDisplay.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            locationDisplay.classList.remove('text-danger');
            locationDisplay.classList.add('text-success');
        }, function(error) {
            console.error('GPS Hatası:', error);
            locationDisplay.value = 'Konum alınamadı';
            locationDisplay.classList.remove('text-success');
            locationDisplay.classList.add('text-danger');
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        });
    } else {
        locationDisplay.value = 'GPS desteklenmiyor';
        locationDisplay.classList.add('text-danger');
    }
}

// Sayfa yüklendiğinde otomatik konum almayı dene
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 768 && navigator.geolocation) {
        setTimeout(getLocation, 1000);
    }
});
</script>

<!-- Signature Pad Library -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
@endsection
