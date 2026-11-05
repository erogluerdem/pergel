// Saha Operasyonları - Basit JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Saha Operasyonları başlatılıyor...');

    // Temel event listener'lar
    setupBasicEventListeners();

    // Takvim başlat
    initCalendar();

    // Harita başlat (Leaflet varsa)
    // Do not force-init the map if it's inside a hidden tab. Instead initialize or invalidate when the map tab is shown.
    if (typeof L !== 'undefined') {
        // If the map tab is already active, init immediately, otherwise wait for tab show
        const activeMapTab = document.querySelector('#map-tab');
        if (activeMapTab && activeMapTab.classList.contains('active')) {
            initMap();
        }
    }

    // Listen for Bootstrap tab shown events to initialize or resize the map when the map tab becomes visible
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(tabEl) {
        tabEl.addEventListener('shown.bs.tab', function(e) {
            try {
                if (e.target && e.target.id === 'map-tab') {
                    if (typeof L === 'undefined') return;
                    // If map not created yet, create it. Otherwise invalidate size so it fills container.
                    if (!window.map) {
                        initMap();
                        // Small timeout to allow layout to settle
                        setTimeout(function() { if (window.map && typeof window.map.invalidateSize === 'function') window.map.invalidateSize(); }, 200);
                    } else {
                        setTimeout(function() { if (window.map && typeof window.map.invalidateSize === 'function') window.map.invalidateSize(); }, 200);
                    }
                }
            } catch (err) {
                console.error('Tab shown handler error', err);
            }
        });
    });
});

function setupBasicEventListeners() {
    // Proje seçimi
    const projectSelect = document.getElementById('project-select');
    if (projectSelect) {
        projectSelect.addEventListener('change', function() {
            console.log('Proje seçildi:', this.value);
            // Proje değiştiğinde görevleri yeniden yükle
            if (document.getElementById('daily-report-modal').classList.contains('show')) {
                loadTasks();
            }
        });
    }

    // Medya yükleme
    const uploadBtn = document.getElementById('upload-btn');
    const mediaUploadEl = document.getElementById('media-upload');
    if (uploadBtn && mediaUploadEl) {
        uploadBtn.addEventListener('click', function() {
            mediaUploadEl.click();
        });
    }

    // Modal butonları
    const addLabourBtn = document.getElementById('add-labour-btn');
    if (addLabourBtn) {
        addLabourBtn.addEventListener('click', function() {
            addLabourItem();
        });
    }

    const addEquipmentBtn = document.getElementById('add-equipment-btn');
    if (addEquipmentBtn) {
        addEquipmentBtn.addEventListener('click', function() {
            addEquipmentItem();
        });
    }

    // Rapor kaydetme butonları
    const saveDraftBtn = document.getElementById('save-draft-btn');
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            saveReport(true);
        });
    }

    const submitReportBtn = document.getElementById('submit-report-btn');
    if (submitReportBtn) {
        submitReportBtn.addEventListener('click', function() {
            saveReport(false);
        });
    }

    // Medya arama
    const mediaSearch = document.getElementById('media-search');
    if (mediaSearch) {
        mediaSearch.addEventListener('input', function() {
            filterMedia(this.value);
        });
    }

    // Medya yükleme işlemi
    const mediaUploadInput = document.getElementById('media-upload');
    if (mediaUploadInput) {
        mediaUploadInput.addEventListener('change', function(e) {
            handleMediaUpload(e.target.files);
        });
    }
}

