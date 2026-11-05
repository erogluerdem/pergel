// Notification System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initNotificationSystem();
});

// safeJsonFetch: centralized fetch wrapper that ensures Accept: application/json,
// sends same-origin credentials, checks content-type and parses JSON or throws
if (!window.safeJsonFetch) {
    window.safeJsonFetch = function(url, opts = {}) {
        opts = Object.assign({}, opts);
        opts.headers = Object.assign({}, opts.headers || {});
        // ensure Accept header
        if (!opts.headers['Accept'] && !opts.headers['accept']) opts.headers['Accept'] = 'application/json';
        // include credentials so session cookie is sent for same-origin
        if (!opts.credentials) opts.credentials = 'same-origin';
        return fetch(url, opts).then(response => {
            const ct = response.headers.get('content-type') || '';
            if (!ct.includes('application/json')) {
                // read text and throw a descriptive error to be handled by callers
                return response.text().then(text => {
                    const err = new Error('Unexpected content-type ' + ct);
                    err.responseText = text;
                    err.status = response.status;
                    throw err;
                });
            }
            return response.json();
        });
    };
}

function initNotificationSystem() {
    // Mark single notification as read when clicked
    const notificationItems = document.querySelectorAll('.notification-item[data-notification-id]');

    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const notificationId = this.getAttribute('data-notification-id');

            // Mark as read
            (window.safeJsonFetch ? window.safeJsonFetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }) : window.safeJsonFetch(`/api/notifications/${notificationId}/read`, { method: 'POST' }))
            .then(data => {
                if (data && data.success) {
                    // Update notification count
                    updateNotificationCount(-1);
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
                // if server returned HTML page (error stack), show toast with short snippet
                const msg = error.responseText ? error.responseText.substring(0, 300) : error.message;
                showNotificationToast('Sunucu hatası: ' + msg, 'error');
            });
        });
    });
}

// Mark all notifications as read
function markAllAsRead() {
    (window.safeJsonFetch ? window.safeJsonFetch('/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }) : window.safeJsonFetch('/api/notifications/mark-all-read', { method: 'POST' }))
    .then(data => {
        if (data && data.success) {
            // Update notification count to 0
            updateNotificationBadges(0);

            // Show success message
            showNotificationToast('Tüm bildirimler okundu olarak işaretlendi', 'success');

            // Reload page after short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const msg = error.responseText ? error.responseText.substring(0,300) : error.message;
        showNotificationToast('Bir hata oluştu: ' + msg, 'error');
    });
}

// Update notification count
function updateNotificationCount(change) {
    // Update dropdown header badge
    const countBadge = document.querySelector('.notification-count-badge');
    if (countBadge) {
        const currentCount = parseInt(countBadge.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);
        countBadge.textContent = newCount + (newCount > 9 ? '+' : '');
    }

    // Update bell icon badge
    const bellBadge = document.querySelector('.notification-bell-badge');
    if (bellBadge) {
        const currentCount = parseInt(bellBadge.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);

        if (newCount === 0) {
            bellBadge.style.display = 'none';
        } else {
            bellBadge.style.display = 'inline';
            bellBadge.textContent = newCount > 99 ? '99+' : newCount;
        }
    }
}

// Show toast notification
function showNotificationToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.notification-toast');
    existingToasts.forEach(toast => toast.remove());

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed notification-toast`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
    toast.innerHTML = `
        <i class="mdi mdi-${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'information'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Add to body
    document.body.appendChild(toast);

    // Auto remove after 3 seconds
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

// Real-time notification updates (if using WebSocket or Server-Sent Events)
function startNotificationUpdates() {
    // This can be expanded to use WebSocket or SSE for real-time updates
    setInterval(() => {
        checkForNewNotifications();
    }, 30000); // Check every 30 seconds
}

// Check for new notifications
function checkForNewNotifications() {
    (window.safeJsonFetch ? window.safeJsonFetch('/api/notifications/count') : window.safeJsonFetch('/api/notifications/count'))
    .then(data => {
        if (data && data.success) {
            const currentCount = getCurrentNotificationCount();
            const newCount = data.count;

            if (newCount > currentCount) {
                // New notifications arrived
                updateNotificationBadges(newCount);
                showNotificationToast(`${newCount - currentCount} yeni bildirim`, 'info');
            } else if (newCount !== currentCount) {
                // Count changed, update badges
                updateNotificationBadges(newCount);
            }
        }
    })
    .catch(error => {
        console.error('Error checking notifications:', error);
        // optional: show brief toast for debugging
        // showNotificationToast('Bildirim kontrolünde hata', 'error');
    });
}

// Get current notification count from badge
function getCurrentNotificationCount() {
    const countBadge = document.querySelector('.notification-count-badge');
    if (countBadge) {
        return parseInt(countBadge.textContent) || 0;
    }
    return 0;
}

// Update all notification badges
function updateNotificationBadges(count) {
    // Update dropdown header badge
    const countBadge = document.querySelector('.notification-count-badge');
    if (countBadge) {
        countBadge.textContent = count + (count > 9 ? '+' : '');
    }

    // Update bell icon badge
    const bellBadge = document.querySelector('.notification-bell-badge');
    if (bellBadge) {
        if (count === 0) {
            bellBadge.style.display = 'none';
        } else {
            bellBadge.style.display = 'inline';
            bellBadge.textContent = count > 99 ? '99+' : count;
        }
    }
}

// Initialize real-time updates
startNotificationUpdates();

// Add notification click handler
function initNotificationClickHandlers() {
    document.addEventListener('click', function(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem) {
            const notificationId = notificationItem.getAttribute('data-notification-id');
            if (notificationId) {
                markNotificationAsRead(notificationId);
            }
        }
    });
}

// Mark single notification as read
function markNotificationAsRead(notificationId) {
    window.safeJsonFetch(`/api/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(data => {
        if (data && data.success) {
            updateNotificationCount(-1);
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        const msg = error.responseText ? error.responseText.substring(0,300) : error.message;
        showNotificationToast('Bildirim okunamadı: ' + msg, 'error');
    });
}

// Initialize notification click handlers
initNotificationClickHandlers();
