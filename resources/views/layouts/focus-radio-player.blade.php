<!-- Persistent Focus Music & Radio Player -->
<div id="pergel-radio-widget" class="pergel-radio-widget shadow-lg d-none">
    <!-- Audio Element -->
    <audio id="pergel-radio-audio" preload="metadata" loop></audio>

    <!-- Minimized View Bar -->
    <div id="radio-minimized-view" class="d-flex align-items-center justify-content-between px-3 py-2 rounded-pill d-none">
        <div class="d-flex align-items-center gap-2 overflow-hidden cursor-pointer" id="mini-radio-info" style="cursor: pointer;">
            <div class="radio-disc-small spinning">
                <i class="ri-disc-line radio-accent-text"></i>
            </div>
            <div class="text-truncate">
                <span id="mini-station-title" class="fw-bold radio-title-text small d-block text-truncate mb-0" style="font-size: 13px;">Power FM Canlı</span>
                <span class="radio-subtitle-text extra-small d-flex align-items-center gap-1" style="font-size: 10px;">
                    <span class="radio-live-dot"></span><span id="mini-status-text">Canlı Yayında</span>
                </span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-1 ms-2">
            <button type="button" class="btn btn-sm btn-icon radio-control-icon p-1" id="mini-play-btn" title="Oynat / Durdur">
                <i id="mini-play-icon" class="ri-play-fill fs-5 radio-accent-text"></i>
            </button>
            <button type="button" class="btn btn-sm btn-icon radio-control-icon p-1" id="mini-expand-btn" title="Genişlet">
                <i class="ri-arrow-up-s-line fs-5"></i>
            </button>
            <button type="button" class="btn btn-sm btn-icon radio-control-icon p-1" id="radio-close-btn" title="Kapat">
                <i class="ri-close-line fs-6"></i>
            </button>
        </div>
    </div>

    <!-- Expanded Player View -->
    <div id="radio-expanded-view" class="p-3 rounded-4">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom radio-border-subtle pb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="radio-header-icon rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="ri-radio-2-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold radio-title-text mb-0" style="font-size:14px;">Odaklanma Müzikleri & Radyo</h6>
                    <small class="radio-subtitle-text extra-small" style="font-size:11px;">SQL Veritabanı Destekli Canlı Akış</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-sm rounded-pill px-2 py-0 extra-small radio-add-btn" id="open-add-radio-btn" title="SQL Veritabanına Yeni Radyo Ekle">
                    <i class="ri-add-line me-1"></i>Kanal Ekle
                </button>
                <button type="button" class="btn btn-sm btn-icon radio-control-icon ms-1" id="expanded-minimize-btn" title="Küçült">
                    <i class="ri-arrow-down-s-line fs-5"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon radio-control-icon" id="expanded-close-btn" title="Gizle">
                    <i class="ri-close-line fs-5"></i>
                </button>
            </div>
        </div>

        <!-- Add Custom Radio Form Container (Collapsible) -->
        <div id="add-radio-form-card" class="radio-form-card p-3 rounded-3 mb-3 d-none">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="fw-bold radio-accent-text mb-0 fs-13"><i class="ri-database-2-line me-1"></i>SQL Veritabanına Yeni Radyo Kaydet</h6>
                <button type="button" class="btn-close small radio-btn-close" id="close-add-radio-form"></button>
            </div>
            <form id="add-radio-form" onsubmit="event.preventDefault(); submitNewRadio();">
                <div class="mb-2">
                    <input type="text" id="new-radio-title" class="form-control form-control-sm radio-form-input" placeholder="Kanal / Radyo Adı (Örn: Alem FM)" required>
                </div>
                <div class="mb-2">
                    <input type="text" id="new-radio-genre" class="form-control form-control-sm radio-form-input" placeholder="Tür / Açıklama (Örn: Türkçe Pop)">
                </div>
                <div class="mb-2">
                    <input type="url" id="new-radio-src" class="form-control form-control-sm radio-form-input" placeholder="Yayın URL (https://... mp3/stream)" required>
                </div>
                <button type="submit" class="btn btn-sm radio-submit-btn w-100 fw-bold" id="save-radio-submit-btn">
                    <i class="ri-save-line me-1"></i>SQL Veritabanına Kaydet
                </button>
            </form>
        </div>

        <!-- Station Selector Pills -->
        <div class="radio-stations-scroll mb-3">
            <div class="d-flex gap-2 pb-1 overflow-x-auto align-items-center" id="radio-stations-list">
                <!-- Dynamically populated from SQL -->
            </div>
        </div>

        <!-- Player Card -->
        <div class="radio-player-card rounded-3 p-3 text-center position-relative overflow-hidden mb-3">
            <div class="radio-vinyl-wrapper mx-auto mb-2">
                <div id="radio-vinyl-disc" class="radio-vinyl-disc">
                    <i class="ri-disc-fill radio-accent-text" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                <h6 id="expanded-station-title" class="fw-bold radio-title-text mb-0">Power FM Canlı</h6>
                <button type="button" id="delete-current-station-btn" class="btn btn-sm text-danger p-0 d-none" title="Bu Kanalı SQL'den Sil">
                    <i class="ri-delete-bin-line fs-6"></i>
                </button>
            </div>
            <p id="expanded-station-genre" class="radio-subtitle-text small mb-2" style="font-size: 11px;">Yabancı Pop & Dünya Hitleri</p>

            <!-- Animated Equalizer Bars -->
            <div id="radio-equalizer" class="radio-equalizer d-flex align-items-end justify-content-center gap-1 mb-3">
                <span class="eq-bar bar-1"></span>
                <span class="eq-bar bar-2"></span>
                <span class="eq-bar bar-3"></span>
                <span class="eq-bar bar-4"></span>
                <span class="eq-bar bar-5"></span>
            </div>

            <!-- Main Controls -->
            <div class="d-flex align-items-center justify-content-center gap-3">
                <button type="button" class="btn btn-icon radio-nav-btn p-2" id="prev-station-btn" title="Önceki Kanal">
                    <i class="ri-skip-back-fill fs-4"></i>
                </button>
                <button type="button" id="expanded-play-btn" class="btn radio-main-play-btn rounded-circle p-3 d-flex align-items-center justify-content-center shadow-lg" style="width:48px; height:48px;">
                    <i id="expanded-play-icon" class="ri-play-fill fs-3 ms-1"></i>
                </button>
                <button type="button" class="btn btn-icon radio-nav-btn p-2" id="next-station-btn" title="Sonraki Kanal">
                    <i class="ri-skip-forward-fill fs-4"></i>
                </button>
            </div>
        </div>

        <!-- Volume Control Slider -->
        <div class="d-flex align-items-center gap-2 px-1">
            <i class="ri-volume-mute-line radio-subtitle-text" style="font-size: 14px;"></i>
            <input type="range" class="form-range flex-fill radio-volume-range" id="radio-volume-slider" min="0" max="1" step="0.05" value="0.7">
            <i class="ri-volume-up-line radio-subtitle-text" style="font-size: 14px;"></i>
        </div>
    </div>