function initCalendar() {
    const calendarEl = document.getElementById('report-calendar');
    if (!calendarEl) return;

    // Geçerli tarih
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    // Takvim başlığı
    const headerEl = document.createElement('div');
    headerEl.className = 'calendar-header mb-3';
    headerEl.innerHTML = `
        <button class="btn btn-sm btn-outline-primary" onclick="changeMonth(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h5 class="mb-0" id="calendar-title">${getMonthName(currentMonth)} ${currentYear}</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="changeMonth(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

    // Takvim grid
    const gridEl = document.createElement('div');
    gridEl.className = 'calendar-grid';
    gridEl.id = 'calendar-grid';

    calendarEl.innerHTML = '';
    calendarEl.appendChild(headerEl);
    calendarEl.appendChild(gridEl);

    // Takvimi oluştur
    renderCalendar(currentYear, currentMonth);

    // Global değişkenleri sakla
    window.currentMonth = currentMonth;
    window.currentYear = currentYear;
}

function initMap() {
    if (typeof L === 'undefined') return;
    const mapEl = document.getElementById('fo-map');
    if (!mapEl) return;
    // Avoid initializing twice
    if (window.map) return;

    // Niğde merkez
    window.map = L.map('fo-map').setView([37.9667, 34.6833], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.map);

    // Örnek marker'lar ekle
    addSampleMarkers();

    // Ensure correct sizing after initialization
    setTimeout(function() { try { if (window.map && typeof window.map.invalidateSize === 'function') window.map.invalidateSize(); } catch(e){} }, 250);
}

function addSampleMarkers() {
    if (!window.map) return;

    const sampleLocations = [
        { lat: 37.9667, lng: 34.6833, title: 'Şantiye Merkezi', type: 'construction' },
        { lat: 37.95, lng: 34.65, title: 'Ekipman Deposu', type: 'equipment' },
        { lat: 37.98, lng: 34.70, title: 'Malzeme Alanı', type: 'material' }
    ];

    sampleLocations.forEach(location => {
        const marker = L.marker([location.lat, location.lng]).addTo(window.map);
        marker.bindPopup(`<strong>${location.title}</strong>`);
    });
}

function addLabourItem() {
    const labourList = document.getElementById('labour-list');
    if (!labourList) return;

    const itemEl = document.createElement('div');
    itemEl.className = 'labour-item';
    itemEl.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Taşeron Firma</label>
                <select class="form-select" required>
                    <option value="">Seçin...</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Meslek Grubu</label>
                <input type="text" class="form-control" placeholder="örn: Betoncu" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Çalışan Sayısı</label>
                <input type="number" class="form-control" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Toplam Saat</label>
                <input type="number" class="form-control" min="0" step="0.5" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest('.labour-item').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    labourList.appendChild(itemEl);
}

function addEquipmentItem() {
    const equipmentList = document.getElementById('equipment-list');
    if (!equipmentList) return;

    const itemEl = document.createElement('div');
    itemEl.className = 'equipment-item';
    itemEl.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Ekipman</label>
                <select class="form-select" required>
                    <option value="">Seçin...</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kodu/Plaka</label>
                <input type="text" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">Çalışma Saati</label>
                <input type="number" class="form-control" min="0" step="0.5" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Boşta Saat</label>
                <input type="number" class="form-control" min="0" step="0.5" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest('.equipment-item').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    equipmentList.appendChild(itemEl);
}

function loadTasks() {
    const tasksList = document.getElementById('tasks-list');
    if (!tasksList) return;

    // Örnek görevler - gerçek API çağrısı yapılacak
    const sampleTasks = [
        { id: 1, name: '2. Kat Kalıp Montajı', progress: 50 },
        { id: 2, name: 'Temel Kazısı', progress: 75 },
        { id: 3, name: 'Demir Bağlama', progress: 30 }
    ];

    displayTasks(sampleTasks);
}

function displayTasks(tasks) {
    const tasksList = document.getElementById('tasks-list');
    if (!tasksList) return;

    tasksList.innerHTML = '';

    if (tasks.length === 0) {
        tasksList.innerHTML = '<p class="text-muted">Bu proje için görev bulunamadı.</p>';
        return;
    }

    tasks.forEach(function(task) {
        const taskEl = document.createElement('div');
        taskEl.className = 'task-item mb-2';
        taskEl.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="task-${task.id}" data-task-id="${task.id}">
                    <label class="form-check-label" for="task-${task.id}">
                        ${task.name}
                    </label>
                </div>
                <div class="d-flex align-items-center">
                    <small class="text-muted me-2">Mevcut: %${task.progress}</small>
                    <input type="number" class="form-control form-control-sm" style="width: 80px;" min="0" max="100" value="${task.progress}" data-task-id="${task.id}">
                    <span class="ms-1">%</span>
                </div>
            </div>
            <div class="progress mt-2" style="height: 8px;">
                <div class="progress-bar bg-success" style="width: ${task.progress}%"></div>
            </div>
        `;
        tasksList.appendChild(taskEl);
    });
}

