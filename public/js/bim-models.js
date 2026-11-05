// BIM Models Modern Dashboard JavaScript
class BimModelsManager {
    constructor() {
        this.selectedModels = new Set();
        this.currentView = 'grid';
        this.filters = {
            search: '',
            discipline: '',
            status: '',
            fileType: ''
        };

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupDragAndDrop();
        this.setupViewToggle();
        this.setupFilterChips();
        this.loadStats();
        this.setupAutoRefresh();
    }

    setupEventListeners() {
        // Search input with debounce
        const searchInput = document.getElementById('bim-search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.filters.search = e.target.value;
                    this.applyFilters();
                }, 300);
            });
        }

        // Filter selects
        document.querySelectorAll('.bim-filter-select').forEach(select => {
            select.addEventListener('change', (e) => {
                const filterType = e.target.dataset.filter;
                this.filters[filterType] = e.target.value;
                this.applyFilters();
                this.updateFilterChips();
            });
        });

        // Model selection
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('bim-model-checkbox')) {
                this.handleModelSelection(e.target);
            }
        });

        // Select all checkbox
        const selectAllBtn = document.getElementById('selectAll');
        if (selectAllBtn) {
            selectAllBtn.addEventListener('change', (e) => {
                this.toggleSelectAll(e.target.checked);
            });
        }

        // Clash test modal
        const clashBtn = document.getElementById('openClashModal');
        if (clashBtn) {
            clashBtn.addEventListener('click', () => {
                this.openClashModal();
            });
        }

        // File upload
        const uploadBtn = document.getElementById('uploadModelBtn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', () => {
                this.openUploadModal();
            });
        }
    }

    setupDragAndDrop() {
        const dropZone = document.getElementById('fileDropZone');
        if (!dropZone) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('dragover');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            this.handleFileUpload(files);
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    setupViewToggle() {
        document.querySelectorAll('.bim-view-toggle-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.target.dataset.view;
                this.switchView(view);
            });
        });
    }

    setupFilterChips() {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('bim-filter-chip-remove')) {
                const chip = e.target.closest('.bim-filter-chip');
                const filterType = chip.dataset.filter;
                this.removeFilter(filterType);
            }
        });
    }

    switchView(view) {
        this.currentView = view;

        // Update toggle buttons
        document.querySelectorAll('.bim-view-toggle-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.view === view);
        });

        // Update container class
        const container = document.getElementById('modelsContainer');
        if (container) {
            container.className = view === 'grid' ? 'bim-models-grid' : 'bim-models-list';
        }

        // Save preference
        localStorage.setItem('bim-view-preference', view);
    }

    handleModelSelection(checkbox) {
        const modelId = parseInt(checkbox.value);
        const card = checkbox.closest('.bim-model-card');

        if (checkbox.checked) {
            this.selectedModels.add(modelId);
            card.classList.add('bim-model-selected');
        } else {
            this.selectedModels.delete(modelId);
            card.classList.remove('bim-model-selected');
        }

        this.updateSelectionUI();
    }

    toggleSelectAll(checked) {
        document.querySelectorAll('.bim-model-checkbox').forEach(checkbox => {
            checkbox.checked = checked;
            this.handleModelSelection(checkbox);
        });
    }

    updateSelectionUI() {
        const selectedCount = this.selectedModels.size;
        const clashBtn = document.getElementById('openClashModal');

        if (clashBtn) {
            clashBtn.disabled = selectedCount < 2;
            clashBtn.innerHTML = selectedCount > 0
                ? `<i class="fas fa-bolt"></i> Çakışma Testi Oluştur (${selectedCount} model)`
                : '<i class="fas fa-bolt"></i> Çakışma Testi Oluştur';
        }

        // Update selection info
        const selectionInfo = document.getElementById('selectionInfo');
        if (selectionInfo) {
            selectionInfo.innerHTML = selectedCount > 0
                ? `${selectedCount} model seçildi`
                : '';
        }
    }

    applyFilters() {
        const cards = document.querySelectorAll('.bim-model-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name?.toLowerCase() || '';
            const discipline = card.dataset.discipline || '';
            const status = card.dataset.status || '';
            const fileType = card.dataset.fileType || '';

            const matchesSearch = !this.filters.search ||
                name.includes(this.filters.search.toLowerCase());
            const matchesDiscipline = !this.filters.discipline ||
                discipline === this.filters.discipline;
            const matchesStatus = !this.filters.status ||
                status === this.filters.status;
            const matchesFileType = !this.filters.fileType ||
                fileType === this.filters.fileType;

            const visible = matchesSearch && matchesDiscipline &&
                          matchesStatus && matchesFileType;

            card.style.display = visible ? 'block' : 'none';
            if (visible) visibleCount++;
        });

        this.updateResultsCount(visibleCount);
    }

    updateFilterChips() {
        const chipsContainer = document.getElementById('filterChips');
        if (!chipsContainer) return;

        chipsContainer.innerHTML = '';

        Object.entries(this.filters).forEach(([key, value]) => {
            if (value && key !== 'search') {
                const chip = document.createElement('span');
                chip.className = 'bim-filter-chip';
                chip.dataset.filter = key;
                chip.innerHTML = `
                    ${this.getFilterLabel(key)}: ${value}
                    <span class="bim-filter-chip-remove">&times;</span>
                `;
                chipsContainer.appendChild(chip);
            }
        });
    }

    getFilterLabel(key) {
        const labels = {
            discipline: 'Disiplin',
            status: 'Durum',
            fileType: 'Dosya Tipi'
        };
        return labels[key] || key;
    }

    removeFilter(filterType) {
        this.filters[filterType] = '';

        // Update UI
        const select = document.querySelector(`[data-filter="${filterType}"]`);
        if (select) select.value = '';

        this.applyFilters();
        this.updateFilterChips();
    }

    updateResultsCount(count) {
        const counter = document.getElementById('resultsCount');
        if (counter) {
            counter.textContent = `${count} model gösteriliyor`;
        }
    }

    openClashModal() {
        const selectedArray = Array.from(this.selectedModels);
        const half = Math.ceil(selectedArray.length / 2);

        document.getElementById('modelsA').value = selectedArray.slice(0, half).join(',');
        document.getElementById('modelsB').value = selectedArray.slice(half).join(',');

        $('#clashModal').modal('show');
    }

    async openUploadModal() {
        // Open slide-in upload panel and load form via AJAX
        try {
            const modalEl = document.getElementById('uploadModal');
            const content = document.getElementById('uploadModalContent');
            // Show Bootstrap modal (requires bootstrap JS)
            if (window.bootstrap && modalEl) {
                const modal = new window.bootstrap.Modal(modalEl);
                modal.show();
            }

            const response = await fetch('/bim/models/upload-partial', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Yükleme formu yüklenemedi');
            const html = await response.text();

            // If the response looks like a full HTML page (login redirect or full layout), avoid injecting it.
            // Detect common signs (doctype, <html>, <body>) and verify the expected fragment exists.
            try {
                const contentType = response.headers.get('content-type') || '';
                const looksLikeFullPage = /<!doctype html>|<html\b|<body\b/i.test(html);
                const isHtmlMime = /text\/html/i.test(contentType) || contentType === '';
                if (isHtmlMime && looksLikeFullPage) {
                    const probe = new DOMParser().parseFromString(html, 'text/html');
                    if (!probe.querySelector('.bim-models-container')) {
                        console.warn('Upload partial returned a full page; redirecting to full upload page.');
                        if (window.bootstrap && modalEl) {
                            try { const m = window.bootstrap.Modal.getInstance(modalEl); if (m) m.hide(); } catch (e) {}
                        }
                        window.location.href = '/bim/models/upload';
                        return;
                    }
                }

                // Parse the returned HTML and extract only the main fragment that contains the upload UI.
                // This avoids executing global footer/head scripts (especially those using document.write)
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const fragment = doc.querySelector('.bim-models-container') || doc.body;
                // Create a clone where script tags are removed to avoid executing them during innerHTML set
                const safeClone = fragment.cloneNode(true);
                const scriptsInClone = safeClone.querySelectorAll('script');
                scriptsInClone.forEach(s => s.parentNode && s.parentNode.removeChild(s));

                // Inject the safe HTML (without scripts)
                content.innerHTML = safeClone.innerHTML;

                // Now handle original scripts from the fragment manually, executing only safe ones
                const scripts = fragment.querySelectorAll('script');
                // Execute inline scripts safely and conditionally load external scripts.
                scripts.forEach(oldScript => {
                    try {
                        const text = oldScript.textContent || '';
                        if (/document\.write\s*\(/i.test(text)) {
                            console.warn('Skipped unsafe script containing document.write');
                            return;
                        }

                        if (oldScript.src) {
                            // Only allow obvious JS files (endsWith .js) to be added directly.
                            // Some src attributes may point to routes that return HTML (causing "expected '<'" errors).
                            const src = oldScript.getAttribute('src') || '';
                            const isLikelyJs = /\.js($|\?|#)/i.test(src);

                            if (!isLikelyJs) {
                                // As a safer fallback, try a HEAD request to check Content-Type, but avoid CORS issues.
                                console.warn('Skipping external script with non-js-looking src:', src);
                                return;
                            }

                            const newScript = document.createElement('script');
                            newScript.src = src;
                            if (oldScript.async) newScript.async = true;
                            if (oldScript.defer) newScript.defer = true;
                            document.head.appendChild(newScript);
                        } else {
                            const newScript = document.createElement('script');
                            newScript.textContent = text;
                            // append to content so any DOM queries inside script find the injected nodes
                            content.appendChild(newScript);
                            // remove it to avoid duplicates
                            content.removeChild(newScript);
                        }
                    } catch (sErr) {
                        console.error('Error evaluating injected script', sErr);
                    }
                });
            } catch (parseErr) {
                // fallback: set raw HTML if parsing failed
                content.innerHTML = html;
            }
        } catch (error) {
            console.error('Upload panel yüklenemedi:', error);
            window.location.href = '/bim/models/upload';
        }
    }

    async handleFileUpload(files) {
        if (files.length === 0) return;

        const formData = new FormData();
        formData.append('file', files[0]);

        // Show loading
        this.showLoading();

        try {
            const response = await fetch('/bim/models', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                this.showToast('Model başarıyla yüklendi!', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                throw new Error('Upload failed');
            }
        } catch (error) {
            this.showToast('Model yükleme hatası!', 'error');
        } finally {
            this.hideLoading();
        }
    }

    async loadStats() {
        try {
            const response = await fetch('/bim/models?json=1');
            const data = await response.json();

            // Update stats cards
            this.updateStatsCards(data.stats);
        } catch (error) {
            console.error('İstatistikler yüklenemedi:', error);
        }
    }

    updateStatsCards(stats) {
        if (!stats) return;

        const updates = {
            'totalModels': stats.total || 0,
            'readyModels': stats.ready || 0,
            'processingModels': stats.processing || 0,
            'totalSize': this.formatFileSize(stats.totalSize || 0)
        };

        Object.entries(updates).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                this.animateNumber(element, value);
            }
        });
    }

    animateNumber(element, targetValue) {
        if (typeof targetValue !== 'number') {
            element.textContent = targetValue;
            return;
        }

        const startValue = parseInt(element.textContent) || 0;
        const duration = 1000;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.round(startValue + (targetValue - startValue) * easeOut);

            element.textContent = currentValue;

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    setupAutoRefresh() {
        // Auto refresh every 30 seconds for processing models
        setInterval(() => {
            const processingModels = document.querySelectorAll('[data-status="processing"]');
            if (processingModels.length > 0) {
                this.refreshProcessingModels();
            }
        }, 30000);
    }

    async refreshProcessingModels() {
        try {
            const response = await fetch(window.location.href + '?json=1');
            const data = await response.json();

            // Update processing model cards
            data.models.forEach(model => {
                const card = document.querySelector(`[data-model-id="${model.id}"]`);
                if (card && model.status !== card.dataset.status) {
                    this.updateModelCard(card, model);
                }
            });
        } catch (error) {
            console.error('Model durumları güncellenemedi:', error);
        }
    }

    updateModelCard(card, modelData) {
        // Update status
        const statusBadge = card.querySelector('.bim-status-badge');
        if (statusBadge) {
            statusBadge.className = `bim-status-badge bim-status-${modelData.status}`;
            statusBadge.textContent = modelData.status.toUpperCase();
        }

        // Update dataset
        card.dataset.status = modelData.status;

        // Show notification if status changed to ready
        if (modelData.status === 'ready') {
            this.showToast(`${modelData.name} modeli hazır!`, 'success');
        }
    }

    showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('active');
        }
    }

    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('active');
        }
    }

    showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `bim-toast bim-toast-${type}`;
        toast.innerHTML = `
            <div class="bim-toast-content">
                <i class="fas fa-${this.getToastIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(toast);

        // Show animation
        setTimeout(() => toast.classList.add('show'), 100);

        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    getToastIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Toast styles
const toastStyles = `
<style>
.bim-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 15px 20px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 10000;
    border-left: 4px solid;
}

.bim-toast.show {
    transform: translateX(0);
}

.bim-toast-success { border-left-color: #28a745; }
.bim-toast-error { border-left-color: #dc3545; }
.bim-toast-warning { border-left-color: #ffc107; }
.bim-toast-info { border-left-color: #17a2b8; }

.bim-toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.bim-toast-content i {
    font-size: 1.2rem;
}

.bim-toast-success i { color: #28a745; }
.bim-toast-error i { color: #dc3545; }
.bim-toast-warning i { color: #ffc107; }
.bim-toast-info i { color: #17a2b8; }
</style>
`;

document.head.insertAdjacentHTML('beforeend', toastStyles);

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.bimManager = new BimModelsManager();
});
