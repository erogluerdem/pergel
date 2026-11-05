<div class="custom-setting bg-primary pe-0 d-flex flex-column rounded-start">
    <button type="button" class="btn btn-wide border-0 text-white fs-20 avatar-sm rounded-end-0" id="theme-switcher-btn" title="Tema Seçici (5 Sektör Teması)">
        <i class="mdi mdi-palette align-middle"></i>
    </button>
    <button type="button" class="btn btn-wide border-0 text-white fs-20 avatar-sm rounded-end-0" id="light-dark-mode" title="Karanlık / Aydın">
        <i class="mdi mdi-brightness-7 align-middle"></i>
        <i class="mdi mdi-white-balance-sunny align-middle"></i>
    </button>
    <button type="button" id="fullscreen-btn" class="btn btn-wide border-0 text-white fs-20 avatar-sm" data-toggle="fullscreen" title="Tam ekran">
        <i class="mdi mdi-arrow-expand-all align-middle"></i>
    </button>
    <button type="button" class="btn btn-wide border-0 text-white fs-16 avatar-sm" id="page-lock-btn" onclick="if(window.PergelLock){ window.PergelLock.lockSession(); } else { window.location.href='{{ route('lock-screen.show') }}'; }" title="Sayfayı Kilitle">
        <i class="mdi mdi-lock align-middle"></i>
    </button>
</div>