// Global fonksiyonlar
function changeMonth(delta) {
    window.currentMonth += delta;

    if (window.currentMonth < 0) {
        window.currentMonth = 11;
        window.currentYear--;
    } else if (window.currentMonth > 11) {
        window.currentMonth = 0;
        window.currentYear++;
    }

    // Başlığı güncelle
    const titleEl = document.getElementById('calendar-title');
    if (titleEl) {
        titleEl.textContent = `${getMonthName(window.currentMonth)} ${window.currentYear}`;
    }

    // Takvimi yeniden oluştur
    renderCalendar(window.currentYear, window.currentMonth);
}

function renderCalendar(year, month) {
    const gridEl = document.getElementById('calendar-grid');
    if (!gridEl) return;

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());

    const endDate = new Date(lastDay);
    endDate.setDate(endDate.getDate() + (6 - lastDay.getDay()));

    let html = '';

    // Gün başlıkları
    const dayNames = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
    dayNames.forEach(day => {
        html += `<div class="calendar-day-header">${day}</div>`;
    });

    // Gün hücreleri
    let currentDate = new Date(startDate);
    while (currentDate <= endDate) {
        const isCurrentMonth = currentDate.getMonth() === month;
        const isToday = currentDate.toDateString() === new Date().toDateString();
        const dateStr = currentDate.toISOString().split('T')[0];

        let classes = 'calendar-day';
        if (!isCurrentMonth) classes += ' other-month';
        if (isToday) classes += ' today';

        // Örnek: Bazı tarihlerde rapor olduğunu varsayalım
        const hasReport = Math.random() > 0.7; // Rastgele rapor simülasyonu
        if (hasReport) classes += ' has-report';

        html += `
            <div class="${classes}" onclick="openReportModal('${dateStr}')">
                <div class="day-number">${currentDate.getDate()}</div>
                ${hasReport ? '<div class="report-indicator"><i class="fas fa-check-circle"></i></div>' : ''}
            </div>
        `;

        currentDate.setDate(currentDate.getDate() + 1);
    }

    gridEl.innerHTML = html;
}

function getMonthName(month) {
    const months = [
        'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
        'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
    ];
    return months[month];
}

function openReportModal(dateStr) {
    console.log('Rapor modal açılıyor:', dateStr);

    // Seçilen tarihi sakla
    window.selectedDate = dateStr;

    // Modal başlığını güncelle
    const modalTitle = document.querySelector('#daily-report-modal .modal-title');
    if (modalTitle) {
        const date = new Date(dateStr);
        modalTitle.textContent = `${date.getDate()} ${getMonthName(date.getMonth())} ${date.getFullYear()} - Günlük Rapor`;
    }

    // Bootstrap modal aç
    const modal = new bootstrap.Modal(document.getElementById('daily-report-modal'));
    modal.show();

    // Görevleri yükle
    loadTasks();

    // Hava durumunu yükle
    loadWeather();

    // Notlar kısmını öne çıkar - modal açıldıktan sonra odakla
    setTimeout(function() {
        const noteTypeSelect = document.getElementById('note-type');
        if (noteTypeSelect) {
            noteTypeSelect.focus();
        }
    }, 500);
}

