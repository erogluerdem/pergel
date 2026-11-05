
(function () {
    try {
        var savedMode = localStorage.getItem("data-layout-mode") || sessionStorage.getItem("data-layout-mode");
        if (savedMode === "dark" || savedMode === "light") {
            document.documentElement.setAttribute('data-bs-theme', savedMode);
        }
    } catch (e) {
        console.warn('Unable to load saved theme mode:', e);
    }
})();