<div class="offcanvas offcanvas-end" id="offcanvas-rightsidabar" style="width: 400px;">
    <div class="card h-100 rounded-0">
        <div class="card-header bg-primary text-white">
            <h6 class="card-title text-uppercase mb-0">
                <i class="fas fa-chart-line me-2"></i>Aktivite Merkezi
            </h6>
            <div class="card-addon">
                <button class="btn btn-outline-light btn-sm" data-bs-dismiss="offcanvas" title="Kapat">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="max-height: calc(100vh - 120px); overflow-y: auto; padding-bottom: 20px;">
            <!-- Proje Özeti -->
            <div class="">
                <h3 class="card-title">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Proje Özeti
                </h3>
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-primary fs-18 mb-1">
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <h5 class="mb-1">{{ $activeProjectsCount ?? 12 }}</h5>
                                <small class="text-muted">Aktif Proje</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-success fs-18 mb-1">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h5 class="mb-1">{{ $pendingTasksCount ?? 47 }}</h5>
                                <small class="text-muted">Bekleyen Görev</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-warning fs-18 mb-1">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h5 class="mb-1">{{ $overdueTasksCount ?? 8 }}</h5>
                                <small class="text-muted">Geciken Görev</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="text-info fs-18 mb-1">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="mb-1">{{ $activeUsersCount ?? 23 }}</h5>
                                <small class="text-muted">Aktif Kullanıcı</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hızlı Aksiyonlar -->
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('projects.create') }}" class="btn btn-sm btn-outline-primary flex-1">
                        <i class="fas fa-plus me-1"></i>Proje
                    </a>
                    {{-- <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-outline-success flex-1"> --}}
                        <i class="fas fa-plus me-1"></i>Görev
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="card-title">
                    <i class="fas fa-history me-2 text-info"></i>Son Aktiviteler
                </h3>
                <div class="timeline" id="recent-activities">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-pin">
                                <i class="marker marker-dot
                                    @switch($activity['type'] ?? 'info')
                                        @case('task_completed') text-success @break
                                        @case('task_assigned') text-primary @break
                                        @case('project_created') text-info @break
                                        @case('deadline_approaching') text-warning @break
                                        @case('overdue') text-danger @break
                                        @default text-secondary
                                    @endswitch
                                "></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="mb-0">{{ $activity['title'] ?? 'Yeni aktivite' }}</p>
                                        @if(isset($activity['project_name']))
                                            <small class="text-muted">{{ $activity['project_name'] }}</small>
                                        @endif
                                    </div>
                                    <span class="text-muted">{{ $activity['time'] ?? 'Şimdi' }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-success"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Yeni görev tamamlandı <span class="badge badge-label-success">Web Sitesi</span></p>
                                    <span class="text-muted">5 dk</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-primary"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Proje ekibine yeni üye eklendi</p>
                                    <span class="text-muted">15 dk</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-warning"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Deadline yaklaşıyor <span class="badge badge-label-warning">Mobil App</span></p>
                                    <span class="text-muted">30 dk</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-info"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Yeni proje oluşturuldu: <a href="#">E-ticaret Platformu</a></p>
                                    <span class="text-muted">1 saat</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-danger"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Geciken görev <span class="badge badge-label-danger">acil</span></p>
                                    <span class="text-muted">2 saat</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-pin"><i class="marker marker-dot text-success"></i></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Milestone tamamlandı</p>
                                    <span class="text-muted">3 saat</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Daha fazla aktivite butonu -->
                <div class="text-center mt-3">
                    {{-- <a href="{{ route('activities.index') ?? '#' }}" class="btn btn-sm btn-outline-primary"> --}}
                        <i class="fas fa-list me-1"></i>Tüm Aktiviteler
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt me-2 text-warning"></i>Yaklaşan Etkinlikler
                </h3>
                <div class="timeline timeline-timed" id="upcoming-activities">
                    @if(isset($upcomingActivities) && count($upcomingActivities) > 0)
                        @foreach($upcomingActivities as $activity)
                        <div class="timeline-item">
                            <span class="timeline-time">{{ $activity['time'] ?? '10:00' }}</span>
                            <div class="timeline-pin">
                                <i class="marker marker-circle
                                    @switch($activity['priority'] ?? 'normal')
                                        @case('high') text-danger @break
                                        @case('medium') text-warning @break
                                        @case('low') text-success @break
                                        @default text-primary
                                    @endswitch
                                "></i>
                            </div>
                            <div class="timeline-content">
                                <div>
                                    <strong>{{ $activity['title'] ?? 'Etkinlik' }}</strong>
                                    @if(isset($activity['participants']) && count($activity['participants']) > 0)
                                        <div class="avatar-group ms-2 mt-1">
                                            @foreach($activity['participants'] as $participant)
                                                <div class="avatar avatar-circle" title="{{ $participant['name'] ?? 'User' }}">
                                                    <img src="{{ $participant['avatar'] ?? URL::asset('build/images/users/avatar-1.png') }}"
                                                        alt="Avatar" class="avatar-2xs" />
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if(isset($activity['description']))
                                        <p class="mb-0 mt-1 text-muted small">{{ $activity['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="timeline-item">
                            <span class="timeline-time">10:00</span>
                            <div class="timeline-pin"><i class="marker marker-circle text-primary"></i></div>
                            <div class="timeline-content">
                                <div>
                                    <strong>Ekip Toplantısı</strong>
                                    <div class="avatar-group ms-2 mt-1">
                                        <div class="avatar avatar-circle">
                                            <img src="{{ URL::asset('build/images/users/avatar-1.png') }}"
                                                alt="Avatar image" class="avatar-2xs" />
                                        </div>
                                        <div class="avatar avatar-circle">
                                            <img src="{{ URL::asset('build/images/users/avatar-2.png') }}"
                                                alt="Avatar image" class="avatar-2xs" />
                                        </div>
                                        <div class="avatar avatar-circle">
                                            <img src="{{ URL::asset('build/images/users/avatar-3.png') }}"
                                                alt="Avatar image" class="avatar-2xs" />
                                        </div>
                                    </div>
                                    <p class="mb-0 mt-1 text-muted small">Haftalık ilerleme toplantısı</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-time">13:00</span>
                            <div class="timeline-pin"><i class="marker marker-circle text-warning"></i></div>
                            <div class="timeline-content">
                                <div>
                                    <strong>Proje Sunumu</strong>
                                    <p class="mb-0 mt-1 text-muted small">Müşteri sunum toplantısı</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-time">15:30</span>
                            <div class="timeline-pin"><i class="marker marker-circle text-danger"></i></div>
                            <div class="timeline-content">
                                <div>
                                    <strong>Deadline</strong>
                                    <p class="mb-0 mt-1 text-muted small">Web sitesi geliştirme teslimatı</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <span class="timeline-time">16:45</span>
                            <div class="timeline-pin"><i class="marker marker-circle text-success"></i></div>
                            <div class="timeline-content">
                                <div>
                                    <strong>İnceleme Toplantısı</strong>
                                    <p class="mb-0 mt-1 text-muted small">Sprint retrospektifi</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Takvim butonu -->
                <div class="text-center mt-3">
                    {{-- <a href="{{ route('calendar.index') ?? '#' }}" class="btn btn-sm btn-outline-warning"> --}}
                        <i class="fas fa-calendar me-1"></i>Tam Takvim
                    </a>
                </div>
            </div>
        </div>
    </div> <!-- end card-->
</div>

<!-- Activities Scroll Fix CSS -->
<style>
/* Activities panel scroll optimization */
.offcanvas-body {
    padding: 0 !important;
}

.offcanvas .card {
    border: none !important;
    border-radius: 0 !important;
    height: 100% !important;
}

.offcanvas .card-body {
    max-height: calc(100vh - 80px) !important;
    overflow-y: auto !important;
    padding: 15px !important;
}

/* Custom scrollbar for activities */
.offcanvas .card-body::-webkit-scrollbar {
    width: 6px;
}

.offcanvas .card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.offcanvas .card-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.offcanvas .card-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Smooth scrolling behavior */
.offcanvas .card-body {
    scroll-behavior: smooth;
}

/* Fix timeline spacing in activities */
.timeline-item {
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
}

.timeline-item:not(:last-child) {
    border-bottom: 1px solid #f0f0f0;
}

/* Hover effects for timeline items */
.timeline-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
    border-radius: 8px;
    padding: 8px;
    margin: -8px;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

/* Better spacing for activity sections */
.mt-4 {
    margin-top: 2rem !important;
}

/* Responsive scroll height */
@media (max-height: 700px) {
    .offcanvas .card-body {
        max-height: calc(100vh - 60px) !important;
    }
}

@media (max-height: 500px) {
    .offcanvas .card-body {
        max-height: calc(100vh - 40px) !important;
    }
}
</style>

<!-- Activities JavaScript -->
<script>
// Guard: prevent accidental auto-opening of the activities offcanvas during initial load.
// Explanation: some scripts or race conditions may call show() or add the 'show' class
// before the page is fully interactive, causing the offcanvas to flash open then disappear.
// This guard ignores those programmatic attempts for the first 750ms after DOMContentLoaded,
// while still allowing explicit user interactions (pointer/click) to open it.
(function(){
    var OFFCANVAS_ID = 'offcanvas-rightsidabar';
    var gracePeriod = 750; // ms
    var blockUntil = Date.now() + gracePeriod;

    function shouldBlock() {
        return Date.now() < blockUntil;
    }

    // Intercept attempts to add 'show' class to the offcanvas element
    document.addEventListener('DOMContentLoaded', function(){
        var el = document.getElementById(OFFCANVAS_ID);
        if(!el) return;

        // MutationObserver to detect class changes
        var mo = new MutationObserver(function(mutations){
            mutations.forEach(function(m){
                if(m.attributeName === 'class'){
                    if(shouldBlock() && el.classList.contains('show')){
                        // remove unintended show class
                        el.classList.remove('show');
                        // also remove backdrop if any
                        var backdrop = document.querySelector('.offcanvas-backdrop');
                        if(backdrop) backdrop.parentNode.removeChild(backdrop);
                    }
                }
            });
        });

        mo.observe(el, { attributes: true, attributeFilter: ['class'] });

        // Wrap bootstrap Offcanvas show to block programmatic calls briefly
        try {
            if(window.bootstrap && window.bootstrap.Offcanvas){
                var Orig = window.bootstrap.Offcanvas.prototype.show;
                window.bootstrap.Offcanvas.prototype.show = function(){
                    try{
                        if(shouldBlock() && this._element && this._element.id === OFFCANVAS_ID){
                            // skip this premature show
                            return;
                        }
                    }catch(e){}
                    return Orig.apply(this, arguments);
                };
            }
        }catch(e){ /* ignore */ }
    });
})();

document.addEventListener('DOMContentLoaded', function() {
    // Activities panel açıldığında verileri güncelle

    const activitiesOffcanvas = document.getElementById('offcanvas-rightsidabar');
    if (activitiesOffcanvas) {
        activitiesOffcanvas.addEventListener('shown.bs.offcanvas', function () {
            refreshActivities();

            // Ensure proper scrolling setup
            const cardBody = this.querySelector('.card-body');
            if (cardBody) {
                // Reset scroll position
                cardBody.scrollTop = 0;

                // Add scroll event listener for debugging
                cardBody.addEventListener('scroll', function() {
                    console.log('Activities scroll:', this.scrollTop, 'of', this.scrollHeight - this.clientHeight);
                });

                // Force scroll area calculation
                setTimeout(() => {
                    const hasOverflow = cardBody.scrollHeight > cardBody.clientHeight;
                    console.log('Activities panel overflow:', hasOverflow, 'Height:', cardBody.scrollHeight, 'Client:', cardBody.clientHeight);
                }, 100);
            }
        });
    }

    // Aktiviteleri otomatik yenile (her 30 saniyede bir)
    setInterval(refreshActivities, 30000);

    function refreshActivities() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        fetch('/api/activities/recent', {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
            }
        })
            .then(response => {
                const contentType = response.headers.get('content-type') || '';

                // 401 Unauthorized - kullanıcı giriş yapmamış
                if (response.status === 401) {
                    console.info('Aktiviteler için giriş gerekli');
                    return null;
                }

                if (!response.ok) {
                    throw new Error(`Server returned ${response.status}`);
                }
                if (!contentType.includes('application/json')) {
                    throw new Error('Beklenmeyen içerik tipi: ' + contentType);
                }
                return response.json();
            })
            .then(data => {
                if (!data) return; // 401 durumunda null döner

                if (data && data.success) {
                    updateRecentActivities(data.activities || []);
                    updateProjectSummary(data.summary || {});
                } else {
                    console.warn('Aktiviteler API başarı dönmedi', data);
                }
            })
            .catch(error => {
                // Sessizce logla - kullanıcıyı rahatsız etme
                if (error.message.includes('401')) {
                    console.info('Aktiviteler için oturum açma gerekli');
                } else {
                    console.warn('Aktiviteler yüklenirken hata:', error);
                }
            });
    }

    function updateRecentActivities(activities) {
        const container = document.getElementById('recent-activities');
        if (!container || !activities || activities.length === 0) return;

        let html = '';
        activities.forEach(activity => {
            const colorClass = getActivityColorClass(activity.type);
            html += `
                <div class="timeline-item">
                    <div class="timeline-pin">
                        <i class="marker marker-dot ${colorClass}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0">${activity.title}</p>
                                ${activity.project_name ? `<small class="text-muted">${activity.project_name}</small>` : ''}
                            </div>
                            <span class="text-muted">${activity.time_ago}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function updateProjectSummary(summary) {
        if (!summary) return;

        // Güvenli element güncellemesi
        const updates = [
            { selector: '.active-projects-count', value: summary.active_projects || 0 },
            { selector: '.pending-tasks-count', value: summary.pending_tasks || 0 },
            { selector: '.overdue-tasks-count', value: summary.overdue_tasks || 0 },
            { selector: '.active-users-count', value: summary.active_users || 0 }
        ];

        updates.forEach(update => {
            const element = document.querySelector(update.selector);
            if (element) {
                element.textContent = update.value;
            }
        });
    }

    function updateUpcomingActivities(activities) {
        const container = document.getElementById('upcoming-activities');
        if (!container || !activities || activities.length === 0) return;

        let html = '';
        activities.forEach(activity => {
            const colorClass = getPriorityColorClass(activity.priority);
            html += `
                <div class="timeline-item">
                    <span class="timeline-time">${activity.time}</span>
                    <div class="timeline-pin">
                        <i class="marker marker-circle ${colorClass}"></i>
                    </div>
                    <div class="timeline-content">
                        <div>
                            <strong>${activity.title}</strong>
                            ${activity.description ? `<p class="mb-0 mt-1 text-muted small">${activity.description}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function getActivityColorClass(type) {
        const colorMap = {
            'task_completed': 'text-success',
            'task_assigned': 'text-primary',
            'project_created': 'text-info',
            'deadline_approaching': 'text-warning',
            'overdue': 'text-danger'
        };
        return colorMap[type] || 'text-secondary';
    }

    function getPriorityColorClass(priority) {
        const colorMap = {
            'high': 'text-danger',
            'medium': 'text-warning',
            'low': 'text-success'
        };
        return colorMap[priority] || 'text-primary';
    }

    // Activity indicator animasyonu
    function animateActivityIndicator() {
        const indicator = document.querySelector('.activities .position-absolute');
        if (indicator) {
            indicator.style.animation = 'pulse 2s infinite';
        }
    }

    // CSS animasyonu ekle
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .activities .btn:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            transform: translateY(-1px);
            transition: all 0.2s ease !important;
        }

        /* Debug: Show scroll status */
        .scroll-debug {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 9999;
            display: none;
        }

        .timeline-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 6px;
            padding: 8px;
            margin: -8px;
            transition: all 0.2s ease;
        }

        .activities-summary-card {
            transition: transform 0.2s ease;
        }

        .activities-summary-card:hover {
            transform: translateY(-2px);
        }
    `;
    document.head.appendChild(style);

    // İlk yüklemede aktiviteleri getir
    refreshActivities();

    // Scroll to top when activities panel opens
    if (activitiesOffcanvas) {
        activitiesOffcanvas.addEventListener('shown.bs.offcanvas', function () {
            const cardBody = this.querySelector('.card-body');
            if (cardBody) {
                cardBody.scrollTop = 0;
            }
        });
    }

    // Add smooth scroll to activity items
    function smoothScrollToElement(element) {
        if (element) {
            const cardBody = document.querySelector('.offcanvas .card-body');
            if (cardBody) {
                const elementTop = element.offsetTop - cardBody.offsetTop;
                cardBody.scrollTo({
                    top: elementTop - 50,
                    behavior: 'smooth'
                });
            }
        }
    }

    // Add click handlers for activity items with smooth scroll
    document.addEventListener('click', function(e) {
        if (e.target.closest('.timeline-item')) {
            const item = e.target.closest('.timeline-item');
            item.style.backgroundColor = 'rgba(0, 123, 255, 0.1)';
            setTimeout(() => {
                item.style.backgroundColor = '';
            }, 1000);
        }
    });
});
</script>
