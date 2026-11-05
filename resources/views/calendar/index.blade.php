@extends('layouts.master')

@section('title') Takvim @endsection

@section('css')
    <style>
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .calendar-header {
            background-color: #495057;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        .calendar-day {
            background-color: white;
            min-height: 120px;
            padding: 8px;
            position: relative;
            transition: background-color 0.2s ease;
        }
        
        .calendar-day:hover {
            background-color: #f8f9fa;
        }
        
        .calendar-day.other-month {
            background-color: #f8f9fa;
            color: #adb5bd;
        }
        
        .calendar-day.today {
            background-color: rgba(0, 123, 255, 0.1);
            border: 2px solid #007bff;
        }
        
        .day-number {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 14px;
        }
        
        .day-events {
            font-size: 11px;
        }
        
        .event-item {
            background-color: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .event-item:hover {
            background-color: #0056b3;
        }
        
        .event-item.task {
            background-color: #007bff;
        }
        
        .event-item.milestone {
            background-color: #ffc107;
            color: #212529;
        }
        
        .event-item.urgent {
            background-color: #dc3545;
        }
        
        .event-item.high {
            background-color: #fd7e14;
        }
        
        .event-item.medium {
            background-color: #17a2b8;
        }
        
        .event-item.low {
            background-color: #28a745;
        }
        
        .calendar-stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        
        .calendar-stats-card:hover {
            transform: translateY(-3px);
        }
        
        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }
        
        .calendar-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #495057;
        }
        
        .nav-button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 2px;
            transition: background-color 0.2s ease;
        }
        
        .nav-button:hover {
            background-color: #0056b3;
        }
        
        .nav-button.today-btn {
            background-color: #28a745;
        }
        
        .nav-button.today-btn:hover {
            background-color: #1e7e34;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Yönetim @endslot
        @slot('title') Takvim @endslot
    @endcomponent

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card calendar-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['this_month_deadlines'] }}</h4>
                            <p class="mb-0">Bu Ay</p>
                            <small class="opacity-75">Deadline</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card calendar-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['overdue_tasks'] }}</h4>
                            <p class="mb-0">Geciken</p>
                            <small class="opacity-75">Görevler</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card calendar-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['upcoming_milestones'] }}</h4>
                            <p class="mb-0">Yaklaşan</p>
                            <small class="opacity-75">Milestone</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-flag fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card calendar-stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['completed_this_month'] }}</h4>
                            <p class="mb-0">Bu Ay Tamamlanan</p>
                            <small class="opacity-75">Görevler</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Takvim Kartı -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Proje Takvimi
                </h5>
                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-color bg-danger"></div>
                        <span>Acil</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color bg-warning"></div>
                        <span>Milestone</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color bg-info"></div>
                        <span>Orta</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color bg-success"></div>
                        <span>Düşük</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color bg-primary"></div>
                        <span>Normal</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Calendar Navigation -->
            <div class="calendar-navigation">
                <div>
                    <button class="nav-button" onclick="previousMonth()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="nav-button today-btn" onclick="goToToday()">Bugün</button>
                    <button class="nav-button" onclick="nextMonth()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="calendar-title" id="calendarTitle">
                    {{ now()->locale('tr')->monthName }} {{ now()->year }}
                </div>
                <div>
                    <button class="nav-button" onclick="toggleView()">Hafta</button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="calendar-grid" id="calendarGrid">
                <!-- Header Row -->
                <div class="calendar-header">Pzt</div>
                <div class="calendar-header">Sal</div>
                <div class="calendar-header">Çar</div>
                <div class="calendar-header">Per</div>
                <div class="calendar-header">Cum</div>
                <div class="calendar-header">Cmt</div>
                <div class="calendar-header">Paz</div>
                
                <!-- Calendar days will be generated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Etkinlik Detayları</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Event details will be populated here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <a href="#" id="eventViewLink" class="btn btn-primary">Detayları Görüntüle</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let currentDate = new Date();
        let events = @json($events);
        let eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
        
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
        });
        
        function renderCalendar() {
            const grid = document.getElementById('calendarGrid');
            const title = document.getElementById('calendarTitle');
            
            // Update title
            const monthNames = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
                              'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
            title.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            
            // Clear existing days (keep headers)
            const existingDays = grid.querySelectorAll('.calendar-day');
            existingDays.forEach(day => day.remove());
            
            // Get month info
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - (firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1));
            
            const today = new Date();
            
            // Generate calendar days
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                
                // Add classes
                if (date.getMonth() !== currentDate.getMonth()) {
                    dayElement.classList.add('other-month');
                }
                
                if (date.toDateString() === today.toDateString()) {
                    dayElement.classList.add('today');
                }
                
                // Day number
                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = date.getDate();
                
                // Events for this day
                const dayEvents = document.createElement('div');
                dayEvents.className = 'day-events';
                
                const dateStr = date.toISOString().split('T')[0];
                const dayEventList = events.filter(event => event.start === dateStr);
                
                dayEventList.forEach((event, index) => {
                    if (index < 3) { // Limit to 3 events per day
                        const eventItem = document.createElement('div');
                        eventItem.className = 'event-item';
                        
                        // Add type and priority classes
                        if (event.type === 'milestone') {
                            eventItem.classList.add('milestone');
                        } else {
                            eventItem.classList.add('task');
                            if (event.priority) {
                                eventItem.classList.add(event.priority);
                            }
                        }
                        
                        eventItem.textContent = event.title;
                        eventItem.title = event.title;
                        eventItem.addEventListener('click', () => showEventDetails(event));
                        
                        dayEvents.appendChild(eventItem);
                    }
                });
                
                // Show more indicator
                if (dayEventList.length > 3) {
                    const moreItem = document.createElement('div');
                    moreItem.className = 'event-item';
                    moreItem.style.backgroundColor = '#6c757d';
                    moreItem.textContent = `+${dayEventList.length - 3} daha`;
                    moreItem.title = `${dayEventList.length - 3} etkinlik daha var`;
                    dayEvents.appendChild(moreItem);
                }
                
                dayElement.appendChild(dayNumber);
                dayElement.appendChild(dayEvents);
                grid.appendChild(dayElement);
            }
        }
        
        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }
        
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }
        
        function goToToday() {
            currentDate = new Date();
            renderCalendar();
        }
        
        function toggleView() {
            // Placeholder for week view toggle
            alert('Hafta görünümü geliştirme aşamasında');
        }
        
        function showEventDetails(event) {
            const modalTitle = document.getElementById('eventModalLabel');
            const modalBody = document.getElementById('eventModalBody');
            const viewLink = document.getElementById('eventViewLink');
            
            modalTitle.textContent = event.title;
            
            let detailsHtml = '<div class="event-details">';
            
            // Event type
            const eventType = event.type === 'task' ? 'Görev' : 'Milestone';
            detailsHtml += `<p><strong>Tür:</strong> <span class="badge bg-primary">${eventType}</span></p>`;
            
            // Date
            const eventDate = new Date(event.start + 'T00:00:00');
            detailsHtml += `<p><strong>Tarih:</strong> ${eventDate.toLocaleDateString('tr-TR')}</p>`;
            
            // Description
            if (event.description) {
                detailsHtml += `<p><strong>Açıklama:</strong> ${event.description}</p>`;
            }
            
            // Project (for tasks)
            if (event.project) {
                detailsHtml += `<p><strong>Proje:</strong> ${event.project}</p>`;
            }
            
            // Assignee (for tasks)
            if (event.assignee) {
                detailsHtml += `<p><strong>Atanan:</strong> ${event.assignee}</p>`;
            }
            
            // Status
            if (event.status) {
                const statusBadge = getStatusBadge(event.status);
                detailsHtml += `<p><strong>Durum:</strong> ${statusBadge}</p>`;
            }
            
            // Priority (for tasks)
            if (event.priority) {
                const priorityBadge = getPriorityBadge(event.priority);
                detailsHtml += `<p><strong>Öncelik:</strong> ${priorityBadge}</p>`;
            }
            
            // Budget (for projects)
            if (event.budget) {
                detailsHtml += `<p><strong>Bütçe:</strong> ${Number(event.budget).toLocaleString('tr-TR')} ₺</p>`;
            }
            
            detailsHtml += '</div>';
            
            modalBody.innerHTML = detailsHtml;
            
            // Set view link
            if (event.url) {
                viewLink.href = event.url;
                viewLink.style.display = 'inline-block';
            } else {
                viewLink.style.display = 'none';
            }
            
            eventModal.show();
        }
        
        function getStatusBadge(status) {
            const statusMap = {
                'pending': '<span class="badge bg-warning">Beklemede</span>',
                'in_progress': '<span class="badge bg-info">Devam Ediyor</span>',
                'completed': '<span class="badge bg-success">Tamamlandı</span>',
                'active': '<span class="badge bg-primary">Aktif</span>',
                'paused': '<span class="badge bg-secondary">Duraklatıldı</span>'
            };
            return statusMap[status] || `<span class="badge bg-light text-dark">${status}</span>`;
        }
        
        function getPriorityBadge(priority) {
            const priorityMap = {
                'urgent': '<span class="badge bg-danger">Acil</span>',
                'high': '<span class="badge bg-warning">Yüksek</span>',
                'medium': '<span class="badge bg-info">Orta</span>',
                'low': '<span class="badge bg-success">Düşük</span>'
            };
            return priorityMap[priority] || `<span class="badge bg-primary">${priority}</span>`;
        }
    </script>
@endsection