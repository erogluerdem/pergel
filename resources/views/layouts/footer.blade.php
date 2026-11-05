<footer class="footer" data-app-footer>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © Pergel.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://erdemeroglu.com.tr/" target="_blank" class="text-muted">Erdem Eroğlu</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Last-resort footer override: placed next to footer markup so it's loaded late and is specific */
[data-app-footer].footer {
    position: static !important;
    margin-top: auto !important;
    width: 100% !important;
    left: auto !important;
    right: auto !important;
    bottom: auto !important;
    transform: none !important;
}

/* Ensure parent layout uses column flex if not already set (non-invasive) */
#layout-wrapper, .layout-wrapper {
    display: flex !important;
    flex-direction: column !important;
    min-height: 100vh !important;
}

.main-content, .content-page, .page-content {
    flex: 1 0 auto !important;
}
</style>