function saveReport(asDraft = false) {
    console.log('Rapor kaydediliyor...', asDraft ? 'Taslak olarak' : 'Onaya gönderiliyor');

    // Form verilerini topla
    const formData = new FormData();

    // Temel bilgiler
    formData.append('date', window.selectedDate || new Date().toISOString().split('T')[0]);
    formData.append('project_id', document.getElementById('project-select')?.value || '');
    formData.append('weather_condition', document.getElementById('weather-condition')?.value || '');
    formData.append('temperature', document.getElementById('temperature')?.value || '');
    formData.append('status', asDraft ? 'draft' : 'submitted');

    // İş gücü verileri
    const labourItems = document.querySelectorAll('.labour-item');
    labourItems.forEach((item, index) => {
        const subcontractor = item.querySelector('select')?.value || '';
        const profession = item.querySelector('input[placeholder*="Meslek"]')?.value || '';
        const workerCount = item.querySelector('input[type="number"]:nth-of-type(1)')?.value || '';
        const totalHours = item.querySelector('input[type="number"]:nth-of-type(2)')?.value || '';

        if (subcontractor || profession || workerCount || totalHours) {
            formData.append(`labour[${index}][subcontractor]`, subcontractor);
            formData.append(`labour[${index}][profession]`, profession);
            formData.append(`labour[${index}][worker_count]`, workerCount);
            formData.append(`labour[${index}][total_hours]`, totalHours);
        }
    });

    // Ekipman verileri
    const equipmentItems = document.querySelectorAll('.equipment-item');
    equipmentItems.forEach((item, index) => {
        const equipment = item.querySelector('select')?.value || '';
        const code = item.querySelector('input[readonly]')?.value || '';
        const workingHours = item.querySelector('input[type="number"]:nth-of-type(1)')?.value || '';
        const idleHours = item.querySelector('input[type="number"]:nth-of-type(2)')?.value || '';

        if (equipment || workingHours || idleHours) {
            formData.append(`equipment[${index}][equipment]`, equipment);
            formData.append(`equipment[${index}][code]`, code);
            formData.append(`equipment[${index}][working_hours]`, workingHours);
            formData.append(`equipment[${index}][idle_hours]`, idleHours);
        }
    });

    // Görev ilerlemeleri
    const taskCheckboxes = document.querySelectorAll('#tasks-list input[type="checkbox"]:checked');
    const taskProgresses = document.querySelectorAll('#tasks-list input[type="number"]');

    taskCheckboxes.forEach(checkbox => {
        const taskId = checkbox.dataset.taskId;
        formData.append('completed_tasks[]', taskId);
    });

    taskProgresses.forEach(input => {
        const taskId = input.dataset.taskId;
        const progress = input.value;
        formData.append(`task_progress[${taskId}]`, progress);
    });

    // Notlar
    const noteType = document.getElementById('note-type')?.value || '';
    const noteText = document.getElementById('daily-notes')?.value || '';
    const criticalEvent = document.getElementById('critical-event')?.checked ? 1 : 0;

    formData.append('note_type', noteType);
    formData.append('notes', noteText);
    formData.append('critical_event', criticalEvent);

    // API çağrısı simülasyonu
    console.log('Gönderilecek veri:', Object.fromEntries(formData));

    // Başarı mesajı göster
    showAlert(asDraft ? 'Taslak olarak kaydedildi!' : 'Rapor onaya gönderildi!', 'success');

    // Modal'ı kapat
    const modal = bootstrap.Modal.getInstance(document.getElementById('daily-report-modal'));
    if (modal) {
        modal.hide();
    }

    // Takvimi güncelle
    setTimeout(() => {
        renderCalendar(window.currentYear, window.currentMonth);
    }, 1000);
}

function loadWeather() {
    const weatherEl = document.getElementById('weather-info');
    if (!weatherEl) return;

    // Hava durumu API çağrısı simülasyonu
    const mockWeather = {
        temperature: 24,
        condition: 'Güneşli',
        humidity: 45,
        wind_speed: 12
    };

    weatherEl.innerHTML = `
        <div class="weather-icon">
            <i class="fas fa-sun"></i>
        </div>
        <div class="weather-temp">${mockWeather.temperature}°C</div>
        <div class="weather-desc">${mockWeather.condition}</div>
        <div class="weather-details">
            Nem: ${mockWeather.humidity}%<br>
            Rüzgar: ${mockWeather.wind_speed} km/s
        </div>
    `;
}

function showAlert(message, type = 'info') {
    // Basit alert gösterimi
    const alertEl = document.createElement('div');
    alertEl.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertEl.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertEl.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertEl);

    // 5 saniye sonra otomatik kapat
    setTimeout(() => {
        if (alertEl.parentNode) {
            alertEl.remove();
        }
    }, 5000);
}

function handleMediaUpload(files) {
    if (!files || files.length === 0) return;

    console.log('Medya yükleniyor:', files.length, 'dosya');

    Array.from(files).forEach(file => {
        // Dosya türü kontrolü
        if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
            showAlert('Sadece resim ve video dosyaları yüklenebilir!', 'warning');
            return;
        }

        // Dosya boyutu kontrolü (10MB)
        if (file.size > 10 * 1024 * 1024) {
            showAlert('Dosya boyutu 10MB\'dan büyük olamaz!', 'warning');
            return;
        }

        // Önizleme oluştur
        const reader = new FileReader();
        reader.onload = function(e) {
            addMediaToGallery(file, e.target.result);
        };
        reader.readAsDataURL(file);
    });
}