</div>

<style>
/* ── Floating Player Widget — Tema Değişkenlerine Tam Uyum ── */
.pergel-radio-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 1060;
    width: 340px;
    max-width: calc(100vw - 32px);
    background: var(--pt-modal-bg, #1e293b) !important;
    border: 1px solid var(--pt-modal-border, rgba(255, 255, 255, 0.15)) !important;
    box-shadow: var(--pt-card-shadow, 0 10px 30px rgba(0, 0, 0, 0.35)), 0 0 24px rgba(var(--pt-primary-rgb, 245, 158, 11), 0.2) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: var(--pt-card-radius, 18px) !important;
    color: var(--pt-body-color, #e2e8f0) !important;
    font-family: var(--pt-font-sans, 'Inter', sans-serif) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#radio-minimized-view {
    background: var(--pt-card-bg, #1e293b) !important;
    border: 1px solid var(--pt-card-border, rgba(255, 255, 255, 0.15)) !important;
    box-shadow: var(--pt-card-shadow, 0 6px 20px rgba(0, 0, 0, 0.25)) !important;
    color: var(--pt-body-color, #e2e8f0) !important;
}

.radio-border-subtle {
    border-color: var(--pt-modal-border, rgba(255, 255, 255, 0.1)) !important;
}

.radio-title-text {
    color: var(--pt-heading-color, #ffffff) !important;
    font-family: var(--pt-font-heading, inherit) !important;
}

.radio-subtitle-text {
    color: var(--pt-text-muted, #94a3b8) !important;
}

.radio-accent-text {
    color: var(--pt-primary, #f59e0b) !important;
}

.radio-control-icon {
    color: var(--pt-text-muted, #94a3b8) !important;
    background: transparent !important;
    border: none !important;
    transition: color 0.18s, transform 0.18s;
}
.radio-control-icon:hover {
    color: var(--pt-heading-color, #ffffff) !important;
    transform: scale(1.1);
}

.radio-header-icon {
    background: var(--pt-primary-subtle, rgba(245, 158, 11, 0.15)) !important;
    color: var(--pt-primary, #f59e0b) !important;
}

.radio-add-btn {
    border: 1px solid var(--pt-primary, #f59e0b) !important;
    color: var(--pt-primary, #f59e0b) !important;
    background: transparent !important;
    font-weight: 600;
    transition: all 0.18s;
}
.radio-add-btn:hover {
    background: var(--pt-primary, #f59e0b) !important;
    color: var(--pt-btn-primary-text, #ffffff) !important;
    transform: translateY(-1px);
}

.radio-form-card {
    background: var(--pt-card-bg, rgba(0, 0, 0, 0.35)) !important;
    border: 1.5px solid var(--pt-primary, #f59e0b) !important;
    border-radius: var(--pt-card-radius, 10px) !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.radio-form-input {
    background: var(--pt-input-bg, rgba(0, 0, 0, 0.25)) !important;
    color: var(--pt-input-text, #ffffff) !important;
    border: 1px solid var(--pt-input-border, rgba(255, 255, 255, 0.15)) !important;
    border-radius: var(--pt-input-radius, 8px) !important;
}
.radio-form-input:focus {
    border-color: var(--pt-primary, #f59e0b) !important;
    box-shadow: 0 0 0 2px var(--pt-primary-subtle, rgba(245, 158, 11, 0.2)) !important;
}

.radio-submit-btn {
    background: var(--pt-primary, #f59e0b) !important;
    color: var(--pt-btn-primary-text, #ffffff) !important;
    border: none !important;
    border-radius: var(--pt-btn-radius, 8px) !important;
    transition: all 0.18s;
}
.radio-submit-btn:hover {
    background: var(--pt-primary-hover, #d97706) !important;
    color: var(--pt-btn-primary-text, #ffffff) !important;
    transform: translateY(-1px);
}

.radio-player-card {
    background: var(--pt-table-stripe-bg, rgba(0, 0, 0, 0.2)) !important;
    border: 1px solid var(--pt-card-border, rgba(255, 255, 255, 0.08)) !important;
    border-radius: var(--pt-card-radius, 12px) !important;
}

.radio-stations-scroll {
    scrollbar-width: thin;
    scrollbar-color: rgba(var(--pt-primary-rgb, 245, 158, 11), 0.3) transparent;
}

.radio-station-pill {
    padding: 6px 12px;
    border-radius: var(--pt-badge-radius, 20px);
    background: var(--pt-card-bg, rgba(255, 255, 255, 0.08)) !important;
    border: 1px solid var(--pt-card-border, rgba(255, 255, 255, 0.1)) !important;
    color: var(--pt-body-color, #cbd5e1) !important;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s ease;
}

.radio-station-pill:hover {
    background: var(--pt-sidebar-hover-bg, rgba(255, 255, 255, 0.18)) !important;
    color: var(--pt-heading-color, #ffffff) !important;
    border-color: var(--pt-primary, #f59e0b) !important;
    transform: translateY(-1px);
}

.radio-station-pill.active {
    background: var(--pt-primary, #f59e0b) !important;
    color: var(--pt-btn-primary-text, #ffffff) !important;
    border-color: var(--pt-primary, #f59e0b) !important;
    box-shadow: 0 2px 10px rgba(var(--pt-primary-rgb, 245, 158, 11), 0.35) !important;
}

.radio-live-dot {
    width: 7px;
    height: 7px;
    background-color: var(--pt-success, #22c55e);
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 6px var(--pt-success, #22c55e);
}

.radio-main-play-btn {
    background: var(--pt-primary, #f59e0b) !important;
    color: var(--pt-btn-primary-text, #ffffff) !important;
    border: none !important;
    box-shadow: 0 4px 15px rgba(var(--pt-primary-rgb, 245, 158, 11), 0.4) !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.radio-main-play-btn:hover {
    background: var(--pt-primary-hover, #d97706) !important;
    transform: scale(1.06);
}

.radio-nav-btn {
    color: var(--pt-text-muted, #94a3b8) !important;
    background: transparent !important;
    border: none !important;
    transition: color 0.18s, transform 0.18s;
}
.radio-nav-btn:hover {
    color: var(--pt-primary, #f59e0b) !important;
    transform: scale(1.15);
}

.radio-volume-range {
    accent-color: var(--pt-primary, #f59e0b) !important;
}

/* Vinyl Spin */
.radio-disc-small.spinning i,
.radio-vinyl-disc.spinning i {
    animation: spin 3s linear infinite;
    display: inline-block;
}

@keyframes spin {
    100% { transform: rotate(360deg); }
}

/* Equalizer Bars */
.radio-equalizer {
    height: 18px;
}

.eq-bar {
    width: 3px;
    height: 4px;
    background-color: var(--pt-primary, #f59e0b);
    border-radius: 2px;
    transition: height 0.15s ease;
}

.playing .eq-bar.bar-1 { animation: eqAnim 0.8s ease-in-out infinite alternate; }
.playing .eq-bar.bar-2 { animation: eqAnim 0.6s ease-in-out infinite alternate 0.2s; }
.playing .eq-bar.bar-3 { animation: eqAnim 1.0s ease-in-out infinite alternate 0.4s; }
.playing .eq-bar.bar-4 { animation: eqAnim 0.7s ease-in-out infinite alternate 0.1s; }
.playing .eq-bar.bar-5 { animation: eqAnim 0.9s ease-in-out infinite alternate 0.3s; }

@keyframes eqAnim {
    0% { height: 3px; }
    100% { height: 18px; }
}

/* Topbar Equalizer Indicator */
.topbar-eq-bars {
    display: inline-flex;
    align-items: flex-end;
    gap: 2px;
    height: 14px;
    margin-left: 2px;
}
.topbar-eq-bars span {
    width: 2px;
    height: 3px;
    background-color: var(--pt-primary, #f59e0b);
    border-radius: 1px;
}
.topbar-eq-bars.playing span:nth-child(1) { animation: eqAnim 0.6s ease-in-out infinite alternate; }
.topbar-eq-bars.playing span:nth-child(2) { animation: eqAnim 0.9s ease-in-out infinite alternate 0.2s; }
.topbar-eq-bars.playing span:nth-child(3) { animation: eqAnim 0.7s ease-in-out infinite alternate 0.4s; }
</style>


<script>
let STATIONS = [];
let currentStationIndex = 0;
let isPlaying = false;
let isExpanded = true;
let isClosed = false;
const STORAGE_KEY = 'pergel_radio_player_state_v4';

document.addEventListener('DOMContentLoaded', function() {
    initPlayer();
});

function initPlayer() {
    const audio = document.getElementById('pergel-radio-audio');
    const widget = document.getElementById('pergel-radio-widget');

    if (!audio || !widget) return;

    // Fetch radio stations dynamically from MySQL API
    fetchRadioStationsFromDB().then(() => {
        bindEvents();
        loadSavedState();
    });

    // Topbar button toggle
    const topbarBtn = document.getElementById('topbar-radio-toggle-btn');
    if (topbarBtn) {
        topbarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleWidgetVisibility();
        });
    }
}

async function fetchRadioStationsFromDB() {
    try {
        const response = await fetch('/api/radio-stations', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();
        if (data.success && data.stations && data.stations.length > 0) {
            STATIONS = data.stations;
        } else {
            fallbackStations();
        }
    } catch (err) {
        console.warn('Failed to load SQL radio stations, using fallback', err);
        fallbackStations();
    }
    renderStationPills();
}

function fallbackStations() {
    STATIONS = [
        { id: 1, title: '📻 Power FM', genre: 'Canlı Yabancı Pop & Hit Müzik', src: 'https://listen.powerapp.com.tr/powerfm/mpeg/icecast.audio', is_system: true },
        { id: 2, title: '📻 PowerTürk FM', genre: 'Canlı Türkçe Pop & En İyiler', src: 'https://listen.powerapp.com.tr/powerturk/mpeg/icecast.audio', is_system: true },
        { id: 3, title: '📻 Radyo Fenomen', genre: 'Canlı Pop & Dance Hits', src: 'https://listen.powerapp.com.tr/radyofenomen/mpeg/icecast.audio', is_system: true },
        { id: 4, title: '🎧 Deep Focus Lofi', genre: 'Konsantrasyon & Kodlama Beats', src: 'https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3', is_system: true },
        { id: 5, title: '🌧️ Yağmur & Fırtına', genre: 'Sakinleştirici Doğal Yağmur', src: 'https://cdn.pixabay.com/download/audio/2022/03/10/audio_c8c8a73467.mp3', is_system: true },
        { id: 6, title: '☕ Kafe Ambiyansı', genre: 'Sıcak Kahve Dükkanı Ambiyansı', src: 'https://cdn.pixabay.com/download/audio/2021/09/06/audio_8b21c439ed.mp3', is_system: true }
    ];
}

function renderStationPills() {
    const stationsList = document.getElementById('radio-stations-list');
    if (!stationsList) return;
    stationsList.innerHTML = '';

    STATIONS.forEach((st, idx) => {
        const pill = document.createElement('button');
        pill.type = 'button';
        pill.className = `radio-station-pill ${idx === currentStationIndex ? 'active' : ''}`;
        pill.innerHTML = `${st.title} ${st.is_system ? '' : '<span class="badge bg-warning text-dark extra-small ms-1">SQL</span>'}`;
        pill.addEventListener('click', () => selectStation(idx));
        stationsList.appendChild(pill);
    });

    if (STATIONS.length > 0) {
        updateCurrentStationDisplay();
    }
}

function bindEvents() {
    // Minimize/Expand
    document.getElementById('mini-radio-info')?.addEventListener('click', toggleExpand);
    document.getElementById('mini-expand-btn')?.addEventListener('click', toggleExpand);
    document.getElementById('expanded-minimize-btn')?.addEventListener('click', toggleExpand);

    // Play/Pause
    document.getElementById('mini-play-btn')?.addEventListener('click', togglePlay);
    document.getElementById('expanded-play-btn')?.addEventListener('click', togglePlay);

    // Prev/Next
    document.getElementById('prev-station-btn')?.addEventListener('click', prevStation);
    document.getElementById('next-station-btn')?.addEventListener('click', nextStation);

    // Close
    document.getElementById('radio-close-btn')?.addEventListener('click', closeWidget);
    document.getElementById('expanded-close-btn')?.addEventListener('click', closeWidget);

    // Add Radio Form Toggle
    document.getElementById('open-add-radio-btn')?.addEventListener('click', function() {
        const formCard = document.getElementById('add-radio-form-card');
        formCard?.classList.toggle('d-none');
    });
    document.getElementById('close-add-radio-form')?.addEventListener('click', function() {
        document.getElementById('add-radio-form-card')?.classList.add('d-none');
    });

    // Delete station button
    document.getElementById('delete-current-station-btn')?.addEventListener('click', deleteCurrentStation);

    // Volume Slider
    const audio = document.getElementById('pergel-radio-audio');
    const volSlider = document.getElementById('radio-volume-slider');
    if (volSlider && audio) {
        volSlider.addEventListener('input', function() {
            audio.volume = parseFloat(this.value);
            saveState();
        });
    }

    // Save state on unload
    window.addEventListener('beforeunload', saveState);
    window.addEventListener('pagehide', saveState);

    // Audio events
    if (audio) {
        audio.addEventListener('play', () => updatePlayStateUI(true));
        audio.addEventListener('pause', () => updatePlayStateUI(false));
    }
}

async function submitNewRadio() {
    const title = document.getElementById('new-radio-title').value.trim();
    const genre = document.getElementById('new-radio-genre').value.trim();
    const src = document.getElementById('new-radio-src').value.trim();
    const btn = document.getElementById('save-radio-submit-btn');

    if (!title || !src) return;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Kaydediliyor...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch('/api/radio-stations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: JSON.stringify({ title, genre, src })
        });
        const data = await response.json();
        if (data.success && data.station) {
            STATIONS.push(data.station);
            renderStationPills();
            selectStation(STATIONS.length - 1, true);

            // Reset Form & Hide
            document.getElementById('add-radio-form').reset();
            document.getElementById('add-radio-form-card').classList.add('d-none');
        } else {
            alert(data.message || 'Radyo kaydedilirken hata oluştu.');
        }
    } catch (err) {
        alert('Radyo veritabanına kaydedilemedi.');
        console.error(err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-save-line me-1"></i>SQL Veritabanına Kaydet';
    }
}

async function deleteCurrentStation() {
    const station = STATIONS[currentStationIndex];
    if (!station || station.is_system) return;

    if (!confirm(`"${station.title}" radyosunu SQL veritabanından silmek istediğinize emin misiniz?`)) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch(`/api/radio-stations/${station.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            }
        });
        const data = await response.json();
        if (data.success) {
            STATIONS.splice(currentStationIndex, 1);
            if (currentStationIndex >= STATIONS.length) currentStationIndex = 0;
            renderStationPills();
            selectStation(currentStationIndex, isPlaying);
        } else {
            alert(data.message || 'Silme işlemi başarısız.');
        }
    } catch (err) {
        alert('Kanal silinirken bir hata oluştu.');
    }
}

function updateCurrentStationDisplay() {
    const station = STATIONS[currentStationIndex];
    if (!station) return;

    const miniTitle = document.getElementById('mini-station-title');
    const expTitle = document.getElementById('expanded-station-title');
    const expGenre = document.getElementById('expanded-station-genre');
    const deleteBtn = document.getElementById('delete-current-station-btn');

    if (miniTitle) miniTitle.textContent = station.title;
    if (expTitle) expTitle.textContent = station.title;
    if (expGenre) expGenre.textContent = station.genre || 'Canlı Akış';

    if (deleteBtn) {
        deleteBtn.classList.toggle('d-none', !!station.is_system);
    }
}

function selectStation(idx, autoPlay = true) {
    if (idx < 0 || idx >= STATIONS.length) return;
    currentStationIndex = idx;
    const station = STATIONS[idx];
    const audio = document.getElementById('pergel-radio-audio');

    // Update Pill UI
    const pills = document.querySelectorAll('#radio-stations-list .radio-station-pill');
    pills.forEach((p, i) => {
        p.classList.toggle('active', i === idx);
    });

    updateCurrentStationDisplay();

    if (audio) {
        audio.src = station.src;
        if (autoPlay) {
            playAudio();
        } else {
            saveState();
        }
    }
}

function togglePlay(e) {
    if (e) e.stopPropagation();
    if (isPlaying) {
        pauseAudio();
    } else {
        playAudio();
    }
}

function playAudio() {
    const audio = document.getElementById('pergel-radio-audio');
    const widget = document.getElementById('pergel-radio-widget');

    if (isClosed && widget) {
        isClosed = false;
        widget.classList.remove('d-none');
    }

    if (audio) {
        if (!audio.src || audio.src === window.location.href) {
            audio.src = STATIONS[currentStationIndex].src;
        }

        audio.play().then(() => {
            isPlaying = true;
            updatePlayStateUI(true);
            saveState();
        }).catch(err => {
            console.warn('Audio playback waiting for user click:', err);
            const handleUserInteraction = () => {
                audio.play().then(() => {
                    isPlaying = true;
                    updatePlayStateUI(true);
                    saveState();
                }).catch(e => console.warn(e));
                document.removeEventListener('click', handleUserInteraction);
            };
            document.addEventListener('click', handleUserInteraction, { once: true });
        });
    }
}

function pauseAudio() {
    const audio = document.getElementById('pergel-radio-audio');
    if (audio) audio.pause();
    isPlaying = false;
    updatePlayStateUI(false);
    saveState();
}

function prevStation() {
    let newIdx = currentStationIndex - 1;
    if (newIdx < 0) newIdx = STATIONS.length - 1;
    selectStation(newIdx, isPlaying);
}

function nextStation() {
    let newIdx = currentStationIndex + 1;
    if (newIdx >= STATIONS.length) newIdx = 0;
    selectStation(newIdx, isPlaying);
}

function toggleExpand() {
    isExpanded = !isExpanded;
    const minView = document.getElementById('radio-minimized-view');
    const expView = document.getElementById('radio-expanded-view');

    if (isExpanded) {
        minView?.classList.add('d-none');
        expView?.classList.remove('d-none');
    } else {
        expView?.classList.add('d-none');
        minView?.classList.remove('d-none');
    }
    saveState();
}

function toggleWidgetVisibility() {
    const widget = document.getElementById('pergel-radio-widget');
    if (isClosed) {
        isClosed = false;
        widget?.classList.remove('d-none');
        if (!isPlaying) {
            playAudio();
        }
    } else {
        if (isExpanded) {
            toggleExpand();
        } else {
            closeWidget();
        }
    }
}

function closeWidget(e) {
    if (e) e.stopPropagation();
    isClosed = true;
    const widget = document.getElementById('pergel-radio-widget');
    if (widget) {
        widget.classList.add('d-none');
    }
    pauseAudio();
    saveState();
}

function updatePlayStateUI(playing) {
    isPlaying = playing;
    const miniPlayIcon = document.getElementById('mini-play-icon');
    const expPlayIcon = document.getElementById('expanded-play-icon');
    const eq = document.getElementById('radio-equalizer');
    const discSmall = document.querySelector('.radio-disc-small');
    const vinylDisc = document.getElementById('radio-vinyl-disc');
    const topbarEq = document.getElementById('topbar-radio-eq-indicator');

    if (playing) {
        if (miniPlayIcon) miniPlayIcon.className = 'ri-pause-fill fs-5 text-warning';
        if (expPlayIcon) expPlayIcon.className = 'ri-pause-fill fs-3 text-dark ms-0';
        eq?.classList.add('playing');
        discSmall?.classList.add('spinning');
        vinylDisc?.classList.add('spinning');
        topbarEq?.classList.add('playing');
    } else {
        if (miniPlayIcon) miniPlayIcon.className = 'ri-play-fill fs-5 text-warning';
        if (expPlayIcon) expPlayIcon.className = 'ri-play-fill fs-3 text-dark ms-1';
        eq?.classList.remove('playing');
        discSmall?.classList.remove('spinning');
        vinylDisc?.classList.remove('spinning');
        topbarEq?.classList.remove('playing');
    }
}

function saveState() {
    const audio = document.getElementById('pergel-radio-audio');
    const state = {
        isPlaying: isPlaying,
        stationIndex: currentStationIndex,
        currentTime: audio ? audio.currentTime : 0,
        volume: audio ? audio.volume : 0.7,
        isExpanded: isExpanded,
        isClosed: isClosed
    };
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    } catch (e) {}
}

function loadSavedState() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        const minView = document.getElementById('radio-minimized-view');
        const expView = document.getElementById('radio-expanded-view');
        const widget = document.getElementById('pergel-radio-widget');
        const audio = document.getElementById('pergel-radio-audio');

        if (raw) {
            const state = JSON.parse(raw);
            if (state.stationIndex !== undefined && state.stationIndex >= 0 && state.stationIndex < STATIONS.length) {
                currentStationIndex = state.stationIndex;
            }
            if (state.volume !== undefined && audio) {
                audio.volume = state.volume;
                const volSlider = document.getElementById('radio-volume-slider');
                if (volSlider) volSlider.value = state.volume;
            }
            isExpanded = state.isExpanded !== undefined ? state.isExpanded : true;
            isPlaying = state.isPlaying || false;
        } else {
            isExpanded = true;
            isPlaying = false;
        }

        selectStation(currentStationIndex, false);

        if (isExpanded) {
            minView?.classList.add('d-none');
            expView?.classList.remove('d-none');
        } else {
            expView?.classList.add('d-none');
            minView?.classList.remove('d-none');
        }

        // Widget is hidden by default on page load; only opens when user clicks topbar radio button
        widget?.classList.add('d-none');
        isClosed = true;

        if (isPlaying) {
            playAudio();
            widget?.classList.add('d-none');
        }
    } catch (e) {
        console.warn('Failed to load radio state', e);
    }
}
</script>
