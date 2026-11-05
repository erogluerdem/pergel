@extends('layouts.master')

@section('title', 'Olay Bildirimi')
@section('topbar-title', 'İSG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Olay / Kaza Bildirimi</h4>
                <div class="page-title-right">
                    <a href="{{ route('field.qhse.safety.incidents.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="card-title mb-0">Olay Bilgilerini Girin</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('field.qhse.safety.incident.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Olay Tipi -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Olay Tipi <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="">Seçiniz...</option>
                                    <option value="ramak_kala">Ramak Kala (Yaralanmasız)</option>
                                    <option value="hafif_yaralanmali">Hafif Yaralanmalı Kaza</option>
                                    <option value="agir_yaralanmali">Ağır Yaralanmalı Kaza</option>
                                    <option value="guvensiz_durum">Güvensiz Durum Tespiti</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="occurred_at" class="form-label">Olay Tarihi ve Saati <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="occurred_at" id="occurred_at" class="form-control"
                                       value="{{ date('Y-m-d\TH:i') }}" required>
                                <small class="text-muted">Sistem tarihi otomatik olarak ayarlandı</small>
                            </div>
                        </div>

                        <!-- Lokasyon -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasyon <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control"
                                   placeholder="Örn: Şantiye Alanı, 5. Kat, Ofis No: 12" required>
                            <small class="text-muted">Olayın gerçekleştiği yeri detaylı olarak belirtin</small>
                        </div>

                        <!-- Açıklama -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Olayın Açıklaması <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Olayı detaylı olarak anlatın. Ne oldu? Nasıl oldu? Kimler etkilendi?" required></textarea>
                        </div>

                        <!-- Kanıt Fotoğrafı/Video -->
                        <div class="mb-3">
                            <label for="evidence" class="form-label">Kanıt Fotoğrafı/Videosu <span class="text-danger">*</span></label>
                            <input type="file" name="evidence" id="evidence" accept="image/*,video/*" capture="environment"
                                   class="form-control" multiple required>
                            <small class="text-muted">Telefonunuzun kamerasını kullanarak olayın fotoğrafını veya videosunu çekin. Birden fazla dosya seçebilirsiniz.</small>
                            <div id="file-preview" class="mt-2"></div>
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

                        <!-- İSG Uzmanı İçin Alanlar (İlk başta gizli) -->
                        <div id="expert-fields" style="display: none;">
                            <hr>
                            <h6 class="text-primary">İSG Uzmanı Analizi (Daha sonra doldurulacak)</h6>

                            <!-- Kök Neden Analizi -->
                            <div class="mb-3">
                                <label for="root_cause" class="form-label">Kök Neden Analizi</label>
                                <textarea name="root_cause" id="root_cause" class="form-control" rows="3"
                                          placeholder="Olayın temel nedenlerini analiz edin"></textarea>
                            </div>

                            <!-- Düzeltici/Önleyici Faaliyet -->
                            <div class="mb-3">
                                <label for="corrective_action" class="form-label">Düzeltici/Önleyici Faaliyet (DÖF)</label>
                                <textarea name="corrective_action" id="corrective_action" class="form-control" rows="3"
                                          placeholder="Olayın tekrarlanmasını önlemek için alınacak tedbirleri belirtin"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="action_responsible" class="form-label">Sorumlu Kişi</label>
                                    <input type="text" name="action_responsible" id="action_responsible" class="form-control"
                                           placeholder="Faaliyetten sorumlu kişi">
                                </div>
                                <div class="col-md-6">
                                    <label for="action_deadline" class="form-label">Termin Tarihi</label>
                                    <input type="date" name="action_deadline" id="action_deadline" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Gönder Butonu -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('field.qhse.safety.incidents.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-send"></i> Olayı Bildir
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
                    <h6 class="card-title mb-0">Bildirim Rehberi</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Ramak Kala:</strong> Yaralanma olmadan atlatılan olaylar
                    </div>
                    <div class="alert alert-warning">
                        <strong>Hafif Yaralanma:</strong> İlk yardım ile tedavi edilebilen yaralanmalar
                    </div>
                    <div class="alert alert-danger">
                        <strong>Ağır Yaralanma:</strong> Hastaneye sevk gerektiren durumlar
                    </div>
                    <div class="alert alert-secondary">
                        <strong>Güvensiz Durum:</strong> Potansiyel risk taşıyan durumlar
                    </div>

                    <hr>
                    <p class="mb-1"><strong>Kanıt Önemli:</strong></p>
                    <small class="text-muted">Fotoğraf/video, olayın doğru analiz edilmesi için kritik önem taşır.</small>
                </div>
            </div>

            <!-- GPS Durumu -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Konum Bilgisi</h6>
                </div>
                <div class="card-body">
                    <div id="location-status" class="text-center">
                        <i class="mdi mdi-crosshairs-gps text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0">Konum alınıyor...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

            // Konum alındığını görsel olarak göster
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
    // Mobil cihazlarda otomatik konum alma
    if (window.innerWidth <= 768 && navigator.geolocation) {
        setTimeout(getLocation, 1000); // 1 saniye bekle
    }
});

// Dosya Önizleme (Çoklu dosya desteği)
document.getElementById('evidence').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('file-preview');
    preview.innerHTML = '';

    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileDiv = document.createElement('div');
            fileDiv.className = 'file-preview-item mb-2 p-2 border rounded';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fileDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img src="${e.target.result}" class="img-thumbnail me-2" style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <small class="text-muted">${file.name}</small><br>
                                <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                fileDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white d-flex align-items-center justify-content-center me-2" style="width: 60px; height: 60px;">
                            <i class="mdi mdi-video"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted">${file.name}</small><br>
                            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(1)} MB</small>
                        </div>
                    </div>
                `;
            }

            preview.appendChild(fileDiv);
        }
    }
});
</script>
@endsection