function addMediaToGallery(file, dataUrl) {
    const galleryEl = document.getElementById('media-gallery');
    if (!galleryEl) return;

    const mediaItem = document.createElement('div');
    mediaItem.className = 'media-item col-6 col-md-4';
    mediaItem.setAttribute('role', 'listitem');

    const isVideo = file.type.startsWith('video/');
    const mediaElement = isVideo
        ? `<video src="${dataUrl}" controls style="width: 100%; height: 150px; object-fit: cover;"></video>`
        : `<img src="${dataUrl}" alt="${file.name}" style="width: 100%; height: 150px; object-fit: cover;">`;

    mediaItem.innerHTML = `
        ${mediaElement}
        <div class="media-overlay">
            <div class="media-info">
                <div>
                    <strong>${file.name}</strong><br>
                    <small>${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-danger" onclick="removeMedia(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    galleryEl.appendChild(mediaItem);

    // Eğer galeri boş mesajı görünürse gizle
    const emptyEl = document.getElementById('media-empty');
    if (emptyEl) emptyEl.style.display = 'none';

    // Haritaya marker ekle (GPS bilgisi varsa)
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            addMediaMarker(position.coords.latitude, position.coords.longitude, file.name, dataUrl);
        });
    }

    showAlert('Medya başarıyla eklendi!', 'success');
}

function removeMedia(button) {
    const mediaItem = button.closest('.media-item');
    if (mediaItem) {
        mediaItem.remove();
        showAlert('Medya kaldırıldı!', 'info');
    }
}

function addMediaMarker(lat, lng, title, dataUrl) {
    if (typeof L === 'undefined' || !window.map) return;

    const marker = L.marker([lat, lng]).addTo(window.map);
    marker.bindPopup(`
        <div style="text-align: center;">
            <strong>${title}</strong><br>
            <img src="${dataUrl}" style="width: 100px; height: 75px; object-fit: cover; margin-top: 5px;">
        </div>
    `);
}

function loadMediaGallery() {
    const galleryEl = document.getElementById('media-gallery');
    if (!galleryEl) return;

    // Örnek medya verileri - gerçek API çağrısı yapılacak
    const sampleMedia = [
        { id: 1, type: 'image', url: '/images/sample1.jpg', name: 'Şantiye görünümü', date: '2024-01-15' },
        // Replaced sample video with a placeholder image to avoid mime/content-type errors in demo environments
        { id: 2, type: 'image', url: 'data:image/svg+xml;utf8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="800" height="450"><rect width="100%" height="100%" fill="#e9ecef"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#6c757d" font-family="Arial, Helvetica, sans-serif" font-size="20">Video önizlemesi (placeholder)</text></svg>'), name: 'İş makinesi çalışması (placeholder)', date: '2024-01-14' }
    ];

    galleryEl.innerHTML = '';

    sampleMedia.forEach(media => {
        const mediaItem = document.createElement('div');
        mediaItem.className = 'media-item';

        const mediaElement = media.type === 'video'
            ? `<video src="${media.url}" controls style="width: 100%; height: 150px; object-fit: cover;"></video>`
            : `<img src="${media.url}" alt="${media.name}" style="width: 100%; height: 150px; object-fit: cover;">`;

        mediaItem.innerHTML = `
            ${mediaElement}
            <div class="media-overlay">
                <div class="media-info">
                    <div>
                        <strong>${media.name}</strong><br>
                        <small>${media.date}</small>
                    </div>
                </div>
            </div>
        `;

        galleryEl.appendChild(mediaItem);
    });

    // Eğer hiç medya yoksa boş uyarısını göster
    const emptyEl = document.getElementById('media-empty');
    if (emptyEl) {
        emptyEl.style.display = sampleMedia.length === 0 ? 'block' : 'none';
    }
}

// Sayfa yüklendiğinde medya galerisini başlat
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    // Medya galerisini yükle
    loadMediaGallery();
});

function filterMedia(searchTerm) {
    const mediaItems = document.querySelectorAll('#media-gallery .media-item');

    mediaItems.forEach(item => {
        const title = item.querySelector('strong')?.textContent || '';
        const isVisible = title.toLowerCase().includes(searchTerm.toLowerCase());
        item.style.display = isVisible ? 'block' : 'none';
    });
}
