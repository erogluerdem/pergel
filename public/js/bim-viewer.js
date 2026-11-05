// BIM 3D Viewer Professional JavaScript
class BimViewerPro {
    constructor(options = {}) {
        this.container = options.container;
        this.model = options.model;
        this.signedUrl = options.signedUrl;

        this.viewer = null;
        this.selectedElement = null;

        this.measurePoints = [];
        this.annotations = [];
        this.viewStates = [];
        this.currentViewState = -1;

        this.performance = {
            fps: 0,
            drawCalls: 0,
            triangles: 0,
            memoryUsage: 0
        };

        this.init();
    }

    async init() {
        try {
            this.showLoading('Viewer başlatılıyor...');
            this.setupUI();
            this.setupEventListeners();
            this.updateLoadingProgress(20, 'Arayüz hazırlanıyor...');
            await this.initializeViewer();
            this.updateLoadingProgress(100, 'Hazır!');
            this.hideLoading();
            this.startPerformanceMonitoring();
            this.setupKeyboardShortcuts();
        } catch (error) {
            console.error('BIM Viewer initialization failed:', error);
            this.hideLoading();
            this.showError('Viewer başlatılamadı: ' + error.message);
        }
    }

    formatLabel(key) {
        const labels = {
            name: 'Ad',
            globalId: 'Global ID',
            type: 'Tip',
            objectType: 'Nesne Tipi',
            description: 'Açıklama',
            volume: 'Hacim',
            area: 'Alan',
            vertices: 'Köşe Sayısı',
            defines: 'Tanımlar',
            decomposes: 'Ayırır',
            decomposedBy: 'Ayırılır'
        };
        return labels[key] || key.charAt(0).toUpperCase() + key.slice(1);
    }

    formatValue(key, value) {
        if (key === 'volume' || key === 'area') {
            return typeof value === 'number' ? value.toFixed(2) + ' m²' : value;
        }
        if (key === 'vertices') {
            return typeof value === 'number' ? value.toLocaleString() : value;
        }
        return value;
    }

    setupUI() {
        // Add performance monitor
        const performanceMonitor = document.createElement('div');
        performanceMonitor.className = 'bim-performance-monitor';
        performanceMonitor.id = 'performanceMonitor';
        performanceMonitor.innerHTML = `
            <div class="bim-performance-item">
                <span>FPS:</span>
                <span id="fpsCounter">0</span>
            </div>
            <div class="bim-performance-item">
                <span>Triangles:</span>
                <span id="triangleCounter">0</span>
            </div>
            <div class="bim-performance-item">
                <span>Memory:</span>
                <span id="memoryCounter">0 MB</span>
            </div>
        `;

        // Add minimap
        const minimap = document.createElement('div');
        minimap.className = 'bim-minimap';
        minimap.innerHTML = '<canvas class="bim-minimap-canvas" id="minimapCanvas"></canvas>';

        const canvasContainer = document.getElementById('bimViewer');
        canvasContainer.appendChild(performanceMonitor);
        canvasContainer.appendChild(minimap);

        // Add loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'bim-loading-overlay';
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.innerHTML = `
            <div class="bim-loading-spinner"></div>
            <h5>Model yükleniyor...</h5>
            <p>Büyük modeller için bu işlem birkaç dakika sürebilir</p>
            <div class="bim-progress-bar">
                <div class="bim-progress-fill" id="loadingProgress"></div>
            </div>
        `;
        canvasContainer.appendChild(loadingOverlay);
    }

    setupEventListeners() {
        // Toolbar buttons
        this.bindToolbarEvents();

        // Panel interactions
        this.bindPanelEvents();

        // Canvas interactions
        this.bindCanvasEvents();

        // Window resize
        window.addEventListener('resize', () => this.handleResize());
    }

