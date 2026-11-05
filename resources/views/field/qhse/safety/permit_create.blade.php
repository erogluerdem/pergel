@extends('layouts.master')

@section('title', 'Yeni Çalışma İzni')
@section('topbar-title', 'İSG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Yeni Çalışma İzni Oluştur</h4>
                <div class="page-title-right">
                    <a href="{{ route('field.qhse.safety.permits.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="card-title mb-0">Çalışma İzni Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('field.qhse.safety.permit.store') }}" id="permit-form">
                        @csrf

                        <!-- İzin Tipi -->
                        <div class="mb-3">
                            <label for="type" class="form-label">İzin Tipi <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select" required onchange="updateChecklist()">
                                <option value="">Seçiniz...</option>
                                <option value="yuksekte_calisma">Yüksekte Çalışma</option>
                                <option value="sicak_islem">Sıcak İşlem (Kaynak vb.)</option>
                                <option value="kapali_alan">Kapalı Alan Çalışması</option>
                                <option value="elektrik">Elektrik Çalışması</option>
                                <option value="kazma_hafriyat">Kazma/Hafriyat</option>
                                <option value="diger">Diğer</option>
                            </select>
                        </div>

                        <!-- İş Tanımı -->
                        <div class="mb-3">
                            <label for="description" class="form-label">İş Tanımı <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="3"
                                      placeholder="Yapılacak işi detaylı olarak açıklayın..." required></textarea>
                        </div>

                        <!-- Lokasyon ve Sorumlular -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="location" class="form-label">Lokasyon <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control"
                                       placeholder="İşin yapılacağı yer" required>
                            </div>
                            <div class="col-md-6">
                                <label for="responsible_person" class="form-label">Sorumlu Kişi <span class="text-danger">*</span></label>
                                <input type="text" name="responsible_person" id="responsible_person" class="form-control"
                                       placeholder="İşin sorumlusu" required>
                            </div>
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

                        <!-- Geçerlilik Süresi -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="valid_from" class="form-label">Geçerlilik Başlangıç <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="valid_from" id="valid_from" class="form-control"
                                       value="{{ date('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="valid_to" class="form-label">Geçerlilik Bitiş <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="valid_to" id="valid_to" class="form-control" required>
                            </div>
                        </div>

                        <!-- Güvenlik Checklist'i -->
                        <div id="checklist-section" style="display: none;">
                            <hr>
                            <h6 class="text-primary mb-3">Güvenlik Kontrol Listesi</h6>
                            <div id="checklist-items">
                                <!-- Checklist öğeleri JavaScript ile doldurulacak -->
                            </div>
                        </div>

                        <!-- Risk Değerlendirmesi -->
                        <div class="mb-3">
                            <label for="risk_assessment" class="form-label">Risk Değerlendirmesi</label>
                            <textarea name="risk_assessment" id="risk_assessment" class="form-control" rows="3"
                                      placeholder="İşin potansiyel risklerini ve alınacak tedbirleri belirtin"></textarea>
                        </div>

                        <!-- Özel Koşullar -->
                        <div class="mb-3">
                            <label for="special_conditions" class="form-label">Özel Koşullar</label>
                            <textarea name="special_conditions" id="special_conditions" class="form-control" rows="2"
                                      placeholder="İş için gerekli özel koşullar veya gereksinimler"></textarea>
                        </div>

                        <!-- Gönder Butonları -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('field.qhse.safety.permits.index') }}" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-send"></i> İzni Oluştur ve Onaya Gönder
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
                    <h6 class="card-title mb-0">Çalışma İzni Rehberi</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Yüksekte Çalışma:</strong> 2m'den yüksek çalışmalarda zorunlu
                    </div>
                    <div class="alert alert-warning">
                        <strong>Sıcak İşlem:</strong> Kaynak, kesme, taşlama gibi ateşli işler
                    </div>
                    <div class="alert alert-danger">
                        <strong>Kapalı Alan:</strong> Havalandırma sınırlı, tehlikeli gaz potansiyeli olan alanlar
                    </div>
                    <div class="alert alert-secondary">
                        <strong>Elektrik:</strong> Elektrik panosu, kablo çalışmaları
                    </div>

                    <hr>
                    <p class="mb-1"><strong>Onay Akışı:</strong></p>
                    <small class="text-muted">
                        1. İSG Uzmanı ön incelemesi<br>
                        2. Şantiye Şefi onayı<br>
                        3. Çalışma başlatılır
                    </small>
                </div>
            </div>

            <!-- İzin İstatistikleri -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">Aktif İzinler</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-primary">{{ \App\Models\Permit::where('status', 'active')->count() }}</h3>
                        <p class="text-muted mb-0">Şu anda aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Güvenlik Checklist'leri
