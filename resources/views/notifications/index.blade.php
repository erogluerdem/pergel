@extends('layouts.master')

@section('title')
    Bildirimler
@endsection

@section('topbar-title')
    Sistem
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-bell-outline me-2"></i>
                            Bildirimler
                        </h4>
                    </div>
                    <div class="col-auto">
                        @if($stats['unread'] > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-check-all me-1"></i>
                                    Tümünü Okundu İşaretle
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-bell-outline fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                        <p class="mb-0">Toplam</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-bell-ring fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0">{{ $stats['unread'] }}</h4>
                                        <p class="mb-0">Okunmamış</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-check-circle fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0">{{ $stats['total'] - $stats['unread'] }}</h4>
                                        <p class="mb-0">Okunmuş</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="mdi mdi-alert-circle fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0">{{ $stats['urgent'] ?? 0 }}</h4>
                                        <p class="mb-0">Acil</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form method="GET" action="{{ route('notifications.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">Tüm Durumlar</option>
                                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Okunmamış</option>
                                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Okunmuş</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="type" class="form-select">
                                        <option value="">Tüm Türler</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="priority" class="form-select">
                                        <option value="">Tüm Öncelikler</option>
                                        @foreach($priorities as $priority)
                                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                                {{ ucfirst($priority) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-filter me-1"></i>
                                            Filtrele
                                        </button>
                                        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-refresh me-1"></i>
                                            Temizle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifications List -->
                @if($notifications->count() > 0)
                    <div class="notification-list">
                        @foreach($notifications as $notification)
                            <div class="notification-item border-bottom py-3 {{ !$notification->is_read ? 'bg-light' : '' }}">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-{{ $notification->priority == 'urgent' ? 'danger' : ($notification->priority == 'high' ? 'warning' : 'primary') }} rounded-circle">
                                                @switch($notification->type)
                                                    @case('task_assigned')
                                                        <i class="mdi mdi-clipboard-check-outline"></i>
                                                        @break
                                                    @case('low_stock')
                                                        <i class="mdi mdi-alert-outline"></i>
                                                        @break
                                                    @case('system_update')
                                                        <i class="mdi mdi-update"></i>
                                                        @break
                                                    @case('task_completed')
                                                        <i class="mdi mdi-check-circle-outline"></i>
                                                        @break
                                                    @case('deadline_approaching')
                                                        <i class="mdi mdi-clock-alert-outline"></i>
                                                        @break
                                                    @case('user_mention')
                                                        <i class="mdi mdi-at"></i>
                                                        @break
                                                    @default
                                                        <i class="mdi mdi-bell-outline"></i>
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                                    {{ $notification->title }}
                                                    @if(!$notification->is_read)
                                                        <span class="badge bg-primary ms-2">Yeni</span>
                                                    @endif
                                                </h6>
                                                <p class="text-muted mb-1">{{ $notification->message }}</p>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-clock-outline me-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(!$notification->is_read)
                                                        <li>
                                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="mdi mdi-check me-2"></i>
                                                                    Okundu İşaretle
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($notification->action_url)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ $notification->action_url }}">
                                                                <i class="mdi mdi-open-in-new me-2"></i>
                                                                İlgili Sayfaya Git
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="text-muted mb-0">
                                {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} 
                                / {{ $notifications->total() }} sonuç gösteriliyor
                            </p>
                        </div>
                        <div>
                            {{ $notifications->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-light rounded-circle">
                                <i class="mdi mdi-bell-outline fa-3x text-muted"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">Bildirim bulunamadı</h5>
                        <p class="text-muted">Henüz hiç bildirim yok veya filtre kriterlerinize uygun bildirim bulunamadı.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh notification count every 30 seconds
    setInterval(function() {
        fetch('/api/notifications/count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const countBadge = document.querySelector('.badge.bg-info-subtle');
                    if (countBadge) {
                        const currentCount = parseInt(countBadge.textContent) || 0;
                        const newCount = data.count;
                        
                        if (newCount !== currentCount) {
                            countBadge.textContent = newCount + (newCount > 9 ? '+' : '');
                            
                            // Show toast if new notifications
                            if (newCount > currentCount) {
                                showNotificationToast(`${newCount - currentCount} yeni bildirim`, 'info');
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error checking notifications:', error);
            });
    }, 30000);
});

function showNotificationToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="mdi mdi-${type === 'success' ? 'check-circle' : 'information'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 150);
        }
    }, 3000);
}
</script>
@endsection