    bindToolbarEvents() {
        const buttons = {
            'btnHome': () => this.fitToView(),
            'btnZoomIn': () => this.zoomIn(),
            'btnZoomOut': () => this.zoomOut(),
            'btnPan': () => this.togglePanMode(),
            'btnRotate': () => this.toggleRotateMode(),
            'btnMeasure': () => this.toggleMeasureMode(),
            'btnSection': () => this.toggleSectionMode(),
            'btnIsolate': () => this.isolateSelection(),
            'btnHide': () => this.hideSelection(),
            'btnShow': () => this.showAll(),
            'btnWireframe': () => this.toggleWireframe(),
            'btnXRay': () => this.toggleXRay(),
            'btnFullscreen': () => this.toggleFullscreen(),
            'btnSnapshot': () => this.takeSnapshot(),
            'btnExportProps': () => this.exportProperties(),
            'btnReset': () => this.resetView()
        };

        Object.entries(buttons).forEach(([id, handler]) => {
            const btn = document.getElementById(id);
            if (btn) {
                btn.addEventListener('click', handler);
            }
        });
    }

    bindPanelEvents() {
        // Tree search
        const treeSearch = document.getElementById('treeSearch');
        if (treeSearch) {
            let searchTimeout;
            treeSearch.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.filterModelTree(e.target.value);
                }, 300);
            });
        }

        // Properties panel actions
        const exportBtn = document.getElementById('btnExportProps');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportSelectedProperties());
        }
    }

    bindCanvasEvents() {
        const canvas = document.getElementById('ifcCanvas');
        if (!canvas) return;

        // Mouse events
        canvas.addEventListener('click', (e) => this.handleCanvasClick(e));
        canvas.addEventListener('dblclick', (e) => this.handleCanvasDoubleClick(e));
        canvas.addEventListener('contextmenu', (e) => this.handleCanvasRightClick(e));

        // Touch events for mobile
        canvas.addEventListener('touchstart', (e) => this.handleTouchStart(e));
        canvas.addEventListener('touchmove', (e) => this.handleTouchMove(e));
        canvas.addEventListener('touchend', (e) => this.handleTouchEnd(e));
    }

    async initializeViewer() {
        this.updateLoadingProgress(30, '3D viewer başlatılıyor...');

        try {
            // Import IFC viewer modules
            const { IFCDemo, Color } = await this.loadViewerModules();
            this.updateLoadingProgress(50, 'Viewer modülleri yüklendi');

            // Initialize viewer
            this.viewer = new IFCDemo({
                container: document.getElementById('bimViewer')
            });
            this.updateLoadingProgress(70, 'Viewer başlatıldı');

            // Load model
            await this.loadModel();
            this.updateLoadingProgress(90, 'Model yüklendi');

            // Setup viewer events
            this.setupViewerEvents();

            // Build model tree
            await this.buildModelTree();

            this.updateLoadingProgress(100, 'Hazır!');

        } catch (error) {
            this.hideLoading();
            this.showError('Model yüklenirken hata: ' + error.message);
            throw error;
        }
    }

    async loadViewerModules() {
        try {
            const mod = await import('/js/dist/index.js');

            // Try several possible export names used by different viewer bundles
            const IFCDemo = mod.IFCDemo || mod.IfcViewerAPI || mod.IfcViewer || mod.default?.IFCDemo || mod.default?.IfcViewerAPI || mod.default;
            const Color = mod.Color || mod.default?.Color || undefined;

            if (!IFCDemo) {
                const exportedKeys = Object.keys(mod).join(', ');
                throw new Error('Modül yüklendi fakat IFCDemo/IfcViewerAPI bulunamadı. Export anahtarları: ' + exportedKeys);
            }

            return { IFCDemo, Color };
        } catch (error) {
            // Bubble a more descriptive message to the caller for debugging
            throw new Error('3D viewer kütüphanesi yüklenemedi: ' + (error && error.message ? error.message : error));
        }
    }

    async loadModel() {
        const progressBar = document.getElementById('loadingProgress');

        // Simulate loading progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 200);

        try {
            await this.viewer.loadIfcUrl(this.signedUrl);

            // Complete progress
            clearInterval(progressInterval);
            progressBar.style.width = '100%';

        } catch (error) {
            clearInterval(progressInterval);
            throw new Error('Model yüklenemedi: ' + error.message);
        }
    }

    setupViewerEvents() {
        if (!this.viewer.ifcPicker) return;

        // Element selection
        this.viewer.ifcPicker.on('click', async (event) => {
            try {
                const result = await this.viewer.ifcPicker.pickIfcItem({
                    mouse: event,
                    pickSurface: true
                });

                if (result) {
                    await this.selectElement(result.modelID, result.id);
                }
            } catch (error) {
                console.warn('Selection failed:', error);
            }
        });

        // Hover highlighting
        this.viewer.ifcPicker.on('mousemove', async (event) => {
            try {
                const result = await this.viewer.ifcPicker.pickIfcItem({
                    mouse: event,
                    pickSurface: true
                });

                if (result && result.id !== this.selectedElement?.id) {
                    this.highlightElement(result.modelID, result.id, 'hover');
                }
            } catch (error) {
                // Ignore hover errors
            }
        });
    }

    async selectElement(modelID, elementId) {
        try {
            // Clear previous selection
            this.viewer.removeAllHighlights();

            // Highlight selected element
            const Color = (await this.loadViewerModules()).Color;
            this.viewer.highlightIfcItemById(modelID, elementId, new Color(0.2, 0.8, 0.2, 0.8));

            // Get element properties
            const properties = await this.viewer.ifcManager.getItemProperties(modelID, elementId, true);

            // Update selection
            this.selectedElement = { modelID, id: elementId, properties };

            // Update properties panel
            this.updatePropertiesPanel(properties);

            // Update tree selection
            this.updateTreeSelection(elementId);

            // Enable element-specific actions
            this.updateToolbarState();

        } catch (error) {
            console.error('Element selection failed:', error);
        }
    }

    async highlightElement(modelID, elementId, type = 'select') {
        try {
            const Color = (await this.loadViewerModules()).Color;
            const colors = {
                select: new Color(0.2, 0.8, 0.2, 0.8),
                hover: new Color(0.8, 0.8, 0.2, 0.5),
                measure: new Color(0.8, 0.2, 0.2, 0.8),
                hide: new Color(0.5, 0.5, 0.5, 0.3)
            };

            this.viewer.highlightIfcItemById(modelID, elementId, colors[type] || colors.select);
        } catch (error) {
            console.warn('Highlighting failed:', error);
        }
    }

    updatePropertiesPanel(properties) {
        const panel = document.getElementById('propsPanel');
        if (!panel) return;

        if (properties) {
            // Format properties for better readability
            const formatted = this.formatProperties(properties);
            panel.innerHTML = `<pre class="bim-properties-view">${formatted}</pre>`;
        } else {
            panel.innerHTML = `
                <div class="bim-properties-empty">
                    <i class="fas fa-cube"></i>
                    <span>Element seçilmedi</span>
                </div>
            `;
        }
    }

    async buildModelTree() {
        const treeContainer = document.getElementById('modelTree');
        if (!treeContainer) return;

        try {
            this.updateStatus('Model ağacı oluşturuluyor...', 'loading');

            const project = await this.viewer.context.ifcManager.getSpatialStructure(0);
            treeContainer.innerHTML = '';

            this.buildTreeNode(project, treeContainer, 0);

        } catch (error) {
            treeContainer.innerHTML = `
                <div class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Model ağacı oluşturulamadı: ${error.message}
                </div>
            `;
        }
    }

    buildTreeNode(node, container, level) {
        const nodeEl = document.createElement('div');
        nodeEl.className = 'bim-tree-node';
        nodeEl.style.paddingLeft = (level * 15) + 'px';
        nodeEl.dataset.expressId = node.expressID;

        const icon = this.getNodeIcon(node.type);
        nodeEl.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <span>${node.type}</span>
            <small class="text-muted ml-2">${node.expressID || ''}</small>
        `;

        nodeEl.addEventListener('click', async (e) => {
            e.stopPropagation();

            // Remove previous selection
            document.querySelectorAll('.bim-tree-node.selected').forEach(el => {
                el.classList.remove('selected');
            });

            // Select this node
            nodeEl.classList.add('selected');

            // Highlight in viewer
            if (node.expressID) {
                await this.selectElement(0, node.expressID);
            }
        });

        container.appendChild(nodeEl);

        // Add children
        if (node.children && node.children.length > 0) {
            const childrenContainer = document.createElement('div');
            childrenContainer.className = 'bim-tree-children';

            node.children.forEach(child => {
                this.buildTreeNode(child, childrenContainer, level + 1);
            });

            container.appendChild(childrenContainer);
        }
    }

    getNodeIcon(nodeType) {
        const icons = {
            'IFCPROJECT': 'folder-open',
            'IFCSITE': 'map-marker-alt',
            'IFCBUILDING': 'building',
            'IFCBUILDINGSTOREY': 'layer-group',
            'IFCSPACE': 'cube',
            'IFCWALL': 'square',
            'IFCDOOR': 'door-open',
            'IFCWINDOW': 'window-maximize',
            'IFCSLAB': 'minus',
            'IFCBEAM': 'grip-lines',
            'IFCCOLUMN': 'grip-vertical',
            'IFCROOF': 'home',
            'IFCSTAIR': 'stairs'
        };

        return icons[nodeType] || 'cube';
    }

    filterModelTree(searchTerm) {
        const nodes = document.querySelectorAll('.bim-tree-node');
        const term = searchTerm.toLowerCase().trim();

        nodes.forEach(node => {
            const text = node.textContent.toLowerCase();
            const visible = !term || text.includes(term);
            node.style.display = visible ? 'block' : 'none';

            // Show parent nodes if child matches
            if (visible && term) {
                let parent = node.parentElement;
                while (parent && parent.classList.contains('bim-tree-children')) {
                    parent.style.display = 'block';
                    parent = parent.parentElement;
                }
            }
        });
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ignore if typing in input fields
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

            const shortcuts = {
                'KeyH': () => this.fitToView(),
                'KeyM': () => this.toggleMeasureMode(),
                'KeyI': () => this.isolateSelection(),
                'KeyZ': () => this.zoomToSelection(),
                'KeyC': () => this.clearSelection(),
                'KeyF': () => this.toggleFullscreen(),
                'KeyS': () => this.takeSnapshot(),
                'KeyW': () => this.toggleWireframe(),
                'KeyX': () => this.toggleXRay(),
                'Escape': () => this.cancelCurrentMode()
            };

            const handler = shortcuts[e.code];
            if (handler) {
                e.preventDefault();
                handler();
            }
        });
    }

    startPerformanceMonitoring() {
        const updatePerformance = () => {
            // Update FPS
            document.getElementById('fpsCounter').textContent = Math.round(this.performance.fps);

            // Update triangle count
            document.getElementById('triangleCounter').textContent = this.performance.triangles.toLocaleString();

            // Update memory usage
            if (performance.memory) {
                const memMB = Math.round(performance.memory.usedJSHeapSize / 1024 / 1024);
                document.getElementById('memoryCounter').textContent = memMB + ' MB';
            }

            requestAnimationFrame(updatePerformance);
        };

        updatePerformance();
    }

    // Viewer Control Methods
    fitToView() {
        try {
            this.viewer.context.ifcCameraControls.fitModelToFrame();
        } catch (error) {
            console.warn('Fit to view failed:', error);
        }
    }

    zoomIn() {
        try {
            this.viewer.context.camera.zoom *= 1.2;
            this.viewer.context.camera.updateProjectionMatrix();
        } catch (error) {
            console.warn('Zoom in failed:', error);
        }
    }

    zoomOut() {
        try {
            this.viewer.context.camera.zoom /= 1.2;
            this.viewer.context.camera.updateProjectionMatrix();
        } catch (error) {
            console.warn('Zoom out failed:', error);
        }
    }

    toggleMeasureMode() {
        this.measureMode = !this.measureMode;
        const measureInfo = document.getElementById('measureInfo');
        const btn = document.getElementById('btnMeasure');

        if (this.measureMode) {
            measureInfo.textContent = 'Ölçüm modu: Aktif - İki nokta seçin';
            measureInfo.classList.add('active');
            btn.classList.add('active');
            this.measurePoints = [];
        } else {
            measureInfo.textContent = 'Ölçüm modu: Pasif';
            measureInfo.classList.remove('active');
            btn.classList.remove('active');
            this.measurePoints = [];
        }
    }

    isolateSelection() {
        if (!this.selectedElement) {
            this.showToast('Önce bir element seçin', 'warning');
            return;
        }

        try {
            this.viewer.clipper.createFromItem(this.selectedElement.modelID, this.selectedElement.id);
            this.showToast('Element izole edildi', 'success');
        } catch (error) {
            this.showToast('İzolasyon başarısız: ' + error.message, 'error');
        }
    }

    hideSelection() {
        if (!this.selectedElement) {
            this.showToast('Önce bir element seçin', 'warning');
            return;
        }

        try {
            this.viewer.hideIfcItemById(this.selectedElement.modelID, this.selectedElement.id);
            this.showToast('Element gizlendi', 'success');
        } catch (error) {
            this.showToast('Gizleme başarısız: ' + error.message, 'error');
        }
    }

    showAll() {
        try {
            this.viewer.showAllItems();
            this.viewer.clipper.removeAll();
            this.showToast('Tüm elementler gösteriliyor', 'success');
        } catch (error) {
            this.showToast('Gösterme başarısız: ' + error.message, 'error');
        }
    }

    toggleWireframe() {
        // Implementation depends on viewer capabilities
        this.showToast('Wireframe modu geçişi', 'info');
    }

    toggleXRay() {
        // Implementation depends on viewer capabilities
        this.showToast('X-Ray modu geçişi', 'info');
    }

    toggleFullscreen() {
        const container = document.getElementById('bimViewer');

        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => {
                this.showToast('Tam ekran modu başarısız: ' + err.message, 'error');
            });
        } else {
            document.exitFullscreen();
        }
    }

    takeSnapshot() {
        try {
            const canvas = document.getElementById('ifcCanvas');
            const link = document.createElement('a');
            link.download = `bim-snapshot-${Date.now()}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();

            this.showToast('Ekran görüntüsü alındı', 'success');
        } catch (error) {
            this.showToast('Ekran görüntüsü alınamadı: ' + error.message, 'error');
        }
    }

    exportSelectedProperties() {
        if (!this.selectedElement || !this.selectedElement.properties) {
            this.showToast('Önce bir element seçin', 'warning');
            return;
        }

        try {
            const data = JSON.stringify(this.selectedElement.properties, null, 2);
            const blob = new Blob([data], { type: 'application/json' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.download = `element-properties-${this.selectedElement.id}.json`;
            link.href = url;
            link.click();

            URL.revokeObjectURL(url);
            this.showToast('Özellikler dışa aktarıldı', 'success');
        } catch (error) {
            this.showToast('Dışa aktarma başarısız: ' + error.message, 'error');
        }
    }

    // Utility Methods
    updateStatus(message, type = 'info') {
        const statusEl = document.getElementById('viewerStatus');
        if (statusEl) {
            statusEl.textContent = message;
            statusEl.className = `bim-viewer-status ${type}`;
        }
    }

    showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : type === 'success' ? '#28a745' : '#17a2b8'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Show animation
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showError(message) {
        const container = document.getElementById('bimViewer');
        container.innerHTML = `
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5>Viewer Hatası</h5>
                    <p>${message}</p>
                    <button class="btn btn-outline-danger" onclick="location.reload()">
                        <i class="fas fa-redo"></i> Yeniden Dene
                    </button>
                </div>
            </div>
        `;
    }

    handleResize() {
        if (this.viewer && this.viewer.context) {
            try {
                this.viewer.context.resize();
            } catch (error) {
                console.warn('Resize failed:', error);
            }
        }
    }

    // Touch event handlers for mobile
    handleTouchStart(e) {
        // Handle touch start
    }

    handleTouchMove(e) {
        // Handle touch move
    }

    handleTouchEnd(e) {
        // Handle touch end
    }

    handleCanvasClick(e) {
        // Handle canvas click events
    }

    handleCanvasDoubleClick(e) {
        if (this.measureMode) {
            // Handle measure point selection
        } else {
            // Fit to view on double click
            this.fitToView();
        }
    }

    handleCanvasRightClick(e) {
        e.preventDefault();
        // Show context menu
    }

    cancelCurrentMode() {
        if (this.measureMode) {
            this.toggleMeasureMode();
        }
        this.clearSelection();
    }

    clearSelection() {
        try {
            this.viewer.removeAllHighlights();
            this.selectedElement = null;
            this.updatePropertiesPanel(null);

            // Clear tree selection
            document.querySelectorAll('.bim-tree-node.selected').forEach(el => {
                el.classList.remove('selected');
            });

            this.updateToolbarState();
        } catch (error) {
            console.warn('Clear selection failed:', error);
        }
    }

    updateToolbarState() {
        const hasSelection = !!this.selectedElement;

        // Enable/disable selection-dependent buttons
        const selectionButtons = ['btnIsolate', 'btnHide', 'btnZoom', 'btnExportProps'];
        selectionButtons.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) {
                btn.disabled = !hasSelection;
            }
        });
    }

    updateTreeSelection(elementId) {
        // Clear previous selection
        document.querySelectorAll('.bim-tree-node.selected').forEach(el => {
            el.classList.remove('selected');
        });

        // Select current element in tree
        const treeNode = document.querySelector(`[data-express-id="${elementId}"]`);
        if (treeNode) {
            treeNode.classList.add('selected');
            treeNode.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    saveViewState(name) {
        if (!this.viewer) return;

        try {
            const camera = this.viewer.context.camera;
            const state = {
                name,
                position: camera.position.clone(),
                target: this.viewer.context.ifcCameraControls.target.clone(),
                zoom: camera.zoom,
                timestamp: Date.now()
            };

            this.viewStates.push(state);
            this.currentViewState = this.viewStates.length - 1;
        } catch (error) {
            console.warn('Save view state failed:', error);
        }
    }

    restoreViewState(index) {
        if (!this.viewer || !this.viewStates[index]) return;

        try {
            const state = this.viewStates[index];
            const camera = this.viewer.context.camera;

            camera.position.copy(state.position);
            camera.zoom = state.zoom;
            this.viewer.context.ifcCameraControls.target.copy(state.target);

            camera.updateProjectionMatrix();
            this.currentViewState = index;
        } catch (error) {
            console.warn('Restore view state failed:', error);
        }
    }

    showLoading(message = 'Yükleniyor...') {
        const loadingEl = document.getElementById('viewerLoading');
        const statusEl = document.getElementById('loadingStatus');
        if (loadingEl) {
            loadingEl.style.display = 'flex';
        }
        if (statusEl) {
            statusEl.textContent = message;
        }
    }

    hideLoading() {
        const loadingEl = document.getElementById('viewerLoading');
        if (loadingEl) {
            loadingEl.style.display = 'none';
        }
    }

    updateLoadingProgress(percent, message = '') {
        const progressEl = document.getElementById('loadingProgress');
        const statusEl = document.getElementById('loadingStatus');

        if (progressEl) {
            progressEl.style.width = percent + '%';
        }
        if (statusEl && message) {
            statusEl.textContent = message;
        }
    }
}

// Initialize viewer when DOM is ready
window.initBimViewer = function(options) {
    return new BimViewerPro(options);
};