const checklists = {
    yuksekte_calisma: [
        {id: 'kemer_kontrol', text: 'Emniyet kemeri ve lanyard kontrol edildi mi?', required: true},
        {id: 'iskele_guvenlik', text: 'İskele güvenli ve sağlam mı?', required: true},
        {id: 'platform_guvenlik', text: 'Çalışma platformu düzgün kuruldu mu?', required: true},
        {id: 'seritlendirme', text: 'Alan aşağıdan şerit ile çevrildi mi?', required: true},
        {id: 'acil_durum', text: 'Acil durum ekipmanları hazır mı?', required: true},
        {id: 'egitim', text: 'Çalışanlar yüksekte çalışma eğitimi aldı mı?', required: true}
    ],
    sicak_islem: [
        {id: 'yangin_sondurucu', text: 'Yangın söndürücü hazır mı?', required: true},
        {id: 'havalandirma', text: 'Alan iyi havalandırılıyor mu?', required: true},
        {id: 'yangin_izni', text: 'İtfaiye izni alındı mı?', required: true},
        {id: 'ates_duvari', text: 'Ateş duvarı kuruldu mu?', required: true},
        {id: 'kaynakci_egitimi', text: 'Kaynakçı sertifikası var mı?', required: true},
        {id: 'komur_tutucu', text: 'Kömür tutucu hazır mı?', required: true}
    ],
    kapali_alan: [
        {id: 'gaz_olcum', text: 'Gaz ölçümü yapıldı mı?', required: true},
        {id: 'havalandirma_sistem', text: 'Havalandırma sistemi çalışıyor mu?', required: true},
        {id: 'acil_cikis', text: 'Acil çıkış yolu açık mı?', required: true},
        {id: 'haberlesme', text: 'Haberleşme sistemi hazır mı?', required: true},
        {id: 'gozlemci', text: 'Sürekli gözlemci atanacak mı?', required: true},
        {id: 'kurtarma', text: 'Kurtarma ekipmanı hazır mı?', required: true}
    ],
    elektrik: [
        {id: 'elektrik_kesildi', text: 'Elektrik kesildi ve kilitlendi mi?', required: true},
        {id: 'gerilim_olcum', text: 'Gerilim ölçümü yapıldı mı?', required: true},
        {id: 'elektrikci_egitim', text: 'Elektrikçi sertifikası var mı?', required: true},
        {id: 'izole_eldiven', text: 'İzole eldiven ve ayakkabı var mı?', required: true},
        {id: 'acil_durdur', text: 'Acil durdurma butonu çalışıyor mu?', required: true},
        {id: 'topraklama', text: 'Topraklama yapıldı mı?', required: true}
    ],
    kazma_hafriyat: [
        {id: 'yer_inceleme', text: 'Yer altı hattı incelemesi yapıldı mı?', required: true},
        {id: 'seritlendirme', text: 'Çalışma alanı şeritlendi mi?', required: true},
        {id: 'trafik_yonlendirme', text: 'Trafik yönlendirmesi yapıldı mı?', required: true},
        {id: 'hafriyat_izin', text: 'Hafriyat izni alındı mı?', required: true},
        {id: 'destek_sistem', text: 'Destek sistemi kuruldu mu?', required: true},
        {id: 'acil_plan', text: 'Acil durum planı hazır mı?', required: true}
    ]
};

function updateChecklist() {
    const type = document.getElementById('type').value;
    const checklistSection = document.getElementById('checklist-section');
    const checklistItems = document.getElementById('checklist-items');

    if (type && checklists[type]) {
        checklistSection.style.display = 'block';
        const items = checklists[type];

        checklistItems.innerHTML = items.map(item => `
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="checklist[]" value="${item.id}" id="${item.id}" ${item.required ? 'required' : ''}>
                <label class="form-check-label" for="${item.id}">
                    ${item.text} ${item.required ? '<span class="text-danger">*</span>' : ''}
                </label>
            </div>
        `).join('');
    } else {
        checklistSection.style.display = 'none';
        checklistItems.innerHTML = '';
    }
}

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
@endsection
